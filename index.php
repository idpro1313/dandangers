<?php
/**
 * Единый роутер сайта dandangers.ru
 * Обрабатывает все динамические страницы: гайды, lootbar, расписание
 * SEO-оптимизированная версия
 */

// Подключаем Parsedown
require_once __DIR__ . '/lib/Parsedown.php';

// Определяем тип страницы из параметров
$route = isset($_GET['route']) ? $_GET['route'] : '';
$page = isset($_GET['page']) ? preg_replace('/[^a-z0-9-]/', '', $_GET['page']) : '';

// Роутинг
switch ($route) {
    case 'guide':
        renderGuide($page);
        break;
    case 'lootbar':
        renderLootbar($page);
        break;
    case 'schedule':
        renderSchedule($page);
        break;
    default:
        http_response_code(404);
        header('Location: /');
        exit;
}

// ============================================================
// ФУНКЦИЯ: Рендеринг гайдов
// ============================================================
function renderGuide($page) {
    require_once __DIR__ . '/guides-config.php';
    
    if (!$page || !guideExists($page)) {
        http_response_code(404);
        header('Location: guide-biblioteka.html');
        exit;
    }
    
    $config = getGuideConfig($page);
    $navLinks = getGuideNavLinks($page);
    
    $mdFile = __DIR__ . '/content/guides/' . $page . '.md';
    if (!file_exists($mdFile)) {
        http_response_code(404);
        header('Location: guide-biblioteka.html');
        exit;
    }
    
    $mdContent = file_get_contents($mdFile);
    $Parsedown = new Parsedown();
    $Parsedown->setSafeMode(false);
    $htmlContent = $Parsedown->text($mdContent);
    
    // Считаем примерное количество слов для SEO
    $wordCount = str_word_count(strip_tags($htmlContent), 0, 'абвгдеёжзийклмнопрстуфхцчшщъыьэюяАБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯ');
    
    // Schema.org
    $schemaType = $config['schema_type'] ?? 'Article';
    $canonical = 'https://dandangers.ru/guide-' . $page . '.html';
    
    // Определяем articleSection
    $articleSection = 'Гайды';
    if (isset($config['is_old']) && $config['is_old']) {
        $articleSection = 'Старые гайды';
    } elseif (isset($config['is_about']) && $config['is_about']) {
        $articleSection = 'О союзе';
    } elseif (isset($config['is_library']) && $config['is_library']) {
        $articleSection = 'Библиотека';
    }
    
    if ($schemaType === 'FAQPage') {
        $schemaJson = json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => []
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    } else {
        $schemaJson = json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => $config['schema_headline'],
            'description' => $config['og_description'],
            'image' => [
                '@type' => 'ImageObject',
                'url' => 'https://dandangers.ru/dangers.jpg',
                'width' => 512,
                'height' => 512
            ],
            'author' => [
                '@type' => 'Organization',
                'name' => 'Союз [Dan]Dangers',
                'url' => 'https://dandangers.ru/'
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => 'Tiles Survive Wiki',
                'url' => 'https://dandangers.ru/',
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => 'https://dandangers.ru/dangers.jpg',
                    'width' => 512,
                    'height' => 512
                ]
            ],
            'datePublished' => '2026-01-29',
            'dateModified' => date('Y-m-d'),
            'mainEntityOfPage' => [
                '@type' => 'WebPage',
                '@id' => $canonical
            ],
            'inLanguage' => 'ru-RU',
            'articleSection' => $articleSection,
            'wordCount' => $wordCount
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
    
    // BreadcrumbList
    $breadcrumbItems = [
        ['@type' => 'ListItem', 'position' => 1, 'name' => 'Главная', 'item' => 'https://dandangers.ru/'],
        ['@type' => 'ListItem', 'position' => 2, 'name' => 'Библиотека', 'item' => 'https://dandangers.ru/guide-biblioteka.html']
    ];
    if ($page !== 'biblioteka') {
        $breadcrumbItems[] = ['@type' => 'ListItem', 'position' => 3, 'name' => $config['nav_title'], 'item' => $canonical];
    }
    $breadcrumbJson = json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => $breadcrumbItems
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
        http_response_code(404);
        header('Location: lootbar-discounts.html');
        exit;
    }
    
    $config = getLootbarConfig($page);
    $navLinks = getLootbarNavLinks($page);
    
    $mdFile = __DIR__ . '/content/lootbar/' . $page . '.md';
    if (!file_exists($mdFile)) {
        http_response_code(404);
        header('Location: lootbar-discounts.html');
        exit;
    }
    
    $mdContent = file_get_contents($mdFile);
    $Parsedown = new Parsedown();
    $Parsedown->setSafeMode(false);
    $htmlContent = $Parsedown->text($mdContent);
    
    // Исправляем пути к изображениям
    $htmlContent = preg_replace(
        '/src="(lootbar-[^"]+\.png)"/',
        'src="content/lootbar/$1"',
        $htmlContent
    );
    
    $canonical = 'https://dandangers.ru/lootbar-' . $page . '.html';
    $schemaType = $config['schema_type'] ?? 'Article';
    
    if ($schemaType === 'HowTo') {
        $schemaJson = json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'HowTo',
            'name' => $config['schema_headline'],
            'description' => $config['description'],
            'image' => 'https://dandangers.ru/dangers.jpg',
            'totalTime' => 'PT5M',
            'step' => [
                ['@type' => 'HowToStep', 'position' => 1, 'name' => 'Выберите метод Self-TopUp', 'text' => 'После покупки подарочного пакета на LootBar выберите метод Self-TopUp.'],
                ['@type' => 'HowToStep', 'position' => 2, 'name' => 'Войдите в игровой аккаунт', 'text' => 'Войдите в интерфейс игры через FunPlus ID.'],
                ['@type' => 'HowToStep', 'position' => 3, 'name' => 'Подтвердите покупку', 'text' => 'Подтвердите информацию и получите подарочный пакет.'],
                ['@type' => 'HowToStep', 'position' => 4, 'name' => 'Дождитесь завершения', 'text' => 'Не закрывайте интерфейс во время погашения.'],
                ['@type' => 'HowToStep', 'position' => 5, 'name' => 'Проверьте результат', 'text' => 'Войдите в игру и проверьте полученные предметы.']
            ]
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    } else {
        $schemaJson = json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => $config['schema_headline'],
            'description' => $config['og_description'],
            'image' => 'https://dandangers.ru/dangers.jpg',
            'author' => ['@type' => 'Organization', 'name' => 'Союз [Dan]Dangers', 'url' => 'https://dandangers.ru/'],
            'publisher' => [
                '@type' => 'Organization',
                'name' => 'Tiles Survive Wiki',
                'logo' => ['@type' => 'ImageObject', 'url' => 'https://dandangers.ru/dangers.jpg']
            ],
            'datePublished' => '2026-01-29',
            'dateModified' => date('Y-m-d'),
            'mainEntityOfPage' => ['@type' => 'WebPage', '@id' => $canonical],
            'inLanguage' => 'ru-RU',
            'articleSection' => 'Донат и скидки'
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
    
    // BreadcrumbList
    $breadcrumbItems = [
        ['@type' => 'ListItem', 'position' => 1, 'name' => 'Главная', 'item' => 'https://dandangers.ru/'],
        ['@type' => 'ListItem', 'position' => 2, 'name' => 'Скидки', 'item' => 'https://dandangers.ru/lootbar-discounts.html']
    ];
    if ($page !== 'discounts') {
        $breadcrumbItems[] = ['@type' => 'ListItem', 'position' => 3, 'name' => $config['nav_title'], 'item' => $canonical];
    }
    $breadcrumbJson = json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => $breadcrumbItems
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    
    $navActive = [
        'about' => false,
        'library' => false,
        'old' => false,
        'schedule' => false,
        'lootbar' => true
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
        'type' => 'lootbar',
        'config' => $config
    ]);
}

// ============================================================
// ФУНКЦИЯ: Рендеринг расписания
// ============================================================
function renderSchedule($page) {
    require_once __DIR__ . '/schedule-config.php';
    
    $page = $page ?: 'daily';
    
    if (!scheduleExists($page)) {
        http_response_code(404);
        header('Location: /');
        exit;
    }
    
    $config = getScheduleConfig($page);
    
    $mdFile = __DIR__ . '/content/schedule/' . $page . '.md';
    if (!file_exists($mdFile)) {
        http_response_code(404);
        header('Location: /');
        exit;
    }
    
    $mdContent = file_get_contents($mdFile);
    $Parsedown = new Parsedown();
    $Parsedown->setSafeMode(false);
    $htmlContent = $Parsedown->text($mdContent);
    
    $canonical = 'https://dandangers.ru/daily.html';
    
    $schemaJson = json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'Article',
        'headline' => $config['schema_headline'],
        'description' => $config['og_description'],
        'image' => 'https://dandangers.ru/dangers.jpg',
        'author' => ['@type' => 'Organization', 'name' => 'Союз [Dan]Dangers', 'url' => 'https://dandangers.ru/'],
        'publisher' => [
            '@type' => 'Organization',
            'name' => 'Tiles Survive Wiki',
            'logo' => ['@type' => 'ImageObject', 'url' => 'https://dandangers.ru/dangers.jpg']
        ],
        'datePublished' => '2026-01-15',
        'dateModified' => date('Y-m-d'),
        'mainEntityOfPage' => ['@type' => 'WebPage', '@id' => $canonical],
        'inLanguage' => 'ru-RU',
        'articleSection' => 'Расписание'
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    
    // BreadcrumbList
    $breadcrumbJson = json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
            ['@type' => 'ListItem', 'position' => 1, 'name' => 'Главная', 'item' => 'https://dandangers.ru/'],
            ['@type' => 'ListItem', 'position' => 2, 'name' => 'Расписание', 'item' => $canonical]
        ]
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    
    $navActive = [
        'about' => false,
        'library' => false,
        'old' => false,
        'schedule' => true,
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

function renderPage($data) {
    extract($data);
    $breadcrumbJson = $breadcrumbJson ?? '';
?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <!-- Preconnect -->
  <link rel="preconnect" href="https://mc.yandex.ru">
  <link rel="dns-prefetch" href="https://mc.yandex.ru">
  
  <!-- SEO Meta Tags -->
  <title><?= $title ?></title>
  <meta name="description" content="<?= $description ?>">
  <meta name="keywords" content="<?= $keywords ?>">
  <meta name="author" content="Союз [Dan]Dangers">
  <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1">
  <meta name="googlebot" content="index, follow">
  <meta name="yandex" content="index, follow">
  <link rel="canonical" href="<?= $canonical ?>">
  <link rel="alternate" hreflang="ru" href="<?= $canonical ?>">
  <link rel="alternate" hreflang="x-default" href="<?= $canonical ?>">
  
  <!-- Open Graph -->
  <meta property="og:type" content="article">
  <meta property="og:url" content="<?= $canonical ?>">
  <meta property="og:title" content="<?= $ogTitle ?>">
  <meta property="og:description" content="<?= $ogDescription ?>">
  <meta property="og:image" content="https://dandangers.ru/dangers.jpg">
  <meta property="og:image:width" content="512">
  <meta property="og:image:height" content="512">
  <meta property="og:locale" content="ru_RU">
  <meta property="og:site_name" content="Tiles Survive Wiki">
  <meta property="article:author" content="Союз [Dan]Dangers">
  <meta property="article:publisher" content="https://dandangers.ru/">
  
  <!-- Twitter -->
  <meta name="twitter:card" content="summary">
  <meta name="twitter:title" content="<?= $ogTitle ?>">
  <meta name="twitter:description" content="<?= $ogDescription ?>">
  <meta name="twitter:image" content="https://dandangers.ru/dangers.jpg">
  
  <!-- Schema.org -->
  <script type="application/ld+json"><?= $schemaJson ?></script>
<?php if ($breadcrumbJson): ?>
  <script type="application/ld+json"><?= $breadcrumbJson ?></script>
<?php endif; ?>
  
  <link rel="icon" type="image/jpeg" href="dangers.jpg">
  <link rel="apple-touch-icon" href="dangers.jpg">
  <link rel="stylesheet" href="modern-styles.css">
  
<?php if ($type === 'lootbar'): ?>
  <style>
    .lootbar-hero { background: linear-gradient(135deg, #fff9e6 0%, #ffe6a0 50%, #ffd54f 100%); border-radius: var(--radius-xl); padding: var(--space-6); margin-bottom: var(--space-6); text-align: center; border: 2px solid #ffc107; }
    .lootbar-hero h1 { color: #e65100 !important; -webkit-text-fill-color: #e65100 !important; background: none !important; }
    .cta-button { display: inline-flex; align-items: center; gap: var(--space-3); padding: var(--space-4) var(--space-8); background: linear-gradient(135deg, #ff9800 0%, #e65100 100%); color: white !important; font-size: var(--font-lg); font-weight: 700; border-radius: var(--radius-full); text-decoration: none; box-shadow: 0 4px 20px rgba(230, 81, 0, 0.4); transition: all var(--transition); margin-top: var(--space-4); }
    .cta-button:hover { transform: translateY(-3px); box-shadow: 0 8px 30px rgba(230, 81, 0, 0.5); color: white !important; }
    .content-wrapper table { border-collapse: separate; border-spacing: 0; background: var(--bg-elevated); border-radius: var(--radius-md); overflow: hidden; border: 1px solid var(--border); }
    .content-wrapper th { background: linear-gradient(135deg, #ff9800 0%, #e65100 100%); color: white; font-weight: 600; padding: var(--space-3) var(--space-4); }
    .content-wrapper td { padding: var(--space-3) var(--space-4); border-bottom: 1px solid var(--border-light); }
    .content-wrapper tr:last-child td { border-bottom: none; }
    .content-wrapper tbody tr:hover { background: var(--bg-card-hover); }
    .content-wrapper img { max-width: 100%; height: auto; border-radius: var(--radius-md); border: 1px solid var(--border); box-shadow: var(--shadow-md); margin: var(--space-4) 0; }
    .lootbar-nav { display: flex; justify-content: space-between; gap: var(--space-4); margin-top: var(--space-8); padding-top: var(--space-6); border-top: 1px solid var(--border); }
    .lootbar-nav a { padding: var(--space-3) var(--space-4); background: var(--bg-elevated); border: 1px solid var(--border); border-radius: var(--radius-md); text-decoration: none; transition: all var(--transition); }
    .lootbar-nav a:hover { border-color: var(--primary); background: var(--bg-card-hover); }
  </style>
<?php elseif ($type === 'schedule'): ?>
  <style>
    .content-wrapper table { font-size: var(--font-sm); width: 100%; border-collapse: separate; border-spacing: 0; background: var(--bg-elevated); border-radius: var(--radius-md); overflow: hidden; border: 1px solid var(--border); margin: var(--space-4) 0; }
    .content-wrapper th { background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); color: white; font-weight: 600; padding: var(--space-3) var(--space-4); text-align: left; white-space: nowrap; }
    .content-wrapper td { padding: var(--space-3) var(--space-4); border-bottom: 1px solid var(--border-light); vertical-align: top; }
    .content-wrapper tr:last-child td { border-bottom: none; }
    .content-wrapper tbody tr:hover { background: var(--bg-card-hover); }
    @media (max-width: 768px) { .content-wrapper table { font-size: var(--font-xs); display: block; overflow-x: auto; } .content-wrapper th, .content-wrapper td { padding: var(--space-2) var(--space-3); min-width: 100px; } }
    .content-wrapper h2 { margin-top: var(--space-8); }
  </style>
<?php endif; ?>

  <!-- Yandex.Metrika -->
  <script type="text/javascript">
      (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};m[i].l=1*new Date();for(var j=0;j<document.scripts.length;j++){if(document.scripts[j].src===r){return;}}k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})(window,document,'script','https://mc.yandex.ru/metrika/tag.js?id=105280577','ym');ym(105280577,'init',{ssr:true,webvisor:true,clickmap:true,ecommerce:"dataLayer",referrer:document.referrer,url:location.href,accurateTrackBounce:true,trackLinks:true});
  </script>
  <noscript><div><img src="https://mc.yandex.ru/watch/105280577" style="position:absolute;left:-9999px;" alt=""/></div></noscript>
</head>
<body>

<nav class="site-nav">
  <div class="nav-container">
    <a href="index.html" class="nav-logo"><img src="dangers.jpg" alt="Tiles Survive Wiki - DanDangers" class="nav-logo-img">TS Wiki</a>
    <button class="nav-toggle" aria-label="Открыть меню навигации">☰</button>
    <ul class="nav-menu">
      <li><a href="index.html">Главная</a></li>
      <li><a href="guide-soyuz-dangers.html"<?= $navActive['about'] ? ' class="active"' : '' ?>>О союзе</a></li>
      <li><a href="daily.html"<?= $navActive['schedule'] ? ' class="active"' : '' ?>>Расписание</a></li>
      <li class="nav-dropdown">
        <a href="guide-biblioteka.html" class="dropdown-toggle<?= $navActive['library'] ? ' active' : '' ?>">Библиотека ▾</a>
        <ul class="dropdown-menu">
          <li><a href="guide-vvedenie.html"<?= ($type==='guide' && $page==='vvedenie') ? ' class="active"' : '' ?>>Введение</a></li>
          <li><a href="guide-razvitie.html"<?= ($type==='guide' && $page==='razvitie') ? ' class="active"' : '' ?>>Развитие F2P</a></li>
          <li><a href="guide-zdaniya.html"<?= ($type==='guide' && $page==='zdaniya') ? ' class="active"' : '' ?>>Здания</a></li>
          <li><a href="guide-geroi.html"<?= ($type==='guide' && $page==='geroi') ? ' class="active"' : '' ?>>Герои</a></li>
          <li><a href="guide-laboratoriya.html"<?= ($type==='guide' && $page==='laboratoriya') ? ' class="active"' : '' ?>>Лаборатория</a></li>
          <li><a href="guide-sobytiya.html"<?= ($type==='guide' && $page==='sobytiya') ? ' class="active"' : '' ?>>События</a></li>
          <li><a href="guide-start.html"<?= ($type==='guide' && $page==='start') ? ' class="active"' : '' ?>>Быстрый старт</a></li>
          <li><a href="guide-vip.html"<?= ($type==='guide' && $page==='vip') ? ' class="active"' : '' ?>>VIP система</a></li>
          <li><a href="guide-faq.html"<?= ($type==='guide' && $page==='faq') ? ' class="active"' : '' ?>>FAQ</a></li>
          <li><a href="guide-biblioteka.html"><strong>Все 18 гайдов →</strong></a></li>
        </ul>
      </li>
      <li class="nav-dropdown">
        <a href="#" class="dropdown-toggle<?= $navActive['old'] ? ' active' : '' ?>">Старые ▾</a>
        <ul class="dropdown-menu">
          <li><a href="guide-17sovetov.html"<?= ($type==='guide' && $page==='17sovetov') ? ' class="active"' : '' ?>>17 советов</a></li>
          <li><a href="guide-bigguide.html"<?= ($type==='guide' && $page==='bigguide') ? ' class="active"' : '' ?>>Для новичков</a></li>
          <li><a href="guide-fullguide.html"<?= ($type==='guide' && $page==='fullguide') ? ' class="active"' : '' ?>>Полный гайд</a></li>
          <li><a href="guide-heroes-old.html"<?= ($type==='guide' && $page==='heroes-old') ? ' class="active"' : '' ?>>Герои</a></li>
          <li><a href="guide-lab-old.html"<?= ($type==='guide' && $page==='lab-old') ? ' class="active"' : '' ?>>Лаборатория</a></li>
          <li><a href="guide-res-old.html"<?= ($type==='guide' && $page==='res-old') ? ' class="active"' : '' ?>>Ресурсы</a></li>
        </ul>
      </li>
      <li class="nav-dropdown">
        <a href="lootbar-discounts.html" class="dropdown-toggle<?= $navActive['lootbar'] ? ' active' : '' ?>">Скидки ▾</a>
        <ul class="dropdown-menu">
          <li><a href="lootbar-discounts.html"<?= ($type==='lootbar' && $page==='discounts') ? ' class="active"' : '' ?>>Цены и купоны</a></li>
          <li><a href="lootbar-instruction.html"<?= ($type==='lootbar' && $page==='instruction') ? ' class="active"' : '' ?>>Инструкция</a></li>
        </ul>
      </li>
    </ul>
  </div>
</nav>

<main>

<?php if ($type !== 'lootbar'): ?>
<a href="lootbar-discounts.html" class="promo-banner<?= $type === 'schedule' ? ' wide' : '' ?>">
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
  <a href="index.html">Главная</a> › 
  <a href="guide-biblioteka.html">Библиотека</a><?php if ($page !== 'biblioteka'): ?> › 
  <span><?= $config['nav_title'] ?></span>
<?php endif; ?>
</nav>
<h1><?= $h1 ?></h1>
<?php elseif ($type === 'lootbar' && $page !== 'discounts'): ?>
<nav class="breadcrumb" aria-label="Хлебные крошки">
  <a href="index.html">Главная</a> › 
  <a href="lootbar-discounts.html">Скидки</a> › 
  <span><?= $config['nav_title'] ?></span>
</nav>
<?php elseif ($type === 'schedule'): ?>
<nav class="breadcrumb" aria-label="Хлебные крошки">
  <a href="index.html">Главная</a> › 
  <span>Расписание</span>
</nav>
<?php endif; ?>

<?php if ($type === 'lootbar'): ?>
<div class="lootbar-content">
<?php endif; ?>

<?= $content ?>

<?php if ($type === 'lootbar'): ?>
</div>
<?php endif; ?>

<?php if ($type === 'guide' && $navLinks): ?>
<nav class="guide-nav" aria-label="Навигация по гайдам">
  <?php if ($navLinks['prev']): ?>
    <a href="guide-<?= $navLinks['prev']['slug'] ?>.html" rel="prev">← <?= esc($navLinks['prev']['title']) ?></a>
  <?php else: ?>
    <a href="guide-biblioteka.html">← К списку гайдов</a>
  <?php endif; ?>
  <?php if ($navLinks['next']): ?>
    <a href="guide-<?= $navLinks['next']['slug'] ?>.html" rel="next">Следующий: <?= esc($navLinks['next']['title']) ?> →</a>
  <?php endif; ?>
</nav>
<?php elseif ($type === 'lootbar' && $navLinks): ?>
<nav class="lootbar-nav" aria-label="Навигация по разделу">
  <?php if ($navLinks['prev']): ?>
    <a href="lootbar-<?= $navLinks['prev']['slug'] ?>.html" rel="prev">← <?= esc($navLinks['prev']['title']) ?></a>
  <?php else: ?>
    <span></span>
  <?php endif; ?>
  <?php if ($navLinks['next']): ?>
    <a href="lootbar-<?= $navLinks['next']['slug'] ?>.html" rel="next"><?= esc($navLinks['next']['title']) ?> →</a>
  <?php endif; ?>
</nav>
<?php endif; ?>

</article>
</main>

<footer class="site-footer">
  <div class="footer-container">
    <p>© <?= date('Y') ?> Союз [Dan]Dangers | Штат 174 | <a href="https://dandangers.ru/">Tiles Survive Wiki</a></p>
  </div>
</footer>

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

</body>
</html>
<?php
}
