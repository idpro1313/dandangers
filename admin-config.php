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
    'lootbar-instruction.html',
    'novoe.html',
    'guide-vvedenie.html',
    'guide-razvitie.html',
    'guide-zdaniya.html',
    'guide-laboratoriya.html',
    'guide-geroi.html',
    'guide-snariazhenie.html',
    'guide-resursy.html',
    'guide-sobytiya.html',
    'guide-soyuz.html',
    'guide-mehaniki.html',
    'guide-start.html',
    'guide-vip.html',
    'guide-raspisanie.html',
    'guide-oshibki.html',
    'guide-cheklista.html',
    'guide-glossariy.html',
    'guide-tablicy.html',
    'guide-faq.html'
];

// Директория с файлами сайта (относительно admin-api.php)
define('SITE_ROOT', __DIR__ . '/');

// Включить логирование изменений
define('ENABLE_LOGGING', true);

// Файл логов
define('LOG_FILE', __DIR__ . '/admin-changes.log');
