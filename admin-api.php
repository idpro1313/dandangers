<?php
/**
 * API для админ-панели редактирования страниц
 */

session_start();
header('Content-Type: application/json; charset=utf-8');

require_once 'admin-config.php';

// CORS для локальной разработки (уберите в продакшене если не нужно)
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// Функция логирования
function logAction($action, $file, $user = 'admin') {
    if (!ENABLE_LOGGING) return;
    
    $timestamp = date('Y-m-d H:i:s');
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $log = "[$timestamp] [$ip] [$user] $action: $file\n";
    file_put_contents(LOG_FILE, $log, FILE_APPEND | LOCK_EX);
}

// Проверка авторизации
function checkAuth() {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

// Получение действия
$action = $_GET['action'] ?? $_POST['action'] ?? '';

// Обработка запросов
switch ($action) {
    
    case 'login':
        $password = $_POST['password'] ?? '';
        
        if ($password === ADMIN_PASSWORD) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['login_time'] = time();
            logAction('LOGIN', 'success');
            echo json_encode(['success' => true, 'message' => 'Авторизация успешна']);
        } else {
            logAction('LOGIN_FAILED', 'invalid password');
            echo json_encode(['success' => false, 'message' => 'Неверный пароль']);
        }
        break;
        
    case 'logout':
        logAction('LOGOUT', 'admin');
        session_destroy();
        echo json_encode(['success' => true, 'message' => 'Выход выполнен']);
        break;
        
    case 'check_auth':
        echo json_encode(['authenticated' => checkAuth()]);
        break;
        
    case 'get_files':
        if (!checkAuth()) {
            http_response_code(401);
            echo json_encode(['error' => 'Не авторизован']);
            break;
        }
        
        global $ALLOWED_FILES;
        $files = [];
        
        foreach ($ALLOWED_FILES as $file) {
            $path = SITE_ROOT . $file;
            if (file_exists($path)) {
                $files[] = [
                    'name' => $file,
                    'modified' => date('Y-m-d H:i:s', filemtime($path)),
                    'size' => filesize($path)
                ];
            }
        }
        
        echo json_encode(['success' => true, 'files' => $files]);
        break;
        
    case 'get_content':
        if (!checkAuth()) {
            http_response_code(401);
            echo json_encode(['error' => 'Не авторизован']);
            break;
        }
        
        $file = $_GET['file'] ?? '';
        global $ALLOWED_FILES;
        
        if (!in_array($file, $ALLOWED_FILES)) {
            echo json_encode(['success' => false, 'error' => 'Файл не разрешён для редактирования']);
            break;
        }
        
        $path = SITE_ROOT . $file;
        
        if (!file_exists($path)) {
            echo json_encode(['success' => false, 'error' => 'Файл не найден']);
            break;
        }
        
        $content = file_get_contents($path);
        
        // Извлекаем только содержимое между <main> и </main> или <body> и </body>
        $editableContent = '';
        
        // Пробуем найти content-wrapper
        if (preg_match('/<div class="content-wrapper[^"]*">(.*?)<\/div>\s*<\/main>/s', $content, $matches)) {
            $editableContent = trim($matches[1]);
        } elseif (preg_match('/<main[^>]*>(.*?)<\/main>/s', $content, $matches)) {
            $editableContent = trim($matches[1]);
        }
        
        logAction('READ', $file);
        echo json_encode([
            'success' => true, 
            'content' => $editableContent,
            'fullContent' => $content,
            'file' => $file
        ]);
        break;
        
    case 'save_content':
        if (!checkAuth()) {
            http_response_code(401);
            echo json_encode(['error' => 'Не авторизован']);
            break;
        }
        
        $file = $_POST['file'] ?? '';
        $newContent = $_POST['content'] ?? '';
        
        global $ALLOWED_FILES;
        
        if (!in_array($file, $ALLOWED_FILES)) {
            echo json_encode(['success' => false, 'error' => 'Файл не разрешён для редактирования']);
            break;
        }
        
        $path = SITE_ROOT . $file;
        
        if (!file_exists($path)) {
            echo json_encode(['success' => false, 'error' => 'Файл не найден']);
            break;
        }
        
        // Читаем оригинальный файл
        $originalContent = file_get_contents($path);
        
        // Создаём резервную копию
        $backupPath = SITE_ROOT . 'backups/';
        if (!is_dir($backupPath)) {
            mkdir($backupPath, 0755, true);
        }
        $backupFile = $backupPath . $file . '.' . date('Y-m-d_H-i-s') . '.bak';
        file_put_contents($backupFile, $originalContent);
        
        // Заменяем содержимое content-wrapper
        $updatedContent = $originalContent;
        
        if (preg_match('/(<div class="content-wrapper[^"]*">)(.*?)(<\/div>\s*<\/main>)/s', $originalContent, $matches)) {
            $updatedContent = preg_replace(
                '/(<div class="content-wrapper[^"]*">)(.*?)(<\/div>\s*<\/main>)/s',
                '$1' . "\n" . $newContent . "\n" . '$3',
                $originalContent
            );
        }
        
        // Сохраняем
        if (file_put_contents($path, $updatedContent, LOCK_EX)) {
            logAction('SAVE', $file);
            echo json_encode([
                'success' => true, 
                'message' => 'Файл сохранён',
                'backup' => basename($backupFile)
            ]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Ошибка записи файла']);
        }
        break;
        
    case 'get_backups':
        if (!checkAuth()) {
            http_response_code(401);
            echo json_encode(['error' => 'Не авторизован']);
            break;
        }
        
        $backupPath = SITE_ROOT . 'backups/';
        $backups = [];
        
        if (is_dir($backupPath)) {
            $files = scandir($backupPath, SCANDIR_SORT_DESCENDING);
            foreach ($files as $f) {
                if ($f !== '.' && $f !== '..' && pathinfo($f, PATHINFO_EXTENSION) === 'bak') {
                    $backups[] = [
                        'name' => $f,
                        'date' => date('Y-m-d H:i:s', filemtime($backupPath . $f)),
                        'size' => filesize($backupPath . $f)
                    ];
                }
            }
        }
        
        echo json_encode(['success' => true, 'backups' => array_slice($backups, 0, 50)]);
        break;
        
    case 'restore_backup':
        if (!checkAuth()) {
            http_response_code(401);
            echo json_encode(['error' => 'Не авторизован']);
            break;
        }
        
        $backupFile = $_POST['backup'] ?? '';
        $backupPath = SITE_ROOT . 'backups/' . basename($backupFile);
        
        if (!file_exists($backupPath)) {
            echo json_encode(['success' => false, 'error' => 'Резервная копия не найдена']);
            break;
        }
        
        // Определяем оригинальный файл из имени бэкапа
        $originalFile = preg_replace('/\.\d{4}-\d{2}-\d{2}_\d{2}-\d{2}-\d{2}\.bak$/', '', basename($backupFile));
        
        global $ALLOWED_FILES;
        if (!in_array($originalFile, $ALLOWED_FILES)) {
            echo json_encode(['success' => false, 'error' => 'Файл не разрешён']);
            break;
        }
        
        $targetPath = SITE_ROOT . $originalFile;
        $backupContent = file_get_contents($backupPath);
        
        if (file_put_contents($targetPath, $backupContent, LOCK_EX)) {
            logAction('RESTORE', "$originalFile from $backupFile");
            echo json_encode(['success' => true, 'message' => "Файл $originalFile восстановлен"]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Ошибка восстановления']);
        }
        break;
        
    default:
        echo json_encode(['error' => 'Неизвестное действие']);
}
