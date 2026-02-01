<?php
/**
 * Единый шаблон для отображения страниц расписания
 * Загружает контент из MD файлов
 */

// Подключаем конфигурацию и Parsedown
require_once __DIR__ . '/schedule-config.php';
require_once __DIR__ . '/lib/Parsedown.php';

// Получаем slug страницы из параметра
$page = isset($_GET['page']) ? preg_replace('/[^a-z0-9-]/', '', $_GET['page']) : 'daily';

// Проверяем существование страницы
if (!scheduleExists($page)) {
    http_response_code(404);
    header('Location: index.html');
    exit;
}

// Получаем конфиг страницы
$config = getScheduleConfig($page);

// Путь к MD файлу
$mdFile = __DIR__ . '/content/schedule/' . $page . '.md';

// Проверяем существование файла
if (!file_exists($mdFile)) {
    http_response_code(404);
    header('Location: index.html');
    exit;
}

// Читаем и конвертируем MD в HTML
$mdContent = file_get_contents($mdFile);
$Parsedown = new Parsedown();
$Parsedown->setSafeMode(false);
$htmlContent = $Parsedown->text($mdContent);

// Генерируем Schema.org JSON-LD
$schemaJson = json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'Article',
    'headline' => $config['schema_headline'],
    'description' => $config['og_description'],
    'image' => 'https://dandangers.ru/dangers.jpg',
    'author' => ['@type' => 'Organization', 'name' => 'Союз [Dan]Dangers'],
    'publisher' => [
        '@type' => 'Organization',
        'name' => 'Tiles Survive Wiki',
        'logo' => ['@type' => 'ImageObject', 'url' => 'https://dandangers.ru/dangers.jpg']
    ],
    'datePublished' => '2026-01-15',
    'dateModified' => date('Y-m-d'),
    'mainEntityOfPage' => [
        '@type' => 'WebPage',
        '@id' => 'https://dandangers.ru/daily.html'
    ]
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

// Экранируем для HTML
$title = htmlspecialchars($config['title'], ENT_QUOTES, 'UTF-8');
$description = htmlspecialchars($config['description'], ENT_QUOTES, 'UTF-8');
$keywords = htmlspecialchars($config['keywords'], ENT_QUOTES, 'UTF-8');
$ogTitle = htmlspecialchars($config['og_title'], ENT_QUOTES, 'UTF-8');
$ogDescription = htmlspecialchars($config['og_description'], ENT_QUOTES, 'UTF-8');
$h1 = htmlspecialchars($config['h1'], ENT_QUOTES, 'UTF-8');
$canonical = 'https://dandangers.ru/daily.html';

?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <script type="text/javascript">
      (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};m[i].l=1*new Date();for(var j=0;j<document.scripts.length;j++){if(document.scripts[j].src===r){return;}}k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})(window,document,'script','https://mc.yandex.ru/metrika/tag.js?id=105280577','ym');ym(105280577,'init',{ssr:true,webvisor:true,clickmap:true,ecommerce:"dataLayer",referrer:document.referrer,url:location.href,accurateTrackBounce:true,trackLinks:true});
  </script>
  <noscript><div><img src="https://mc.yandex.ru/watch/105280577" style="position:absolute;left:-9999px;" alt=""/></div></noscript>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="<?php echo $description; ?>">
  <meta name="keywords" content="<?php echo $keywords; ?>">
  <meta name="author" content="Союз [Dan]Dangers">
  <meta name="robots" content="index, follow">
  <link rel="canonical" href="<?php echo $canonical; ?>">
  <meta property="og:type" content="article">
  <meta property="og:url" content="<?php echo $canonical; ?>">
  <meta property="og:title" content="<?php echo $ogTitle; ?>">
  <meta property="og:description" content="<?php echo $ogDescription; ?>">
  <meta property="og:image" content="https://dandangers.ru/dangers.jpg">
  <title><?php echo $title; ?></title>
  
  <script type="application/ld+json">
  <?php echo $schemaJson; ?>
  </script>
  
  <link rel="icon" type="image/jpeg" href="dangers.jpg">
  <link rel="stylesheet" href="modern-styles.css">
  <style>
    /* Стили для таблиц расписания */
    .content-wrapper table {
      font-size: var(--font-sm);
      width: 100%;
      border-collapse: separate;
      border-spacing: 0;
      background: var(--bg-elevated);
      border-radius: var(--radius-md);
      overflow: hidden;
      border: 1px solid var(--border);
      margin: var(--space-4) 0;
    }
    
    .content-wrapper th {
      background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
      color: white;
      font-weight: 600;
      padding: var(--space-3) var(--space-4);
      text-align: left;
      white-space: nowrap;
    }
    
    .content-wrapper td {
      padding: var(--space-3) var(--space-4);
      border-bottom: 1px solid var(--border-light);
      vertical-align: top;
    }
    
    .content-wrapper tr:last-child td {
      border-bottom: none;
    }
    
    .content-wrapper tbody tr:hover {
      background: var(--bg-card-hover);
    }
    
    /* Цвета для пересечений событий */
    .content-wrapper td:has(🟢),
    .content-wrapper td:contains("🟢") {
      background: rgba(76, 175, 80, 0.15);
    }
    
    .content-wrapper td:has(🟡),
    .content-wrapper td:contains("🟡") {
      background: rgba(255, 193, 7, 0.15);
    }
    
    .content-wrapper td:has(🟠),
    .content-wrapper td:contains("🟠") {
      background: rgba(255, 152, 0, 0.15);
    }
    
    /* Мобильная адаптация */
    @media (max-width: 768px) {
      .content-wrapper table {
        font-size: var(--font-xs);
        display: block;
        overflow-x: auto;
      }
      
      .content-wrapper th,
      .content-wrapper td {
        padding: var(--space-2) var(--space-3);
        min-width: 100px;
      }
    }
    
    /* Заголовки дней */
    .content-wrapper h2 {
      margin-top: var(--space-8);
    }
    
    /* Подсветка текущего дня */
    .current-day {
      border: 2px solid var(--primary) !important;
    }
  </style>
</head>
<body>

<nav class="site-nav">
  <div class="nav-container">
    <a href="index.html" class="nav-logo"><img src="dangers.jpg" alt="DanDangers" class="nav-logo-img">TS Wiki</a>
    <button class="nav-toggle" aria-label="Меню">☰</button>
    <ul class="nav-menu">
      <li><a href="index.html">Главная</a></li>
      <li><a href="main.html">О союзе</a></li>
      <li><a href="daily.html" class="active">Расписание</a></li>
      <li class="nav-dropdown">
        <a href="novoe.html" class="dropdown-toggle">Библиотека ▾</a>
        <ul class="dropdown-menu">
          <li><a href="guide-vvedenie.html">Введение</a></li>
          <li><a href="guide-razvitie.html">Развитие F2P</a></li>
          <li><a href="guide-zdaniya.html">Здания</a></li>
          <li><a href="guide-geroi.html">Герои</a></li>
          <li><a href="guide-laboratoriya.html">Лаборатория</a></li>
          <li><a href="guide-sobytiya.html">События</a></li>
          <li><a href="guide-start.html">Быстрый старт</a></li>
          <li><a href="guide-vip.html">VIP система</a></li>
          <li><a href="guide-faq.html">FAQ</a></li>
          <li><a href="novoe.html"><strong>Все 18 гайдов →</strong></a></li>
        </ul>
      </li>
      <li class="nav-dropdown">
        <a href="#" class="dropdown-toggle">Старые ▾</a>
        <ul class="dropdown-menu">
          <li><a href="guide-17sovetov.html">17 советов</a></li>
          <li><a href="guide-bigguide.html">Для новичков</a></li>
          <li><a href="guide-fullguide.html">Полный гайд</a></li>
          <li><a href="guide-heroes-old.html">Герои</a></li>
          <li><a href="guide-lab-old.html">Лаборатория</a></li>
          <li><a href="guide-res-old.html">Ресурсы</a></li>
        </ul>
      </li>
      <li><a href="tabl.html">Таблица</a></li>
      <li class="nav-dropdown">
        <a href="lootbar-discounts.html" class="dropdown-toggle">Скидки ▾</a>
        <ul class="dropdown-menu">
          <li><a href="lootbar-discounts.html">Цены и купоны</a></li>
          <li><a href="lootbar-instruction.html">Инструкция</a></li>
        </ul>
      </li>
    </ul>
  </div>
</nav>

<main>

<!-- Промо-баннер LootBar -->
<a href="lootbar-discounts.html" class="promo-banner wide">
  <div class="promo-banner-content">
    <div class="promo-banner-icon">💰</div>
    <div class="promo-banner-text">
      <div class="promo-banner-title">Скидки до 32% на игровую валюту!</div>
      <div class="promo-banner-desc">Купоны 6% и 10% для новых пользователей LootBar</div>
    </div>
  </div>
  <div class="promo-banner-badge">Подробнее</div>
</a>

<div class="content-wrapper wide">

<?php echo $htmlContent; ?>

<p style="margin-top: var(--space-6);">
  <a href="tabl.html">Смотрите также подробную таблицу ивентов →</a>
</p>

</div>
</main>

<footer class="site-footer">
  <div class="footer-container">
    <p>© <?php echo date('Y'); ?> Союз [Dan]Dangers | Штат 174 | Tiles Survive</p>
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
