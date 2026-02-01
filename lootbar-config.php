<?php
/**
 * Конфигурация страниц LootBar — мета-данные для SEO
 * Используется lootbar.php для генерации страниц из MD файлов
 */

$LOOTBAR_PAGES = [
    'discounts' => [
        'title' => 'Выгодные цены на Tiles Survive | Скидки до 32% + купоны',
        'h1' => 'Самые выгодные цены на Tiles Survive',
        'description' => 'Самые низкие цены на игровую валюту Tiles Survive на LootBar — экономия до 32%! Получите купоны на дополнительную скидку 6% и 10% для новых пользователей.',
        'keywords' => 'tiles survive донат, tiles survive игровая валюта купить, tiles survive top up, tiles survive recharge, buy gems tiles survive, дешевый донат tiles survive, скидки на донат tiles survive, tiles survive донат без обмана, безопасный донат tiles survive, lootbar tiles survive, tiles survive купоны',
        'og_title' => 'Скидки до 32% на донат Tiles Survive | LootBar купоны',
        'og_description' => 'Самые низкие цены на игровую валюту. Купоны 6% и 10% для новых пользователей.',
        'schema_headline' => 'Скидки до 32% на донат Tiles Survive — Купоны LootBar',
        'nav_title' => 'Скидки',
        'prev' => null,
        'next' => 'instruction'
    ],
    'instruction' => [
        'title' => 'Инструкция по пополнению LootBar | Tiles Survive',
        'h1' => 'Инструкция по пополнению',
        'description' => 'Пошаговая инструкция по пополнению игровой валюты Tiles Survive через LootBar. Как войти через FunPlus ID и получить покупку с помощью Self-TopUp.',
        'keywords' => 'tiles survive пополнение, lootbar инструкция, tiles survive self topup, как пополнить tiles survive, tiles survive funplus id, tiles survive top up guide, tiles survive как купить валюту',
        'og_title' => 'Инструкция по пополнению Tiles Survive через LootBar',
        'og_description' => 'Пошаговая инструкция Self-TopUp с картинками. Как войти и получить покупку.',
        'schema_headline' => 'Как пополнить Tiles Survive через LootBar',
        'schema_type' => 'HowTo',
        'nav_title' => 'Инструкция',
        'prev' => 'discounts',
        'next' => null
    ]
];

$LOOTBAR_ORDER = ['discounts', 'instruction'];

/**
 * Получить конфиг страницы по slug
 */
function getLootbarConfig($slug) {
    global $LOOTBAR_PAGES;
    return isset($LOOTBAR_PAGES[$slug]) ? $LOOTBAR_PAGES[$slug] : null;
}

/**
 * Проверить существование страницы
 */
function lootbarExists($slug) {
    global $LOOTBAR_PAGES;
    return isset($LOOTBAR_PAGES[$slug]);
}

/**
 * Получить навигационные ссылки
 */
function getLootbarNavLinks($slug) {
    global $LOOTBAR_PAGES;
    $config = getLootbarConfig($slug);
    if (!$config) return ['prev' => null, 'next' => null];
    
    $prev = null;
    $next = null;
    
    if ($config['prev'] && isset($LOOTBAR_PAGES[$config['prev']])) {
        $prev = [
            'slug' => $config['prev'],
            'title' => $LOOTBAR_PAGES[$config['prev']]['nav_title']
        ];
    }
    
    if ($config['next'] && isset($LOOTBAR_PAGES[$config['next']])) {
        $next = [
            'slug' => $config['next'],
            'title' => $LOOTBAR_PAGES[$config['next']]['nav_title']
        ];
    }
    
    return ['prev' => $prev, 'next' => $next];
}
