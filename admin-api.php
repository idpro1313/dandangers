<?php
/**
 * API для админ-панели редактирования страниц
 */

// Отключаем вывод ошибок в HTML
ini_set('display_errors', 0);
error_reporting(E_ALL);

// Устанавливаем обработчик ошибок для вывода в JSON
set_error_handler(function($severity, $message, $file, $line) {
    // Игнорируем ошибки, подавленные с @ (error_reporting = 0)
    if (!(error_reporting() & $severity)) {
        return false;
    }
    throw new ErrorException($message, 0, $severity, $file, $line);
});

set_exception_handler(function($e) {
    http_response_code(500);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'success' => false,
        'error' => 'PHP Error: ' . $e->getMessage(),
        'file' => basename($e->getFile()),
        'line' => $e->getLine()
    ]);
    exit;
});

// Буферизация вывода для перехвата любых ошибок до header
ob_start();

session_start();
header('Content-Type: application/json; charset=utf-8');

// Проверяем существование конфига
if (!file_exists(__DIR__ . '/admin-config.php')) {
    echo json_encode(['success' => false, 'error' => 'Файл admin-config.php не найден']);
    exit;
}

require_once 'admin-config.php';

// Очищаем буфер (на случай если были предупреждения)
ob_end_clean();

// Убеждаемся что $ALLOWED_FILES доступна
if (!isset($ALLOWED_FILES) || !is_array($ALLOWED_FILES)) {
    $ALLOWED_FILES = [];
}

// Убеждаемся что $ALLOWED_MD_FILES доступна
if (!isset($ALLOWED_MD_FILES) || !is_array($ALLOWED_MD_FILES)) {
    $ALLOWED_MD_FILES = [];
}

// Функция проверки - это MD файл?
function isMdFile($file) {
    return pathinfo($file, PATHINFO_EXTENSION) === 'md';
}

// Функция проверки разрешённости файла (HTML или MD)
function isFileAllowed($file) {
    global $ALLOWED_FILES, $ALLOWED_MD_FILES;
    return in_array($file, $ALLOWED_FILES) || array_key_exists($file, $ALLOWED_MD_FILES);
}

// CORS для локальной разработки (уберите в продакшене если не нужно)
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// Функция логирования (безопасная - не ломает систему при ошибке записи)
function logAction($action, $file, $user = 'admin') {
    if (!defined('ENABLE_LOGGING') || !ENABLE_LOGGING) return;
    if (!defined('LOG_FILE')) return;
    
    $timestamp = date('Y-m-d H:i:s');
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $log = "[$timestamp] [$ip] [$user] $action: $file\n";
    
    // @ подавляет ошибку если нет прав на запись
    @file_put_contents(LOG_FILE, $log, FILE_APPEND | LOCK_EX);
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
        
        $files = [];
        $mdFiles = [];
        
        // HTML файлы
        foreach ($ALLOWED_FILES as $file) {
            $path = SITE_ROOT . $file;
            if (file_exists($path)) {
                $files[] = [
                    'name' => $file,
                    'modified' => date('Y-m-d H:i:s', filemtime($path)),
                    'size' => filesize($path),
                    'type' => 'html'
                ];
            }
        }
        
        // MD файлы (гайды)
        foreach ($ALLOWED_MD_FILES as $file => $title) {
            $path = SITE_ROOT . $file;
            if (file_exists($path)) {
                $mdFiles[] = [
                    'name' => $file,
                    'title' => $title,
                    'modified' => date('Y-m-d H:i:s', filemtime($path)),
                    'size' => filesize($path),
                    'type' => 'md'
                ];
            }
        }
        
        echo json_encode(['success' => true, 'files' => $files, 'mdFiles' => $mdFiles]);
        break;
        
    case 'get_content':
        if (!checkAuth()) {
            http_response_code(401);
            echo json_encode(['error' => 'Не авторизован']);
            break;
        }
        
        $file = $_GET['file'] ?? '';
        
        if (!isFileAllowed($file)) {
            echo json_encode(['success' => false, 'error' => 'Файл не разрешён для редактирования']);
            break;
        }
        
        $path = SITE_ROOT . $file;
        
        if (!file_exists($path)) {
            echo json_encode(['success' => false, 'error' => 'Файл не найден']);
            break;
        }
        
        $content = file_get_contents($path);
        
        // Для MD файлов - возвращаем весь контент как есть
        if (isMdFile($file)) {
            logAction('READ', $file);
            echo json_encode([
                'success' => true, 
                'content' => $content,
                'fullContent' => $content,
                'file' => $file,
                'type' => 'md'
            ]);
            break;
        }
        
        // Для HTML файлов - извлекаем содержимое content-wrapper
        $editableContent = '';
        
        // Ищем content-wrapper и извлекаем его содержимое
        $startMarker = '<div class="content-wrapper">';
        $endMarker = '</main>';
        
        $startPos = strpos($content, $startMarker);
        if ($startPos !== false) {
            $startPos += strlen($startMarker);
            $endPos = strpos($content, $endMarker, $startPos);
            if ($endPos !== false) {
                // Находим последний </div> перед </main>
                $section = substr($content, $startPos, $endPos - $startPos);
                // Убираем последний </div> который закрывает content-wrapper
                $lastDivPos = strrpos($section, '</div>');
                if ($lastDivPos !== false) {
                    $editableContent = trim(substr($section, 0, $lastDivPos));
                } else {
                    $editableContent = trim($section);
                }
            }
        }
        
        // Fallback: пробуем найти <main>
        if (empty($editableContent) && preg_match('/<main[^>]*>(.*)<\/main>/s', $content, $matches)) {
            $editableContent = trim($matches[1]);
        }
        
        logAction('READ', $file);
        echo json_encode([
            'success' => true, 
            'content' => $editableContent,
            'fullContent' => $content,
            'file' => $file,
            'type' => 'html'
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
        
        if (!isFileAllowed($file)) {
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
        // Для MD файлов создаём путь к бэкапу с подпапкой
        $backupFileName = str_replace('/', '_', $file) . '.' . date('Y-m-d_H-i-s') . '.bak';
        $backupFile = $backupPath . $backupFileName;
        file_put_contents($backupFile, $originalContent);
        
        // Для MD файлов - сохраняем контент напрямую
        if (isMdFile($file)) {
            if (file_put_contents($path, $newContent, LOCK_EX)) {
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
        }
        
        // Для HTML файлов - заменяем содержимое content-wrapper
        $updatedContent = $originalContent;
        
        $startMarker = '<div class="content-wrapper">';
        $endMarker = '</main>';
        
        $startPos = strpos($originalContent, $startMarker);
        if ($startPos !== false) {
            $contentStart = $startPos + strlen($startMarker);
            $endPos = strpos($originalContent, $endMarker, $contentStart);
            if ($endPos !== false) {
                // Находим последний </div> перед </main>
                $section = substr($originalContent, $contentStart, $endPos - $contentStart);
                $lastDivPos = strrpos($section, '</div>');
                if ($lastDivPos !== false) {
                    $actualEnd = $contentStart + $lastDivPos;
                    // Собираем новый контент
                    $updatedContent = substr($originalContent, 0, $contentStart) . 
                                      "\n" . $newContent . "\n" . 
                                      substr($originalContent, $actualEnd);
                }
            }
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
        // Формат: filename.ext.YYYY-MM-DD_HH-ii-ss.bak или content_guides_name.md.YYYY-MM-DD_HH-ii-ss.bak
        $baseName = basename($backupFile);
        $originalFile = preg_replace('/\.\d{4}-\d{2}-\d{2}_\d{2}-\d{2}-\d{2}\.bak$/', '', $baseName);
        
        // Восстанавливаем путь для MD файлов (content_guides_name.md -> content/guides/name.md)
        $originalFile = str_replace('_', '/', $originalFile);
        
        if (!isFileAllowed($originalFile)) {
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
