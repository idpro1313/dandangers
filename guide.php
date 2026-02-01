<?php
/**
 * Единый шаблон для отображения гайдов
 * Загружает контент из MD файлов и отображает с мета-данными из guides-config.php
 */

// Подключаем конфигурацию и Parsedown
require_once __DIR__ . '/guides-config.php';
require_once __DIR__ . '/lib/Parsedown.php';

// Получаем slug гайда из параметра
$page = isset($_GET['page']) ? preg_replace('/[^a-z0-9-]/', '', $_GET['page']) : '';

// Проверяем существование гайда
if (!$page || !guideExists($page)) {
    http_response_code(404);
    header('Location: novoe.html');
    exit;
}

// Получаем конфиг гайда
$config = getGuideConfig($page);
$navLinks = getGuideNavLinks($page);

// Путь к MD файлу
$mdFile = __DIR__ . '/content/guides/' . $page . '.md';

// Проверяем существование файла
if (!file_exists($mdFile)) {
    http_response_code(404);
    header('Location: novoe.html');
    exit;
}

// Читаем и конвертируем MD в HTML
$mdContent = file_get_contents($mdFile);
$Parsedown = new Parsedown();
$Parsedown->setSafeMode(false); // Разрешаем HTML в MD
$htmlContent = $Parsedown->text($mdContent);

// Генерируем Schema.org JSON-LD
$schemaType = isset($config['schema_type']) ? $config['schema_type'] : 'Article';
$schemaJson = '';

if ($schemaType === 'FAQPage') {
    // Специальная схема для FAQ (будет заполняться из контента)
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
        'image' => 'https://dandangers.ru/dangers.jpg',
        'author' => ['@type' => 'Organization', 'name' => 'Союз [Dan]Dangers'],
        'publisher' => [
            '@type' => 'Organization',
            'name' => 'Tiles Survive Wiki',
            'logo' => ['@type' => 'ImageObject', 'url' => 'https://dandangers.ru/dangers.jpg']
        ],
        'datePublished' => '2026-01-29',
        'dateModified' => date('Y-m-d'),
        'mainEntityOfPage' => [
            '@type' => 'WebPage',
            '@id' => 'https://dandangers.ru/guide-' . $page . '.html'
        ]
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
}

// Экранируем для HTML
$title = htmlspecialchars($config['title'], ENT_QUOTES, 'UTF-8');
$description = htmlspecialchars($config['description'], ENT_QUOTES, 'UTF-8');
$keywords = htmlspecialchars($config['keywords'], ENT_QUOTES, 'UTF-8');
$ogTitle = htmlspecialchars($config['og_title'], ENT_QUOTES, 'UTF-8');
$ogDescription = htmlspecialchars($config['og_description'], ENT_QUOTES, 'UTF-8');
$h1 = htmlspecialchars($config['h1'], ENT_QUOTES, 'UTF-8');
$canonical = 'https://dandangers.ru/guide-' . $page . '.html';

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
</head>
<body>

<nav class="site-nav">
  <div class="nav-container">
    <a href="index.html" class="nav-logo"><img src="dangers.jpg" alt="DanDangers" class="nav-logo-img">TS Wiki</a>
    <button class="nav-toggle" aria-label="Меню">☰</button>
    <ul class="nav-menu">
      <li><a href="index.html">Главная</a></li>
      <li><a href="main.html">О союзе</a></li>
      <li><a href="daily.html">Расписание</a></li>
      <li class="nav-dropdown">
        <a href="novoe.html" class="dropdown-toggle active">Библиотека ▾</a>
        <ul class="dropdown-menu">
          <li><a href="guide-vvedenie.html"<?php if($page==='vvedenie') echo ' class="active"'; ?>>Введение</a></li>
          <li><a href="guide-razvitie.html"<?php if($page==='razvitie') echo ' class="active"'; ?>>Развитие F2P</a></li>
          <li><a href="guide-zdaniya.html"<?php if($page==='zdaniya') echo ' class="active"'; ?>>Здания</a></li>
          <li><a href="guide-geroi.html"<?php if($page==='geroi') echo ' class="active"'; ?>>Герои</a></li>
          <li><a href="guide-laboratoriya.html"<?php if($page==='laboratoriya') echo ' class="active"'; ?>>Лаборатория</a></li>
          <li><a href="guide-sobytiya.html"<?php if($page==='sobytiya') echo ' class="active"'; ?>>События</a></li>
          <li><a href="guide-start.html"<?php if($page==='start') echo ' class="active"'; ?>>Быстрый старт</a></li>
          <li><a href="guide-vip.html"<?php if($page==='vip') echo ' class="active"'; ?>>VIP система</a></li>
          <li><a href="guide-faq.html"<?php if($page==='faq') echo ' class="active"'; ?>>FAQ</a></li>
          <li><a href="novoe.html"><strong>Все 18 гайдов →</strong></a></li>
        </ul>
      </li>
      <li class="nav-dropdown">
        <a href="#" class="dropdown-toggle">Старые ▾</a>
        <ul class="dropdown-menu">
          <li><a href="17sovetov.html">17 советов</a></li>
          <li><a href="bigguide.html">Для новичков</a></li>
          <li><a href="fullguide.html">Полный гайд</a></li>
          <li><a href="heroes.html">Герои</a></li>
          <li><a href="lab.html">Лаборатория</a></li>
          <li><a href="res.html">Ресурсы</a></li>
        </ul>
      </li>
      <li><a href="tabl.html">Таблица</a></li>
      <li><a href="lootbar.html">Скидки</a></li>
    </ul>
  </div>
</nav>

<main>
<a href="lootbar.html" class="promo-banner">
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

<p class="breadcrumb"><a href="novoe.html">← Библиотека гайдов</a></p>

<h1><?php echo $h1; ?></h1>

<?php echo $htmlContent; ?>

<p class="guide-nav">
  <?php if ($navLinks['prev']): ?>
    <a href="guide-<?php echo $navLinks['prev']['slug']; ?>.html">← <?php echo htmlspecialchars($navLinks['prev']['title'], ENT_QUOTES, 'UTF-8'); ?></a>
  <?php else: ?>
    <a href="novoe.html">← К списку гайдов</a>
  <?php endif; ?>
  
  <?php if ($navLinks['next']): ?>
    <a href="guide-<?php echo $navLinks['next']['slug']; ?>.html">Следующий: <?php echo htmlspecialchars($navLinks['next']['title'], ENT_QUOTES, 'UTF-8'); ?> →</a>
  <?php endif; ?>
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
