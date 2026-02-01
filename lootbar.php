<?php
/**
 * Единый шаблон для отображения страниц LootBar (донат)
 * Загружает контент из MD файлов и отображает с мета-данными из lootbar-config.php
 */

// Подключаем конфигурацию и Parsedown
require_once __DIR__ . '/lootbar-config.php';
require_once __DIR__ . '/lib/Parsedown.php';

// Получаем slug страницы из параметра
$page = isset($_GET['page']) ? preg_replace('/[^a-z0-9-]/', '', $_GET['page']) : 'discounts';

// Проверяем существование страницы
if (!lootbarExists($page)) {
    http_response_code(404);
    header('Location: lootbar-discounts.html');
    exit;
}

// Получаем конфиг страницы
$config = getLootbarConfig($page);
$navLinks = getLootbarNavLinks($page);

// Путь к MD файлу
$mdFile = __DIR__ . '/content/lootbar/' . $page . '.md';

// Проверяем существование файла
if (!file_exists($mdFile)) {
    http_response_code(404);
    header('Location: lootbar-discounts.html');
    exit;
}

// Читаем и конвертируем MD в HTML
$mdContent = file_get_contents($mdFile);
$Parsedown = new Parsedown();
$Parsedown->setSafeMode(false); // Разрешаем HTML в MD
$htmlContent = $Parsedown->text($mdContent);

// Исправляем пути к изображениям (относительные -> абсолютные от корня)
$htmlContent = preg_replace(
    '/src="(lootbar-[^"]+\.png)"/',
    'src="content/lootbar/$1"',
    $htmlContent
);

// Генерируем Schema.org JSON-LD
$schemaType = isset($config['schema_type']) ? $config['schema_type'] : 'Article';

if ($schemaType === 'HowTo') {
    $schemaJson = json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'HowTo',
        'name' => $config['schema_headline'],
        'description' => $config['description'],
        'image' => 'https://dandangers.ru/dangers.jpg',
        'step' => [
            ['@type' => 'HowToStep', 'name' => 'Выберите метод Self-TopUp', 'text' => 'После покупки подарочного пакета на LootBar выберите метод Self-TopUp (Пополнить самостоятельно).'],
            ['@type' => 'HowToStep', 'name' => 'Войдите в игровой аккаунт', 'text' => 'Войдите в интерфейс игры, войдите в свою учётную запись FunPlus ID и перейдите в игровой магазин.'],
            ['@type' => 'HowToStep', 'name' => 'Подтвердите покупку', 'text' => 'Подтвердите информацию о подарочном пакете и получите свой подарочный пакет.'],
            ['@type' => 'HowToStep', 'name' => 'Дождитесь завершения', 'text' => 'Не закрывайте интерфейс во время процесса погашения пакета.'],
            ['@type' => 'HowToStep', 'name' => 'Проверьте результат', 'text' => 'После уведомления о завершении войдите в игру и проверьте полученные предметы.']
        ]
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
            '@id' => 'https://dandangers.ru/lootbar-' . $page . '.html'
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
$canonical = 'https://dandangers.ru/lootbar-' . $page . '.html';

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
    /* Стили для LootBar страниц */
    .lootbar-hero {
      background: linear-gradient(135deg, #fff9e6 0%, #ffe6a0 50%, #ffd54f 100%);
      border-radius: var(--radius-xl);
      padding: var(--space-6);
      margin-bottom: var(--space-6);
      text-align: center;
      border: 2px solid #ffc107;
    }
    
    .lootbar-hero h1 {
      color: #e65100 !important;
      -webkit-text-fill-color: #e65100 !important;
      background: none !important;
    }
    
    .cta-button {
      display: inline-flex;
      align-items: center;
      gap: var(--space-3);
      padding: var(--space-4) var(--space-8);
      background: linear-gradient(135deg, #ff9800 0%, #e65100 100%);
      color: white !important;
      font-size: var(--font-lg);
      font-weight: 700;
      border-radius: var(--radius-full);
      text-decoration: none;
      box-shadow: 0 4px 20px rgba(230, 81, 0, 0.4);
      transition: all var(--transition);
      margin-top: var(--space-4);
    }
    
    .cta-button:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 30px rgba(230, 81, 0, 0.5);
      color: white !important;
    }
    
    /* Таблицы цен */
    .content-wrapper table {
      border-collapse: separate;
      border-spacing: 0;
      background: var(--bg-elevated);
      border-radius: var(--radius-md);
      overflow: hidden;
      border: 1px solid var(--border);
    }
    
    .content-wrapper th {
      background: linear-gradient(135deg, #ff9800 0%, #e65100 100%);
      color: white;
      font-weight: 600;
      padding: var(--space-3) var(--space-4);
    }
    
    .content-wrapper td {
      padding: var(--space-3) var(--space-4);
      border-bottom: 1px solid var(--border-light);
    }
    
    .content-wrapper tr:last-child td {
      border-bottom: none;
    }
    
    .content-wrapper tbody tr:hover {
      background: var(--bg-card-hover);
    }
    
    /* Изображения инструкции */
    .content-wrapper img {
      max-width: 100%;
      height: auto;
      border-radius: var(--radius-md);
      border: 1px solid var(--border);
      box-shadow: var(--shadow-md);
      margin: var(--space-4) 0;
    }
    
    /* Навигация между страницами */
    .lootbar-nav {
      display: flex;
      justify-content: space-between;
      gap: var(--space-4);
      margin-top: var(--space-8);
      padding-top: var(--space-6);
      border-top: 1px solid var(--border);
    }
    
    .lootbar-nav a {
      padding: var(--space-3) var(--space-4);
      background: var(--bg-elevated);
      border: 1px solid var(--border);
      border-radius: var(--radius-md);
      text-decoration: none;
      transition: all var(--transition);
    }
    
    .lootbar-nav a:hover {
      border-color: var(--primary);
      background: var(--bg-card-hover);
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
      <li><a href="daily.html">Расписание</a></li>
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
        <a href="lootbar-discounts.html" class="dropdown-toggle active">Скидки ▾</a>
        <ul class="dropdown-menu">
          <li><a href="lootbar-discounts.html"<?php if($page==='discounts') echo ' class="active"'; ?>>Цены и купоны</a></li>
          <li><a href="lootbar-instruction.html"<?php if($page==='instruction') echo ' class="active"'; ?>>Инструкция</a></li>
        </ul>
      </li>
    </ul>
  </div>
</nav>

<main>
<div class="content-wrapper">

<?php if ($page !== 'discounts'): ?>
<p class="breadcrumb"><a href="lootbar-discounts.html">← Скидки и купоны</a></p>
<?php endif; ?>

<div class="lootbar-content">
<?php echo $htmlContent; ?>
</div>

<div class="lootbar-nav">
  <?php if ($navLinks['prev']): ?>
    <a href="lootbar-<?php echo $navLinks['prev']['slug']; ?>.html">← <?php echo htmlspecialchars($navLinks['prev']['title'], ENT_QUOTES, 'UTF-8'); ?></a>
  <?php else: ?>
    <span></span>
  <?php endif; ?>
  
  <?php if ($navLinks['next']): ?>
    <a href="lootbar-<?php echo $navLinks['next']['slug']; ?>.html"><?php echo htmlspecialchars($navLinks['next']['title'], ENT_QUOTES, 'UTF-8'); ?> →</a>
  <?php endif; ?>
</div>

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
