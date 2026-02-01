<?php
/**
 * Единый роутер сайта dandangers.ru
 * Обрабатывает ВСЕ страницы: главная, гайды, lootbar, расписание
 */

// DEBUG отключен

// Подключаем библиотеки и конфиги (в глобальной области!)
require_once __DIR__ . '/lib/Parsedown.php';
require_once __DIR__ . '/guides-config.php';
require_once __DIR__ . '/lootbar-config.php';
require_once __DIR__ . '/schedule-config.php';

// Получаем URI без query string
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Роутинг на основе URL
if ($uri === '/' || $uri === '') {
    // Главная страница
    renderHome();
} elseif (preg_match('#^/guide-([a-z0-9-]+)\.html$#', $uri, $matches)) {
    // Гайды: /guide-{slug}.html
    renderGuide($matches[1]);
} elseif (preg_match('#^/lootbar-([a-z0-9-]+)\.html$#', $uri, $matches)) {
    // LootBar: /lootbar-{slug}.html
    renderLootbar($matches[1]);
} elseif ($uri === '/daily.html') {
    // Расписание
    renderSchedule('daily');
} else {
    // 404
    show404();
}

// ============================================================
// ФУНКЦИЯ: Главная страница
// ============================================================
function renderHome() {
?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <link rel="preconnect" href="https://mc.yandex.ru">
  <link rel="dns-prefetch" href="https://mc.yandex.ru">
  
  <title>Tiles Survive Wiki — Гайды, Герои, Расписание | [Dan]Dangers</title>
  <meta name="description" content="Tiles Survive Wiki — полный справочник по игре от союза [Dan]Dangers. 18 гайдов по героям, прокачке, ресурсам. Расписание ивентов, VIP система, F2P стратегии. Штат 174.">
  <meta name="keywords" content="tiles survive, tiles survive гайд, tiles survive wiki, tiles survive герои, tiles survive прокачка, tiles survive расписание, dandangers, тайлс сурвайв, tiles survive game, tiles survive советы, tiles survive донат, tiles survive f2p, tiles survive vip">
  <meta name="author" content="Союз [Dan]Dangers">
  <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1">
  <meta name="googlebot" content="index, follow">
  <meta name="yandex" content="index, follow">
  <link rel="canonical" href="https://dandangers.ru/">
  <link rel="alternate" hreflang="ru" href="https://dandangers.ru/">
  <link rel="alternate" hreflang="x-default" href="https://dandangers.ru/">
  
  <meta property="og:type" content="website">
  <meta property="og:url" content="https://dandangers.ru/">
  <meta property="og:title" content="Tiles Survive Wiki — Полный справочник по игре">
  <meta property="og:description" content="18 подробных гайдов по Tiles Survive: герои, здания, VIP, события. Расписание ивентов и F2P стратегии.">
  <meta property="og:image" content="https://dandangers.ru/dangers.jpg">
  <meta property="og:image:width" content="512">
  <meta property="og:image:height" content="512">
  <meta property="og:locale" content="ru_RU">
  <meta property="og:site_name" content="Tiles Survive Wiki">
  
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="Tiles Survive Wiki | [Dan]Dangers">
  <meta name="twitter:description" content="Полный справочник по Tiles Survive. 18 гайдов, расписание ивентов, прокачка героев.">
  <meta name="twitter:image" content="https://dandangers.ru/dangers.jpg">
  
  <script type="application/ld+json">
  {"@context":"https://schema.org","@type":"WebSite","name":"Tiles Survive Wiki","alternateName":["DanDangers Wiki","Тайлс Сурвайв Вики"],"url":"https://dandangers.ru/","description":"Полный справочник по игре Tiles Survive с 18 гайдами","inLanguage":"ru-RU","publisher":{"@type":"Organization","name":"Союз [Dan]Dangers","url":"https://dandangers.ru/","logo":{"@type":"ImageObject","url":"https://dandangers.ru/dangers.jpg","width":512,"height":512}}}
  </script>
  <script type="application/ld+json">
  {"@context":"https://schema.org","@type":"Organization","name":"Союз [Dan]Dangers","alternateName":"DanDangers","url":"https://dandangers.ru/","logo":"https://dandangers.ru/dangers.jpg","description":"Активное сообщество игроков Tiles Survive на штате 174"}
  </script>
  <script type="application/ld+json">
  {"@context":"https://schema.org","@type":"CollectionPage","name":"Библиотека гайдов Tiles Survive","description":"Полная коллекция из 18 подробных гайдов","url":"https://dandangers.ru/","mainEntity":{"@type":"ItemList","numberOfItems":18,"itemListElement":[{"@type":"ListItem","position":1,"name":"Введение","url":"https://dandangers.ru/guide-vvedenie.html"},{"@type":"ListItem","position":2,"name":"Развитие","url":"https://dandangers.ru/guide-razvitie.html"},{"@type":"ListItem","position":3,"name":"Здания","url":"https://dandangers.ru/guide-zdaniya.html"},{"@type":"ListItem","position":4,"name":"Лаборатория","url":"https://dandangers.ru/guide-laboratoriya.html"},{"@type":"ListItem","position":5,"name":"Герои","url":"https://dandangers.ru/guide-geroi.html"},{"@type":"ListItem","position":6,"name":"Снаряжение","url":"https://dandangers.ru/guide-snariazhenie.html"},{"@type":"ListItem","position":7,"name":"Ресурсы","url":"https://dandangers.ru/guide-resursy.html"},{"@type":"ListItem","position":8,"name":"События","url":"https://dandangers.ru/guide-sobytiya.html"},{"@type":"ListItem","position":9,"name":"Союз","url":"https://dandangers.ru/guide-soyuz.html"},{"@type":"ListItem","position":10,"name":"Механики","url":"https://dandangers.ru/guide-mehaniki.html"},{"@type":"ListItem","position":11,"name":"Быстрый старт","url":"https://dandangers.ru/guide-start.html"},{"@type":"ListItem","position":12,"name":"VIP","url":"https://dandangers.ru/guide-vip.html"},{"@type":"ListItem","position":13,"name":"Расписание","url":"https://dandangers.ru/guide-raspisanie.html"},{"@type":"ListItem","position":14,"name":"Ошибки","url":"https://dandangers.ru/guide-oshibki.html"},{"@type":"ListItem","position":15,"name":"Чек-лист","url":"https://dandangers.ru/guide-cheklista.html"},{"@type":"ListItem","position":16,"name":"Глоссарий","url":"https://dandangers.ru/guide-glossariy.html"},{"@type":"ListItem","position":17,"name":"Таблицы","url":"https://dandangers.ru/guide-tablicy.html"},{"@type":"ListItem","position":18,"name":"FAQ","url":"https://dandangers.ru/guide-faq.html"}]}}
  </script>
  
  <link rel="icon" type="image/jpeg" href="/dangers.jpg">
  <link rel="apple-touch-icon" href="/dangers.jpg">
  <link rel="stylesheet" href="/modern-styles.css">
  
  <script type="text/javascript">
      (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};m[i].l=1*new Date();for(var j=0;j<document.scripts.length;j++){if(document.scripts[j].src===r){return;}}k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})(window,document,'script','https://mc.yandex.ru/metrika/tag.js?id=105280577','ym');ym(105280577,'init',{ssr:true,webvisor:true,clickmap:true,ecommerce:"dataLayer",referrer:document.referrer,url:location.href,accurateTrackBounce:true,trackLinks:true});
  </script>
  <noscript><div><img src="https://mc.yandex.ru/watch/105280577" style="position:absolute;left:-9999px;" alt=""/></div></noscript>
</head>
<body>

<?php renderNav(['home' => true]); ?>

<main>

<a href="/lootbar-discounts.html" class="promo-banner">
  <div class="promo-banner-content">
    <div class="promo-banner-icon">💰</div>
    <div class="promo-banner-text">
      <div class="promo-banner-title">Скидки до 32% на игровую валюту!</div>
      <div class="promo-banner-desc">Купоны 6% и 10% для новых пользователей LootBar</div>
    </div>
  </div>
  <div class="promo-banner-badge">Подробнее</div>
</a>

<div class="content-wrapper">

<div class="hero-banner">
  <img src="/dangers.jpg" alt="Союз [Dan]Dangers" class="hero-logo">
  <div class="hero-text">
    <h1>Tiles Survive Wiki</h1>
    <p class="hero-subtitle">Справочник Союза [Dan]Dangers | Штат 174</p>
  </div>
</div>

<p>Добро пожаловать в справочник <strong>Союза [Dan]Dangers</strong>, штат 174. Здесь собраны гайды, расписания и полезная информация для игроков Tiles Survive.</p>

<h2>📚 Библиотека гайдов</h2>
<p style="margin-bottom: 1.5rem;">18 подробных руководств по всем аспектам игры — от основ для новичков до продвинутых стратегий.</p>

<h3 style="color: var(--primary); margin: 1.5rem 0 1rem;">📖 Основные гайды</h3>
<div class="nav-cards">
  <?php
  $mainGuides = [
    ['vvedenie', '01', 'Введение и стратегии', 'Основы игры, боевая мощь, F2P vs донат'],
    ['razvitie', '02', 'Развитие без доната', 'VIP система, стратегия 60 дней, кристаллы'],
    ['zdaniya', '03', 'Здания и приоритеты', 'Электростанция, иерархия зданий, план'],
    ['laboratoriya', '04', 'Лаборатория и науки', 'Фазы прокачки, таблица, приоритеты'],
    ['geroi', '05', 'Герои и боевые группы', 'Типы героев, основная группа, звёзды'],
    ['snariazhenie', '06', 'Снаряжение и артефакты', 'Редкость, трёхцвет, рекомендации'],
    ['resursy', '07', 'Ресурсы и фарм', 'Типы ресурсов, плитки, оптимизация'],
    ['sobytiya', '08', 'События и оптимизация', 'Турбо-черепашка, Дуэль, стратегия'],
    ['soyuz', '09', 'Союз и карта', 'Типы союзов, навыки, территории'],
    ['mehaniki', '10', 'Специальные механики', 'Титан, радар, переработка, боссы'],
  ];
  foreach ($mainGuides as $g): ?>
  <a href="/guide-<?= $g[0] ?>.html" class="nav-card">
    <span class="nav-card-icon"><?= $g[1] ?></span>
    <div class="nav-card-content">
      <div class="nav-card-title"><?= $g[2] ?></div>
      <div class="nav-card-desc"><?= $g[3] ?></div>
    </div>
  </a>
  <?php endforeach; ?>
</div>

<h3 style="color: var(--primary); margin: 2rem 0 1rem;">⭐ Специальные гайды</h3>
<div class="nav-cards">
  <?php
  $specialGuides = [
    ['start', '11', 'Быстрый старт', 'Первые 24 часа, неделя 1, месяц 1'],
    ['vip', '12', 'VIP система', 'Почему VIP #1, математика, график'],
    ['raspisanie', '13', 'Календарь и расписание', 'Глобальное расписание, месячный календарь'],
    ['oshibki', '14', 'Частые ошибки', '14 критических ошибок новичков'],
    ['cheklista', '15', 'Чек-лист достижений', 'Контрольные точки по дням'],
    ['glossariy', '16', 'Глоссарий и термины', 'Сокращения, механики, словарь'],
    ['tablicy', '17', 'Таблицы сравнения', 'F2P vs донат, критические точки'],
    ['faq', '18', 'FAQ', '30+ вопросов с ответами'],
  ];
  foreach ($specialGuides as $g): ?>
  <a href="/guide-<?= $g[0] ?>.html" class="nav-card">
    <span class="nav-card-icon"><?= $g[1] ?></span>
    <div class="nav-card-content">
      <div class="nav-card-title"><?= $g[2] ?></div>
      <div class="nav-card-desc"><?= $g[3] ?></div>
    </div>
  </a>
  <?php endforeach; ?>
</div>

<h2>🏰 О союзе</h2>
<div class="nav-cards">
  <a href="/guide-soyuz-dangers.html" class="nav-card">
    <span class="nav-card-icon">🏰</span>
    <div class="nav-card-content">
      <div class="nav-card-title">О Союзе</div>
      <div class="nav-card-desc">Информация о [Dan]Dangers, штат 174</div>
    </div>
  </a>
  <a href="/daily.html" class="nav-card">
    <span class="nav-card-icon">📅</span>
    <div class="nav-card-content">
      <div class="nav-card-title">Расписание</div>
      <div class="nav-card-desc">Трата ресурсов по дням и событиям</div>
    </div>
  </a>
</div>

<h2>📜 Старые гайды</h2>
<details class="old-guides-section">
  <summary>Показать старые гайды (6 штук)</summary>
  <div class="nav-cards" style="margin-top: 1rem;">
    <?php
    $oldGuides = [
      ['17sovetov', '💡', '17 советов', 'Полезные советы от опытных игроков'],
      ['bigguide', '📖', 'Гайд для новичков', 'Полный гайд: основы и стратегии'],
      ['fullguide', '📚', 'Полный гайд', 'Текстовый гайд по всем аспектам игры'],
      ['heroes-old', '⚔️', 'Герои', 'Навыки, снаряжение и прокачка'],
      ['lab-old', '🔬', 'Лаборатория', 'Приоритеты прокачки наук'],
      ['res-old', '💎', 'Ресурсы', 'Сбор и производство ресурсов'],
    ];
    foreach ($oldGuides as $g): ?>
    <a href="/guide-<?= $g[0] ?>.html" class="nav-card">
      <span class="nav-card-icon"><?= $g[1] ?></span>
      <div class="nav-card-content">
        <div class="nav-card-title"><?= $g[2] ?></div>
        <div class="nav-card-desc"><?= $g[3] ?></div>
      </div>
    </a>
    <?php endforeach; ?>
  </div>
</details>

<h2>📊 Таблицы и данные</h2>
<div class="nav-cards">
  <a href="/daily.html" class="nav-card">
    <span class="nav-card-icon">📅</span>
    <div class="nav-card-content">
      <div class="nav-card-title">Расписание</div>
      <div class="nav-card-desc">Расписание событий по дням недели</div>
    </div>
  </a>
  <a href="https://docs.google.com/spreadsheets/d/14YBxtEsrZhuhFp_ldXRtWBzs50IiMq4lZTouZr3tYeA/edit?gid=0#gid=0" class="nav-card" target="_blank">
    <span class="nav-card-icon">📊</span>
    <div class="nav-card-content">
      <div class="nav-card-title">Статы героев</div>
      <div class="nav-card-desc">Таблица статов героев и отрядов</div>
    </div>
  </a>
  <a href="https://docs.google.com/spreadsheets/d/1eKqvXaFjlWc4EWBFxg1dkA-yKhqyCIuMm2kIdqbET98/edit?gid=116685073#gid=116685073" class="nav-card" target="_blank">
    <span class="nav-card-icon">🌊</span>
    <div class="nav-card-content">
      <div class="nav-card-title">Морской сезон</div>
      <div class="nav-card-desc">Таблица по Морскому сезону</div>
    </div>
  </a>
</div>

<h2>Сообщество</h2>
<div class="nav-cards">
  <a href="https://rutube.ru/channel/72144398/" class="nav-card" target="_blank">
    <span class="nav-card-icon">🎬</span>
    <div class="nav-card-content">
      <div class="nav-card-title">RuTube</div>
      <div class="nav-card-desc">Видеогайды на RuTube</div>
    </div>
  </a>
  <a href="https://vkvideo.ru/@club233882791" class="nav-card" target="_blank">
    <span class="nav-card-icon">📹</span>
    <div class="nav-card-content">
      <div class="nav-card-title">VK Video</div>
      <div class="nav-card-desc">Видеогайды на VK Video</div>
    </div>
  </a>
  <a href="https://t.me/tilessurviveru" class="nav-card" target="_blank">
    <span class="nav-card-icon">💬</span>
    <div class="nav-card-content">
      <div class="nav-card-title">Tiles Survive RU</div>
      <div class="nav-card-desc">Телеграм-сообщество</div>
    </div>
  </a>
  <a href="https://t.me/TLSSURVIVE" class="nav-card" target="_blank">
    <span class="nav-card-icon">📢</span>
    <div class="nav-card-content">
      <div class="nav-card-title">TLS Survive</div>
      <div class="nav-card-desc">Телеграм-канал сообщества</div>
    </div>
  </a>
</div>

</div>
</main>

<?php renderFooter(); ?>
<?php renderScripts(); ?>

</body>
</html>
<?php
}

// ============================================================
// ФУНКЦИЯ: 404 страница
// ============================================================
function show404() {
    http_response_code(404);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>404 — Страница не найдена | Tiles Survive Wiki</title>
    <link rel="icon" type="image/jpeg" href="/dangers.jpg">
    <link rel="stylesheet" href="/modern-styles.css">
</head>
<body>
<nav class="site-nav">
    <div class="nav-container">
        <a href="/" class="nav-logo"><img src="/dangers.jpg" alt="DanDangers" class="nav-logo-img">TS Wiki</a>
    </div>
</nav>
<main>
    <div class="content-wrapper" style="text-align: center; padding: 4rem 2rem;">
        <h1>404 — Страница не найдена</h1>
        <p style="margin: 2rem 0;">Запрошенная страница не существует или была перемещена.</p>
        <p><a href="/" style="color: var(--primary);">← Вернуться на главную</a></p>
        <p><a href="/guide-biblioteka.html" style="color: var(--primary);">📚 Библиотека гайдов</a></p>
    </div>
</main>
</body>
</html>
<?php
    exit;
}

// ============================================================
// ФУНКЦИЯ: Рендеринг гайдов
// ============================================================
function renderGuide($page) {
    if (!$page || !guideExists($page)) {
        show404();
    }
    
    $config = getGuideConfig($page);
    $navLinks = getGuideNavLinks($page);
    
    $mdFile = __DIR__ . '/content/guides/' . $page . '.md';
    if (!file_exists($mdFile)) {
        show404();
    }
    
    $mdContent = file_get_contents($mdFile);
    $Parsedown = new Parsedown();
    $Parsedown->setSafeMode(false);
    $htmlContent = $Parsedown->text($mdContent);
    
    $wordCount = str_word_count(strip_tags($htmlContent), 0, 'абвгдеёжзийклмнопрстуфхцчшщъыьэюяАБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯ');
    
    $schemaType = $config['schema_type'] ?? 'Article';
    $canonical = 'https://dandangers.ru/guide-' . $page . '.html';
    
    $articleSection = 'Гайды';
    if (isset($config['is_old'])) $articleSection = 'Старые гайды';
    elseif (isset($config['is_about'])) $articleSection = 'О союзе';
    elseif (isset($config['is_library'])) $articleSection = 'Библиотека';
    
    if ($schemaType === 'FAQPage') {
        $schemaJson = '{"@context":"https://schema.org","@type":"FAQPage","mainEntity":[]}';
    } else {
        $schemaJson = json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => $config['schema_headline'],
            'description' => $config['og_description'],
            'image' => ['@type' => 'ImageObject', 'url' => 'https://dandangers.ru/dangers.jpg', 'width' => 512, 'height' => 512],
            'author' => ['@type' => 'Organization', 'name' => 'Союз [Dan]Dangers', 'url' => 'https://dandangers.ru/'],
            'publisher' => ['@type' => 'Organization', 'name' => 'Tiles Survive Wiki', 'url' => 'https://dandangers.ru/', 'logo' => ['@type' => 'ImageObject', 'url' => 'https://dandangers.ru/dangers.jpg']],
            'datePublished' => '2026-01-29',
            'dateModified' => date('Y-m-d'),
            'mainEntityOfPage' => ['@type' => 'WebPage', '@id' => $canonical],
            'inLanguage' => 'ru-RU',
            'articleSection' => $articleSection,
            'wordCount' => $wordCount
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
    
    $breadcrumbJson = json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => array_merge(
            [['@type' => 'ListItem', 'position' => 1, 'name' => 'Главная', 'item' => 'https://dandangers.ru/'],
             ['@type' => 'ListItem', 'position' => 2, 'name' => 'Библиотека', 'item' => 'https://dandangers.ru/guide-biblioteka.html']],
            $page !== 'biblioteka' ? [['@type' => 'ListItem', 'position' => 3, 'name' => $config['nav_title'], 'item' => $canonical]] : []
        )
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    
    $navActive = [
        'about' => ($page === 'soyuz-dangers'),
        'library' => (!isset($config['is_old']) && !isset($config['is_about']) && !isset($config['is_library'])),
        'old' => isset($config['is_old']) && $config['is_old'],
        'schedule' => false,
        'lootbar' => false
    ];
    
    renderPage([
        'title' => esc($config['title']),
        'description' => esc($config['description']),
        'keywords' => esc($config['keywords']),
        'ogTitle' => esc($config['og_title']),
        'ogDescription' => esc($config['og_description']),
        'canonical' => $canonical,
        'schemaJson' => $schemaJson,
        'breadcrumbJson' => $breadcrumbJson,
        'h1' => esc($config['h1']),
        'content' => $htmlContent,
        'navActive' => $navActive,
        'page' => $page,
        'navLinks' => $navLinks,
        'type' => 'guide',
        'config' => $config
    ]);
}

// ============================================================
// ФУНКЦИЯ: Рендеринг LootBar
// ============================================================
function renderLootbar($page) {
    require_once __DIR__ . '/lootbar-config.php';
    
    $page = $page ?: 'discounts';
    
    if (!lootbarExists($page)) {
        show404();
    }
    
    $config = getLootbarConfig($page);
    $navLinks = getLootbarNavLinks($page);
    
    $mdFile = __DIR__ . '/content/lootbar/' . $page . '.md';
    if (!file_exists($mdFile)) {
        show404();
    }
    
    $mdContent = file_get_contents($mdFile);
    $Parsedown = new Parsedown();
    $Parsedown->setSafeMode(false);
    $htmlContent = $Parsedown->text($mdContent);
    $htmlContent = preg_replace('/src="(lootbar-[^"]+\.png)"/', 'src="/content/lootbar/$1"', $htmlContent);
    
    $canonical = 'https://dandangers.ru/lootbar-' . $page . '.html';
    $schemaType = $config['schema_type'] ?? 'Article';
    
    if ($schemaType === 'HowTo') {
        $schemaJson = json_encode([
            '@context' => 'https://schema.org', '@type' => 'HowTo',
            'name' => $config['schema_headline'], 'description' => $config['description'],
            'image' => 'https://dandangers.ru/dangers.jpg', 'totalTime' => 'PT5M',
            'step' => [
                ['@type' => 'HowToStep', 'position' => 1, 'name' => 'Выберите Self-TopUp', 'text' => 'После покупки выберите Self-TopUp.'],
                ['@type' => 'HowToStep', 'position' => 2, 'name' => 'Войдите в аккаунт', 'text' => 'Войдите через FunPlus ID.'],
                ['@type' => 'HowToStep', 'position' => 3, 'name' => 'Подтвердите', 'text' => 'Подтвердите и получите пакет.'],
                ['@type' => 'HowToStep', 'position' => 4, 'name' => 'Дождитесь', 'text' => 'Не закрывайте интерфейс.'],
                ['@type' => 'HowToStep', 'position' => 5, 'name' => 'Проверьте', 'text' => 'Войдите в игру и проверьте.']
            ]
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    } else {
        $schemaJson = json_encode([
            '@context' => 'https://schema.org', '@type' => 'Article',
            'headline' => $config['schema_headline'], 'description' => $config['og_description'],
            'image' => 'https://dandangers.ru/dangers.jpg',
            'author' => ['@type' => 'Organization', 'name' => 'Союз [Dan]Dangers'],
            'publisher' => ['@type' => 'Organization', 'name' => 'Tiles Survive Wiki', 'logo' => ['@type' => 'ImageObject', 'url' => 'https://dandangers.ru/dangers.jpg']],
            'datePublished' => '2026-01-29', 'dateModified' => date('Y-m-d'),
            'mainEntityOfPage' => ['@type' => 'WebPage', '@id' => $canonical],
            'inLanguage' => 'ru-RU', 'articleSection' => 'Донат и скидки'
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
    
    $breadcrumbJson = json_encode([
        '@context' => 'https://schema.org', '@type' => 'BreadcrumbList',
        'itemListElement' => array_merge(
            [['@type' => 'ListItem', 'position' => 1, 'name' => 'Главная', 'item' => 'https://dandangers.ru/'],
             ['@type' => 'ListItem', 'position' => 2, 'name' => 'Скидки', 'item' => 'https://dandangers.ru/lootbar-discounts.html']],
            $page !== 'discounts' ? [['@type' => 'ListItem', 'position' => 3, 'name' => $config['nav_title'], 'item' => $canonical]] : []
        )
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    
    renderPage([
        'title' => esc($config['title']),
        'description' => esc($config['description']),
        'keywords' => esc($config['keywords']),
        'ogTitle' => esc($config['og_title']),
        'ogDescription' => esc($config['og_description']),
        'canonical' => $canonical,
        'schemaJson' => $schemaJson,
        'breadcrumbJson' => $breadcrumbJson,
        'h1' => esc($config['h1']),
        'content' => $htmlContent,
        'navActive' => ['about' => false, 'library' => false, 'old' => false, 'schedule' => false, 'lootbar' => true],
        'page' => $page,
        'navLinks' => $navLinks,
        'type' => 'lootbar',
        'config' => $config
    ]);
}

// ============================================================
// ФУНКЦИЯ: Рендеринг расписания
// ============================================================
function renderSchedule($page) {
    // Конфиг уже загружен в начале файла
    
    $page = $page ?: 'daily';
    
    if (!scheduleExists($page)) {
        show404();
    }
    
    $config = getScheduleConfig($page);
    
    $mdFile = __DIR__ . '/content/schedule/' . $page . '.md';
    if (!file_exists($mdFile)) {
        show404();
    }
    
    $mdContent = file_get_contents($mdFile);
    $Parsedown = new Parsedown();
    $Parsedown->setSafeMode(false);
    $htmlContent = $Parsedown->text($mdContent);
    
    $canonical = 'https://dandangers.ru/daily.html';
    
    $schemaJson = json_encode([
        '@context' => 'https://schema.org', '@type' => 'Article',
        'headline' => $config['schema_headline'], 'description' => $config['og_description'],
        'image' => 'https://dandangers.ru/dangers.jpg',
        'author' => ['@type' => 'Organization', 'name' => 'Союз [Dan]Dangers'],
        'publisher' => ['@type' => 'Organization', 'name' => 'Tiles Survive Wiki', 'logo' => ['@type' => 'ImageObject', 'url' => 'https://dandangers.ru/dangers.jpg']],
        'datePublished' => '2026-01-15', 'dateModified' => date('Y-m-d'),
        'mainEntityOfPage' => ['@type' => 'WebPage', '@id' => $canonical],
        'inLanguage' => 'ru-RU', 'articleSection' => 'Расписание'
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    
    $breadcrumbJson = json_encode([
        '@context' => 'https://schema.org', '@type' => 'BreadcrumbList',
        'itemListElement' => [
            ['@type' => 'ListItem', 'position' => 1, 'name' => 'Главная', 'item' => 'https://dandangers.ru/'],
            ['@type' => 'ListItem', 'position' => 2, 'name' => 'Расписание', 'item' => $canonical]
        ]
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    
    renderPage([
        'title' => esc($config['title']),
        'description' => esc($config['description']),
        'keywords' => esc($config['keywords']),
        'ogTitle' => esc($config['og_title']),
        'ogDescription' => esc($config['og_description']),
        'canonical' => $canonical,
        'schemaJson' => $schemaJson,
        'breadcrumbJson' => $breadcrumbJson,
        'h1' => esc($config['h1']),
        'content' => $htmlContent,
        'navActive' => ['about' => false, 'library' => false, 'old' => false, 'schedule' => true, 'lootbar' => false],
        'page' => $page,
        'navLinks' => null,
        'type' => 'schedule',
        'config' => $config
    ]);
}

// ============================================================
// ВСПОМОГАТЕЛЬНЫЕ ФУНКЦИИ
// ============================================================
function esc($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

function renderNav($navActive = []) {
    $navActive = array_merge(['home' => false, 'about' => false, 'library' => false, 'old' => false, 'schedule' => false, 'lootbar' => false], $navActive);
    
    // Определяем текущую страницу из URL
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $page = '';
    $type = '';
    
    if (preg_match('#^/guide-([a-z0-9-]+)\.html$#', $uri, $m)) {
        $type = 'guide';
        $page = $m[1];
    } elseif (preg_match('#^/lootbar-([a-z0-9-]+)\.html$#', $uri, $m)) {
        $type = 'lootbar';
        $page = $m[1];
    } elseif ($uri === '/daily.html') {
        $type = 'schedule';
        $page = 'daily';
    }
?>
<nav class="site-nav">
  <div class="nav-container">
    <a href="/" class="nav-logo"><img src="/dangers.jpg" alt="Tiles Survive Wiki" class="nav-logo-img">TS Wiki</a>
    <button class="nav-toggle" aria-label="Меню">☰</button>
    <ul class="nav-menu">
      <li><a href="/"<?= $navActive['home'] ? ' class="active"' : '' ?>>Главная</a></li>
      <li><a href="/guide-soyuz-dangers.html"<?= $navActive['about'] ? ' class="active"' : '' ?>>О союзе</a></li>
      <li><a href="/daily.html"<?= $navActive['schedule'] ? ' class="active"' : '' ?>>Расписание</a></li>
      <li class="nav-dropdown">
        <a href="/guide-biblioteka.html" class="dropdown-toggle<?= $navActive['library'] ? ' active' : '' ?>">Библиотека ▾</a>
        <ul class="dropdown-menu">
          <li><a href="/guide-vvedenie.html"<?= ($type==='guide' && $page==='vvedenie') ? ' class="active"' : '' ?>>Введение</a></li>
          <li><a href="/guide-razvitie.html"<?= ($type==='guide' && $page==='razvitie') ? ' class="active"' : '' ?>>Развитие F2P</a></li>
          <li><a href="/guide-zdaniya.html"<?= ($type==='guide' && $page==='zdaniya') ? ' class="active"' : '' ?>>Здания</a></li>
          <li><a href="/guide-geroi.html"<?= ($type==='guide' && $page==='geroi') ? ' class="active"' : '' ?>>Герои</a></li>
          <li><a href="/guide-laboratoriya.html"<?= ($type==='guide' && $page==='laboratoriya') ? ' class="active"' : '' ?>>Лаборатория</a></li>
          <li><a href="/guide-sobytiya.html"<?= ($type==='guide' && $page==='sobytiya') ? ' class="active"' : '' ?>>События</a></li>
          <li><a href="/guide-start.html"<?= ($type==='guide' && $page==='start') ? ' class="active"' : '' ?>>Быстрый старт</a></li>
          <li><a href="/guide-vip.html"<?= ($type==='guide' && $page==='vip') ? ' class="active"' : '' ?>>VIP система</a></li>
          <li><a href="/guide-faq.html"<?= ($type==='guide' && $page==='faq') ? ' class="active"' : '' ?>>FAQ</a></li>
          <li><a href="/guide-biblioteka.html"><strong>Все 18 гайдов →</strong></a></li>
        </ul>
      </li>
      <li class="nav-dropdown">
        <a href="#" class="dropdown-toggle<?= $navActive['old'] ? ' active' : '' ?>">Старые ▾</a>
        <ul class="dropdown-menu">
          <li><a href="/guide-17sovetov.html"<?= ($type==='guide' && $page==='17sovetov') ? ' class="active"' : '' ?>>17 советов</a></li>
          <li><a href="/guide-bigguide.html"<?= ($type==='guide' && $page==='bigguide') ? ' class="active"' : '' ?>>Для новичков</a></li>
          <li><a href="/guide-fullguide.html"<?= ($type==='guide' && $page==='fullguide') ? ' class="active"' : '' ?>>Полный гайд</a></li>
          <li><a href="/guide-heroes-old.html"<?= ($type==='guide' && $page==='heroes-old') ? ' class="active"' : '' ?>>Герои</a></li>
          <li><a href="/guide-lab-old.html"<?= ($type==='guide' && $page==='lab-old') ? ' class="active"' : '' ?>>Лаборатория</a></li>
          <li><a href="/guide-res-old.html"<?= ($type==='guide' && $page==='res-old') ? ' class="active"' : '' ?>>Ресурсы</a></li>
        </ul>
      </li>
      <li class="nav-dropdown">
        <a href="/lootbar-discounts.html" class="dropdown-toggle<?= $navActive['lootbar'] ? ' active' : '' ?>">Скидки ▾</a>
        <ul class="dropdown-menu">
          <li><a href="/lootbar-discounts.html"<?= ($type==='lootbar' && $page==='discounts') ? ' class="active"' : '' ?>>Цены и купоны</a></li>
          <li><a href="/lootbar-instruction.html"<?= ($type==='lootbar' && $page==='instruction') ? ' class="active"' : '' ?>>Инструкция</a></li>
        </ul>
      </li>
    </ul>
  </div>
</nav>
<?php
}

function renderFooter() {
?>
<footer class="site-footer">
  <div class="footer-container">
    <p>© <?= date('Y') ?> Союз [Dan]Dangers | Штат 174 | <a href="https://dandangers.ru/">Tiles Survive Wiki</a></p>
  </div>
</footer>
<?php
}

function renderScripts() {
?>
<script>
document.querySelector('.nav-toggle').addEventListener('click', function() {
  document.querySelector('.nav-menu').classList.toggle('active');
});
document.querySelectorAll('.dropdown-toggle').forEach(function(toggle) {
  toggle.addEventListener('click', function(e) {
    if (window.innerWidth <= 768) {
      e.preventDefault();
      this.parentElement.classList.toggle('active');
    }
  });
});
</script>
<?php
}

function renderPage($data) {
    extract($data);
    $breadcrumbJson = $breadcrumbJson ?? '';
?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="preconnect" href="https://mc.yandex.ru">
  <link rel="dns-prefetch" href="https://mc.yandex.ru">
  
  <title><?= $title ?></title>
  <meta name="description" content="<?= $description ?>">
  <meta name="keywords" content="<?= $keywords ?>">
  <meta name="author" content="Союз [Dan]Dangers">
  <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1">
  <link rel="canonical" href="<?= $canonical ?>">
  <link rel="alternate" hreflang="ru" href="<?= $canonical ?>">
  
  <meta property="og:type" content="article">
  <meta property="og:url" content="<?= $canonical ?>">
  <meta property="og:title" content="<?= $ogTitle ?>">
  <meta property="og:description" content="<?= $ogDescription ?>">
  <meta property="og:image" content="https://dandangers.ru/dangers.jpg">
  <meta property="og:locale" content="ru_RU">
  <meta property="og:site_name" content="Tiles Survive Wiki">
  
  <meta name="twitter:card" content="summary">
  <meta name="twitter:title" content="<?= $ogTitle ?>">
  <meta name="twitter:description" content="<?= $ogDescription ?>">
  <meta name="twitter:image" content="https://dandangers.ru/dangers.jpg">
  
  <script type="application/ld+json"><?= $schemaJson ?></script>
<?php if ($breadcrumbJson): ?>
  <script type="application/ld+json"><?= $breadcrumbJson ?></script>
<?php endif; ?>
  
  <link rel="icon" type="image/jpeg" href="/dangers.jpg">
  <link rel="stylesheet" href="/modern-styles.css">
  
<?php if ($type === 'lootbar'): ?>
  <style>
    .content-wrapper table{border-collapse:separate;border-spacing:0;background:var(--bg-elevated);border-radius:var(--radius-md);overflow:hidden;border:1px solid var(--border)}
    .content-wrapper th{background:linear-gradient(135deg,#ff9800 0%,#e65100 100%);color:white;font-weight:600;padding:var(--space-3) var(--space-4)}
    .content-wrapper td{padding:var(--space-3) var(--space-4);border-bottom:1px solid var(--border-light)}
    .content-wrapper tr:last-child td{border-bottom:none}
    .content-wrapper tbody tr:hover{background:var(--bg-card-hover)}
    .content-wrapper img{max-width:100%;height:auto;border-radius:var(--radius-md);border:1px solid var(--border);box-shadow:var(--shadow-md);margin:var(--space-4) 0}
    .lootbar-nav{display:flex;justify-content:space-between;gap:var(--space-4);margin-top:var(--space-8);padding-top:var(--space-6);border-top:1px solid var(--border)}
    .lootbar-nav a{padding:var(--space-3) var(--space-4);background:var(--bg-elevated);border:1px solid var(--border);border-radius:var(--radius-md);text-decoration:none;transition:all var(--transition)}
    .lootbar-nav a:hover{border-color:var(--primary);background:var(--bg-card-hover)}
  </style>
<?php elseif ($type === 'schedule'): ?>
  <style>
    .content-wrapper table{font-size:var(--font-sm);width:100%;border-collapse:separate;border-spacing:0;background:var(--bg-elevated);border-radius:var(--radius-md);overflow:hidden;border:1px solid var(--border);margin:var(--space-4) 0}
    .content-wrapper th{background:linear-gradient(135deg,var(--primary) 0%,var(--primary-dark) 100%);color:white;font-weight:600;padding:var(--space-3) var(--space-4);text-align:left;white-space:nowrap}
    .content-wrapper td{padding:var(--space-3) var(--space-4);border-bottom:1px solid var(--border-light);vertical-align:top}
    .content-wrapper tr:last-child td{border-bottom:none}
    .content-wrapper tbody tr:hover{background:var(--bg-card-hover)}
    @media(max-width:768px){.content-wrapper table{font-size:var(--font-xs);display:block;overflow-x:auto}.content-wrapper th,.content-wrapper td{padding:var(--space-2) var(--space-3);min-width:100px}}
    .content-wrapper h2{margin-top:var(--space-8)}
  </style>
<?php endif; ?>

  <script type="text/javascript">
      (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};m[i].l=1*new Date();for(var j=0;j<document.scripts.length;j++){if(document.scripts[j].src===r){return;}}k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})(window,document,'script','https://mc.yandex.ru/metrika/tag.js?id=105280577','ym');ym(105280577,'init',{ssr:true,webvisor:true,clickmap:true,ecommerce:"dataLayer",referrer:document.referrer,url:location.href,accurateTrackBounce:true,trackLinks:true});
  </script>
  <noscript><div><img src="https://mc.yandex.ru/watch/105280577" style="position:absolute;left:-9999px;" alt=""/></div></noscript>
</head>
<body>

<?php renderNav($navActive); ?>

<main>

<?php if ($type !== 'lootbar'): ?>
<a href="/lootbar-discounts.html" class="promo-banner<?= $type === 'schedule' ? ' wide' : '' ?>">
  <div class="promo-banner-content">
    <div class="promo-banner-icon">💰</div>
    <div class="promo-banner-text">
      <div class="promo-banner-title">Скидки до 32% на игровую валюту!</div>
      <div class="promo-banner-desc">Купоны 6% и 10% для новых пользователей LootBar</div>
    </div>
  </div>
  <div class="promo-banner-badge">Подробнее</div>
</a>
<?php endif; ?>

<article class="content-wrapper<?= $type === 'schedule' ? ' wide' : '' ?>">

<?php if ($type === 'guide'): ?>
<nav class="breadcrumb" aria-label="Хлебные крошки">
  <a href="/">Главная</a> › 
  <a href="/guide-biblioteka.html">Библиотека</a><?php if ($page !== 'biblioteka'): ?> › 
  <span><?= $config['nav_title'] ?></span>
<?php endif; ?>
</nav>
<h1><?= $h1 ?></h1>
<?php elseif ($type === 'lootbar' && $page !== 'discounts'): ?>
<nav class="breadcrumb" aria-label="Хлебные крошки">
  <a href="/">Главная</a> › 
  <a href="/lootbar-discounts.html">Скидки</a> › 
  <span><?= $config['nav_title'] ?></span>
</nav>
<?php elseif ($type === 'schedule'): ?>
<nav class="breadcrumb" aria-label="Хлебные крошки">
  <a href="/">Главная</a> › 
  <span>Расписание</span>
</nav>
<?php endif; ?>

<?= $content ?>

<?php if ($type === 'guide' && $navLinks): ?>
<nav class="guide-nav" aria-label="Навигация по гайдам">
  <?php if ($navLinks['prev']): ?>
    <a href="/guide-<?= $navLinks['prev']['slug'] ?>.html" rel="prev">← <?= esc($navLinks['prev']['title']) ?></a>
  <?php else: ?>
    <a href="/guide-biblioteka.html">← К списку гайдов</a>
  <?php endif; ?>
  <?php if ($navLinks['next']): ?>
    <a href="/guide-<?= $navLinks['next']['slug'] ?>.html" rel="next">Следующий: <?= esc($navLinks['next']['title']) ?> →</a>
  <?php endif; ?>
</nav>
<?php elseif ($type === 'lootbar' && $navLinks): ?>
<nav class="lootbar-nav" aria-label="Навигация">
  <?php if ($navLinks['prev']): ?>
    <a href="/lootbar-<?= $navLinks['prev']['slug'] ?>.html" rel="prev">← <?= esc($navLinks['prev']['title']) ?></a>
  <?php else: ?>
    <span></span>
  <?php endif; ?>
  <?php if ($navLinks['next']): ?>
    <a href="/lootbar-<?= $navLinks['next']['slug'] ?>.html" rel="next"><?= esc($navLinks['next']['title']) ?> →</a>
  <?php endif; ?>
</nav>
<?php endif; ?>

</article>
</main>

<?php renderFooter(); ?>
<?php renderScripts(); ?>

</body>
</html>
<?php
}
