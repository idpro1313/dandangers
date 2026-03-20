<?php
/**
 * Конфигурация страниц расписания — мета-данные для SEO
 * Мета-данные для SEO; страницы отдаёт index.php из MD файлов
 */

$SCHEDULE_PAGES = [
    'daily' => [
        'title' => 'Расписание ивентов Tiles Survive по дням недели | Wiki',
        'h1' => 'Расписание событий',
        'description' => 'Расписание ивентов Tiles Survive по дням недели. Когда тратить ресурсы, ускорения и участвовать в событиях. Оптимальная стратегия.',
        'keywords' => 'tiles survive расписание, tiles survive ивенты, tiles survive события, tiles survive календарь, когда тратить ресурсы tiles survive, tiles survive event schedule, tiles survive дуэль альянсов, tiles survive турбо черепашка, tiles survive игра по крупному',
        'og_title' => 'Расписание ивентов Tiles Survive по дням',
        'og_description' => 'Полное расписание событий Tiles Survive. Когда тратить ресурсы для максимальной выгоды.',
        'schema_headline' => 'Расписание ивентов Tiles Survive — Когда тратить ресурсы',
        'nav_title' => 'Расписание'
    ]
];

/**
 * Получить конфиг страницы по slug
 */
function getScheduleConfig($slug) {
    global $SCHEDULE_PAGES;
    return isset($SCHEDULE_PAGES[$slug]) ? $SCHEDULE_PAGES[$slug] : null;
}

/**
 * Проверить существование страницы
 */
function scheduleExists($slug) {
    global $SCHEDULE_PAGES;
    return isset($SCHEDULE_PAGES[$slug]);
}
