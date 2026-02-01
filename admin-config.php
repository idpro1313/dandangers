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

// Разрешённые для редактирования HTML файлы (главная страница теперь в PHP)
$ALLOWED_FILES = [];

// Разрешённые для редактирования MD файлы (гайды)
$ALLOWED_MD_FILES = [
    // О союзе и библиотека
    'content/guides/soyuz-dangers.md' => 'О союзе [Dan]Dangers',
    'content/guides/biblioteka.md' => 'Библиотека гайдов',
    // Новые гайды
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
    'content/guides/faq.md' => 'FAQ',
    // Старые гайды
    'content/guides/17sovetov.md' => '[Старый] 17 советов',
    'content/guides/bigguide.md' => '[Старый] Для новичков',
    'content/guides/fullguide.md' => '[Старый] Полный гайд',
    'content/guides/heroes-old.md' => '[Старый] Герои',
    'content/guides/lab-old.md' => '[Старый] Лаборатория',
    'content/guides/res-old.md' => '[Старый] Ресурсы',
    // LootBar (донат)
    'content/lootbar/discounts.md' => '[Донат] Скидки LootBar',
    'content/lootbar/instruction.md' => '[Донат] Инструкция пополнения',
    // Расписание
    'content/schedule/daily.md' => 'Расписание событий'
];

// Директория с файлами сайта (относительно admin-api.php)
define('SITE_ROOT', __DIR__ . '/');

// Включить логирование изменений
define('ENABLE_LOGGING', true);

// Файл логов
define('LOG_FILE', __DIR__ . '/admin-changes.log');
