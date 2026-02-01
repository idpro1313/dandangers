<?php
/**
 * Конфигурация админ-панели
 */

// Загружаем пароль из отдельного файла (не в git)
$passwordFile = __DIR__ . '/admin-password.php';
if (file_exists($passwordFile)) {
    require_once $passwordFile;
} else {
    // Если файл не найден, показываем ошибку
    die(json_encode([
        'success' => false,
        'error' => 'Файл admin-password.php не найден. Скопируйте admin-password.example.php в admin-password.php и установите пароль.'
    ]));
}

// Разрешённые для редактирования HTML файлы
$ALLOWED_FILES = [
    'index.html',
    'main.html',
    'daily.html',
    'tabl.html',
    '17sovetov.html',
    'bigguide.html',
    'fullguide.html',
    'heroes.html',
    'lab.html',
    'res.html',
    'lootbar.html',
    'lootbar-instruction.html',
    'novoe.html'
];

// Разрешённые для редактирования MD файлы (гайды)
$ALLOWED_MD_FILES = [
    'content/guides/vvedenie.md' => 'Введение и стратегии',
    'content/guides/razvitie.md' => 'Развитие без доната',
    'content/guides/zdaniya.md' => 'Здания и приоритеты',
    'content/guides/laboratoriya.md' => 'Лаборатория и науки',
    'content/guides/geroi.md' => 'Герои и боевые группы',
    'content/guides/snariazhenie.md' => 'Снаряжение и артефакты',
    'content/guides/resursy.md' => 'Ресурсы и фарм',
    'content/guides/sobytiya.md' => 'События и оптимизация',
    'content/guides/soyuz.md' => 'Союз и карта',
    'content/guides/mehaniki.md' => 'Специальные механики',
    'content/guides/start.md' => 'Быстрый старт',
    'content/guides/vip.md' => 'VIP система',
    'content/guides/raspisanie.md' => 'Календарь и расписание',
    'content/guides/oshibki.md' => 'Частые ошибки',
    'content/guides/cheklista.md' => 'Чек-лист достижений',
    'content/guides/glossariy.md' => 'Глоссарий терминов',
    'content/guides/tablicy.md' => 'Таблицы сравнения',
    'content/guides/faq.md' => 'FAQ'
];

// Директория с файлами сайта (относительно admin-api.php)
define('SITE_ROOT', __DIR__ . '/');

// Включить логирование изменений
define('ENABLE_LOGGING', true);

// Файл логов
define('LOG_FILE', __DIR__ . '/admin-changes.log');
