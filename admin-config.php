<?php
/**
 * Конфигурация админ-панели
 * ВАЖНО: Измените пароль перед загрузкой на сервер!
 */

// Пароль для входа в админку (ОБЯЗАТЕЛЬНО ИЗМЕНИТЕ!)
define('ADMIN_PASSWORD', 'Penelopa2');

// Разрешённые для редактирования файлы
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
    'lootbar-instruction.html'
];

// Директория с файлами сайта (относительно admin-api.php)
define('SITE_ROOT', __DIR__ . '/');

// Включить логирование изменений
define('ENABLE_LOGGING', true);

// Файл логов
define('LOG_FILE', __DIR__ . '/admin-changes.log');
