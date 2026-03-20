<?php
/**
 * Конфигурация гайдов — мета-данные для SEO
 * Мета-данные для SEO; страницы отдаёт index.php (роутер) из MD файлов
 */

$GUIDES = [
    'soyuz-dangers' => [
        'title' => 'Союз [Dan]Dangers — О нас | Tiles Survive Wiki',
        'h1' => 'Союз [Dan]Dangers',
        'description' => 'Союз [Dan]Dangers — активное сообщество игроков Tiles Survive на штате 174. Командная игра, обмен знаниями и взаимопомощь.',
        'keywords' => 'dandangers, tiles survive союз, tiles survive альянс, штат 174, tiles survive сообщество, tiles survive alliance guide, tiles survive клан',
        'og_title' => 'Союз [Dan]Dangers | Tiles Survive',
        'og_description' => 'Активное сообщество игроков Tiles Survive на штате 174. Командная игра и взаимопомощь.',
        'schema_headline' => 'Союз [Dan]Dangers — Сообщество Tiles Survive',
        'prev' => null,
        'next' => 'biblioteka',
        'nav_title' => 'О союзе',
        'is_about' => true
    ],
    'biblioteka' => [
        'title' => 'Библиотека гайдов — Tiles Survive Wiki | 18 руководств',
        'h1' => 'Библиотека гайдов Tiles Survive',
        'description' => 'Полная библиотека из 18 гайдов по Tiles Survive — от введения до FAQ. VIP система, герои, лаборатория, события, здания, ресурсы и частые ошибки новичков.',
        'keywords' => 'tiles survive гайды, tiles survive библиотека, tiles survive руководства, tiles survive wiki гайды, полный гайд tiles survive, tiles survive для новичков, tiles survive VIP, tiles survive герои гайд',
        'og_title' => 'Библиотека гайдов Tiles Survive — 18 руководств',
        'og_description' => 'Полная коллекция гайдов по игре Tiles Survive. От основ до продвинутых стратегий.',
        'schema_headline' => 'Библиотека гайдов Tiles Survive — 18 подробных руководств',
        'prev' => 'soyuz-dangers',
        'next' => 'vvedenie',
        'nav_title' => 'Библиотека',
        'is_library' => true
    ],
    'vvedenie' => [
        'title' => 'Введение и основные стратегии — Tiles Survive Wiki',
        'h1' => 'Введение и основные стратегии',
        'description' => 'Введение в Tiles Survive — основные стратегии, принципы игры, боевая мощь, разница F2P vs донат, роль союза и скорость развития.',
        'keywords' => 'tiles survive введение, tiles survive основы, tiles survive стратегии, tiles survive для начинающих, tiles survive боевая мощь, tiles survive f2p',
        'og_title' => 'Введение и основные стратегии — Tiles Survive',
        'og_description' => 'Что такое Tiles Survive, основные принципы игры, боевая мощь, разница F2P vs донат.',
        'schema_headline' => 'Введение и основные стратегии Tiles Survive',
        'prev' => 'biblioteka',
        'next' => 'razvitie',
        'nav_title' => 'Введение'
    ],
    'razvitie' => [
        'title' => 'Развитие без доната — Tiles Survive Wiki',
        'h1' => 'Развитие без доната',
        'description' => 'Развитие без доната в Tiles Survive — VIP система, очистка карты, стратегия 60 дней, дисциплина траты кристаллов.',
        'keywords' => 'tiles survive f2p, tiles survive без доната, tiles survive vip, tiles survive кристаллы, tiles survive развитие бесплатно',
        'og_title' => 'Развитие без доната — Tiles Survive',
        'og_description' => 'VIP система, очистка карты, стратегия развития 60 дней, дисциплина траты кристаллов.',
        'schema_headline' => 'Развитие без доната в Tiles Survive',
        'prev' => 'vvedenie',
        'next' => 'zdaniya',
        'nav_title' => 'Развитие F2P'
    ],
    'zdaniya' => [
        'title' => 'Здания и приоритеты строительства — Tiles Survive Wiki',
        'h1' => 'Здания и приоритеты строительства',
        'description' => 'Здания и приоритеты строительства в Tiles Survive — электростанция как король, иерархия зданий, цикл строительства, план по неделям.',
        'keywords' => 'tiles survive здания, tiles survive электростанция, tiles survive приоритеты строительства, tiles survive лаборатория, tiles survive казарма',
        'og_title' => 'Здания и приоритеты — Tiles Survive',
        'og_description' => 'Электростанция как король, иерархия зданий, цикл строительства, план по неделям.',
        'schema_headline' => 'Здания и приоритеты строительства в Tiles Survive',
        'prev' => 'razvitie',
        'next' => 'laboratoriya',
        'nav_title' => 'Здания'
    ],
    'laboratoriya' => [
        'title' => 'Лаборатория и науки — Tiles Survive Wiki',
        'h1' => 'Лаборатория и науки',
        'description' => 'Лаборатория и науки в Tiles Survive — фазы прокачки, приоритеты наук: Развитие, Экономика, Герои, Военное дело, полная таблица.',
        'keywords' => 'tiles survive лаборатория, tiles survive науки, tiles survive исследования, tiles survive развитие наук',
        'og_title' => 'Лаборатория и науки — Tiles Survive',
        'og_description' => 'Фазы прокачки наук, приоритеты: Развитие, Экономика, Герои, Военное дело, полная таблица.',
        'schema_headline' => 'Лаборатория и науки в Tiles Survive',
        'prev' => 'zdaniya',
        'next' => 'geroi',
        'nav_title' => 'Лаборатория'
    ],
    'geroi' => [
        'title' => 'Герои и боевые группы — Tiles Survive Wiki',
        'h1' => 'Герои и боевые группы',
        'description' => 'Герои и боевые группы в Tiles Survive — типы героев, основная группа Мэди, Розе, Бэка, Лэйла, правила прокачки звёзд.',
        'keywords' => 'tiles survive герои, tiles survive мэди, tiles survive розе, tiles survive бэка, tiles survive прокачка героев',
        'og_title' => 'Герои и боевые группы — Tiles Survive',
        'og_description' => 'Типы героев, основная группа Мэди, Розе, Бэка, Лэйла, правила прокачки звёзд.',
        'schema_headline' => 'Герои и боевые группы в Tiles Survive',
        'prev' => 'laboratoriya',
        'next' => 'snariazhenie',
        'nav_title' => 'Герои'
    ],
    'snariazhenie' => [
        'title' => 'Снаряжение и артефакты — Tiles Survive Wiki',
        'h1' => 'Снаряжение и артефакты',
        'description' => 'Снаряжение и артефакты в Tiles Survive — классы редкости, правило трёхцвета, рекомендуемые предметы: Нож, Часы, Фонарик.',
        'keywords' => 'tiles survive снаряжение, tiles survive артефакты, tiles survive экипировка, tiles survive синергия',
        'og_title' => 'Снаряжение и артефакты — Tiles Survive',
        'og_description' => 'Классы редкости, правило трёхцвета, рекомендуемые предметы: Нож, Часы, Фонарик.',
        'schema_headline' => 'Снаряжение и артефакты в Tiles Survive',
        'prev' => 'geroi',
        'next' => 'resursy',
        'nav_title' => 'Снаряжение'
    ],
    'resursy' => [
        'title' => 'Ресурсы и фарм — Tiles Survive Wiki',
        'h1' => 'Ресурсы и фарм',
        'description' => 'Ресурсы и фарм в Tiles Survive — типы ресурсов, плитки на карте, дневной ускоритель, оптимизация сбора, система магазинов.',
        'keywords' => 'tiles survive ресурсы, tiles survive фарм, tiles survive плитки, tiles survive сбор ресурсов',
        'og_title' => 'Ресурсы и фарм — Tiles Survive',
        'og_description' => 'Типы ресурсов, плитки на карте, дневной ускоритель, оптимизация сбора, система магазинов.',
        'schema_headline' => 'Ресурсы и фарм в Tiles Survive',
        'prev' => 'snariazhenie',
        'next' => 'sobytiya',
        'nav_title' => 'Ресурсы'
    ],
    'sobytiya' => [
        'title' => 'События и оптимизация — Tiles Survive Wiki',
        'h1' => 'События и оптимизация',
        'description' => 'События и оптимизация в Tiles Survive — Игра по-крупному, Турбо-черепашка, Дуэль союзов, Битва за резервуар, глобальная стратегия.',
        'keywords' => 'tiles survive события, tiles survive дуэль, tiles survive черепашка, tiles survive резервуар, tiles survive оптимизация',
        'og_title' => 'События и оптимизация — Tiles Survive',
        'og_description' => 'Игра по-крупному, Турбо-черепашка, Дуэль союзов, Битва за резервуар, глобальная стратегия.',
        'schema_headline' => 'События и оптимизация в Tiles Survive',
        'prev' => 'resursy',
        'next' => 'soyuz',
        'nav_title' => 'События'
    ],
    'soyuz' => [
        'title' => 'Союз и карта — Tiles Survive Wiki',
        'h1' => 'Союз и карта',
        'description' => 'Союз и карта в Tiles Survive — типы союзов, навыки союза, территории карты, Аркадия, справедливое распределение.',
        'keywords' => 'tiles survive союз, tiles survive альянс, tiles survive карта, tiles survive территории, tiles survive навыки союза',
        'og_title' => 'Союз и карта — Tiles Survive',
        'og_description' => 'Типы союзов, навыки союза, территории карты, Аркадия, справедливое распределение.',
        'schema_headline' => 'Союз и карта в Tiles Survive',
        'prev' => 'sobytiya',
        'next' => 'mehaniki',
        'nav_title' => 'Союз'
    ],
    'mehaniki' => [
        'title' => 'Специальные механики — Tiles Survive Wiki',
        'h1' => 'Специальные механики',
        'description' => 'Специальные механики в Tiles Survive — титан, радар и разведка, центр переработки, грузовики, монстры и боссы.',
        'keywords' => 'tiles survive титан, tiles survive радар, tiles survive разведка, tiles survive грузовики, tiles survive механики',
        'og_title' => 'Специальные механики — Tiles Survive',
        'og_description' => 'Титан, радар и разведка, центр переработки, грузовики, монстры и боссы.',
        'schema_headline' => 'Специальные механики в Tiles Survive',
        'prev' => 'soyuz',
        'next' => 'start',
        'nav_title' => 'Механики'
    ],
    'start' => [
        'title' => 'Быстрый старт — Tiles Survive Wiki',
        'h1' => 'Быстрый старт',
        'description' => 'Быстрый старт в Tiles Survive — первые 24 часа, день 2-3, неделя 1, месяц 1, чек-лист первого месяца.',
        'keywords' => 'tiles survive быстрый старт, tiles survive для новичков, tiles survive день 1, tiles survive первый месяц, tiles survive начало',
        'og_title' => 'Быстрый старт — Tiles Survive',
        'og_description' => 'Первые 24 часа, день 2-3, неделя 1, месяц 1, чек-лист первого месяца.',
        'schema_headline' => 'Быстрый старт в Tiles Survive',
        'prev' => 'mehaniki',
        'next' => 'vip',
        'nav_title' => 'Быстрый старт'
    ],
    'vip' => [
        'title' => 'VIP система — Tiles Survive Wiki',
        'h1' => 'VIP система',
        'description' => 'VIP система в Tiles Survive — почему VIP приоритет #1, математика VIP, стоимость уровней, график прокачки VIP 1-8.',
        'keywords' => 'tiles survive vip, tiles survive vip система, tiles survive vip уровни, tiles survive vip 8, tiles survive кристаллы vip',
        'og_title' => 'VIP система — Tiles Survive',
        'og_description' => 'Почему VIP приоритет #1, математика VIP, стоимость уровней, график прокачки VIP 1-8.',
        'schema_headline' => 'VIP система в Tiles Survive',
        'prev' => 'start',
        'next' => 'raspisanie',
        'nav_title' => 'VIP система'
    ],
    'raspisanie' => [
        'title' => 'Календарь и расписание — Tiles Survive Wiki',
        'h1' => 'Календарь и расписание',
        'description' => 'Календарь и расписание Tiles Survive — глобальное расписание, ежедневные и еженедельные события, идеальный месячный календарь.',
        'keywords' => 'tiles survive расписание, tiles survive календарь, tiles survive события расписание, tiles survive время событий',
        'og_title' => 'Календарь и расписание — Tiles Survive',
        'og_description' => 'Глобальное расписание, ежедневные и еженедельные события, идеальный месячный календарь.',
        'schema_headline' => 'Календарь и расписание в Tiles Survive',
        'prev' => 'vip',
        'next' => 'oshibki',
        'nav_title' => 'Расписание'
    ],
    'oshibki' => [
        'title' => 'Частые ошибки и решения — Tiles Survive Wiki',
        'h1' => 'Частые ошибки и решения',
        'description' => 'Частые ошибки в Tiles Survive — 14 критических ошибок новичков с подробным разбором: что делают неправильно и как надо.',
        'keywords' => 'tiles survive ошибки, tiles survive ошибки новичков, tiles survive советы, tiles survive что не делать',
        'og_title' => 'Частые ошибки — Tiles Survive',
        'og_description' => '14 критических ошибок новичков с подробным разбором: что делают неправильно и как надо.',
        'schema_headline' => 'Частые ошибки в Tiles Survive',
        'prev' => 'raspisanie',
        'next' => 'cheklista',
        'nav_title' => 'Ошибки'
    ],
    'cheklista' => [
        'title' => 'Чек-лист достижений — Tiles Survive Wiki',
        'h1' => 'Чек-лист достижений',
        'description' => 'Чек-лист достижений Tiles Survive — день 1, неделя 1, месяц 1-2, контрольные точки и таблица достижений по дням.',
        'keywords' => 'tiles survive чек-лист, tiles survive достижения, tiles survive прогресс, tiles survive контрольные точки',
        'og_title' => 'Чек-лист достижений — Tiles Survive',
        'og_description' => 'День 1, неделя 1, месяц 1-2, контрольные точки и таблица достижений по дням.',
        'schema_headline' => 'Чек-лист достижений в Tiles Survive',
        'prev' => 'oshibki',
        'next' => 'glossariy',
        'nav_title' => 'Чек-лист'
    ],
    'glossariy' => [
        'title' => 'Глоссарий терминов — Tiles Survive Wiki',
        'h1' => 'Глоссарий терминов',
        'description' => 'Глоссарий Tiles Survive — сокращения по зданиям, механики, события, валюты, войска, герои, типы боёв, специальные термины.',
        'keywords' => 'tiles survive глоссарий, tiles survive термины, tiles survive словарь, tiles survive сокращения',
        'og_title' => 'Глоссарий терминов — Tiles Survive',
        'og_description' => 'Сокращения по зданиям, механики, события, валюты, войска, герои, типы боёв.',
        'schema_headline' => 'Глоссарий Tiles Survive',
        'prev' => 'cheklista',
        'next' => 'tablicy',
        'nav_title' => 'Глоссарий'
    ],
    'tablicy' => [
        'title' => 'Таблицы сравнения — Tiles Survive Wiki',
        'h1' => 'Таблицы сравнения',
        'description' => 'Таблицы сравнения Tiles Survive — стадии развития по месяцам, сравнение F2P vs донат, критические точки, финальная матрица.',
        'keywords' => 'tiles survive таблицы, tiles survive сравнение, tiles survive прогресс по дням, tiles survive f2p vs донат',
        'og_title' => 'Таблицы сравнения — Tiles Survive',
        'og_description' => 'Стадии развития по месяцам, сравнение F2P vs донат, критические точки, финальная матрица.',
        'schema_headline' => 'Таблицы сравнения Tiles Survive',
        'prev' => 'glossariy',
        'next' => 'faq',
        'nav_title' => 'Таблицы'
    ],
    'faq' => [
        'title' => 'FAQ — Tiles Survive Wiki',
        'h1' => 'Часто задаваемые вопросы (FAQ)',
        'description' => 'FAQ Tiles Survive — 30+ вопросов с ответами: новички, здания, герои, снаряжение, ресурсы, события, финансы, техника.',
        'keywords' => 'tiles survive faq, tiles survive вопросы, tiles survive ответы, tiles survive помощь',
        'og_title' => 'FAQ — Tiles Survive',
        'og_description' => '30+ вопросов с ответами: новички, здания, герои, снаряжение, ресурсы, события, финансы, техника.',
        'schema_headline' => 'FAQ Tiles Survive',
        'schema_type' => 'FAQPage', // Специальный тип Schema.org для FAQ
        'prev' => 'tablicy',
        'next' => '17sovetov',
        'nav_title' => 'FAQ'
    ],
    
    // ===== СТАРЫЕ ГАЙДЫ =====
    '17sovetov' => [
        'title' => '17 ключевых советов — Tiles Survive Wiki',
        'h1' => '17 ключевых советов',
        'description' => '17 ключевых советов по Tiles Survive от опытных игроков. Как правильно тратить ресурсы, оптимизировать прокачку и побеждать в событиях.',
        'keywords' => 'tiles survive советы, tiles survive tips, tiles survive гайд для новичков, как играть в tiles survive, tiles survive стратегия',
        'og_title' => '17 ключевых советов — Tiles Survive',
        'og_description' => 'Проверенные советы от опытных игроков. Оптимизация ресурсов, стратегия прокачки.',
        'schema_headline' => '17 ключевых советов по Tiles Survive',
        'prev' => 'faq',
        'next' => 'bigguide',
        'nav_title' => '17 советов',
        'is_old' => true
    ],
    'bigguide' => [
        'title' => 'Гайд для новичков — Tiles Survive Wiki',
        'h1' => 'Гайд для новичков',
        'description' => 'Полный гайд для новичков Tiles Survive. Основы развития базы, приоритеты построек, рост боевой мощи, игра в союзе и тактика Аркадии.',
        'keywords' => 'tiles survive гайд для новичков, tiles survive как начать, tiles survive база, tiles survive постройки, tiles survive аркадия',
        'og_title' => 'Гайд для новичков — Tiles Survive',
        'og_description' => 'Полное руководство для начинающих игроков. Развитие базы, постройки, союз и Аркадия.',
        'schema_headline' => 'Гайд для новичков Tiles Survive',
        'prev' => '17sovetov',
        'next' => 'fullguide',
        'nav_title' => 'Для новичков',
        'is_old' => true
    ],
    'fullguide' => [
        'title' => 'Полный гайд по Tiles Survive — Wiki',
        'h1' => 'Полный гайд по Tiles Survive',
        'description' => 'Исчерпывающий гайд по Tiles Survive: стратегия без доната, VIP прокачка, Титан, герои, снаряжение шефа, тактика боя и защита Аркадии.',
        'keywords' => 'tiles survive полный гайд, tiles survive без доната, tiles survive vip, tiles survive титан, tiles survive снаряжение',
        'og_title' => 'Полный гайд по Tiles Survive',
        'og_description' => 'Исчерпывающее руководство: стратегия без доната, VIP, Титан, снаряжение и PvP тактика.',
        'schema_headline' => 'Полный гайд по Tiles Survive',
        'prev' => 'bigguide',
        'next' => 'heroes-old',
        'nav_title' => 'Полный гайд',
        'is_old' => true
    ],
    'heroes-old' => [
        'title' => 'Герои и снаряжение (старый гайд) — Tiles Survive Wiki',
        'h1' => 'Герои и снаряжение',
        'description' => 'Полный гайд по героям Tiles Survive: рейтинг лучших героев, навыки, снаряжение, оптимальные сборки отрядов. Бэка, Розе, Мэди, Никола.',
        'keywords' => 'tiles survive герои, tiles survive лучшие герои, tiles survive бэка, tiles survive розе, tiles survive снаряжение героев',
        'og_title' => 'Герои Tiles Survive — Гайд',
        'og_description' => 'Рейтинг лучших героев, навыки, снаряжение и оптимальные сборки отрядов.',
        'schema_headline' => 'Герои Tiles Survive — Лучшие герои и сборки',
        'prev' => 'fullguide',
        'next' => 'lab-old',
        'nav_title' => 'Герои (старый)',
        'is_old' => true
    ],
    'lab-old' => [
        'title' => 'Лаборатория (старый гайд) — Tiles Survive Wiki',
        'h1' => 'Лаборатория',
        'description' => 'Гайд по Лаборатории Tiles Survive: приоритеты прокачки наук, ветки Развитие, Экономика, Герои, Дуэль Союза. Оптимальная стратегия исследований.',
        'keywords' => 'tiles survive лаборатория, tiles survive науки, tiles survive исследования, tiles survive прокачка наук',
        'og_title' => 'Лаборатория Tiles Survive',
        'og_description' => 'Какие науки качать в первую очередь. Оптимальная стратегия исследований.',
        'schema_headline' => 'Лаборатория Tiles Survive — Приоритеты прокачки',
        'prev' => 'heroes-old',
        'next' => 'res-old',
        'nav_title' => 'Лаборатория (старый)',
        'is_old' => true
    ],
    'res-old' => [
        'title' => 'Ресурсы (старый гайд) — Tiles Survive Wiki',
        'h1' => 'Ресурсы',
        'description' => 'Гайд по ресурсам Tiles Survive: производство, быстрый сбор, бонусы скорости. Как эффективно фармить древесину, металл и еду.',
        'keywords' => 'tiles survive ресурсы, tiles survive фарм, tiles survive сбор ресурсов, tiles survive древесина, tiles survive производство',
        'og_title' => 'Ресурсы Tiles Survive',
        'og_description' => 'Эффективный сбор и производство ресурсов. Бонусы скорости и тактика фарма.',
        'schema_headline' => 'Ресурсы Tiles Survive — Гайд по сбору и фарму',
        'prev' => 'lab-old',
        'next' => null,
        'nav_title' => 'Ресурсы (старый)',
        'is_old' => true
    ]
];

// Порядок новых гайдов для навигации
$GUIDE_ORDER = [
    'vvedenie', 'razvitie', 'zdaniya', 'laboratoriya', 'geroi', 'snariazhenie',
    'resursy', 'sobytiya', 'soyuz', 'mehaniki', 'start', 'vip',
    'raspisanie', 'oshibki', 'cheklista', 'glossariy', 'tablicy', 'faq'
];

// Порядок старых гайдов
$OLD_GUIDE_ORDER = [
    '17sovetov', 'bigguide', 'fullguide', 'heroes-old', 'lab-old', 'res-old'
];

/**
 * Получить конфиг гайда по slug
 */
function getGuideConfig($slug) {
    global $GUIDES;
    return isset($GUIDES[$slug]) ? $GUIDES[$slug] : null;
}

/**
 * Проверить существование гайда
 */
function guideExists($slug) {
    global $GUIDES;
    return isset($GUIDES[$slug]);
}

/**
 * Получить навигационные ссылки для гайда
 */
function getGuideNavLinks($slug) {
    global $GUIDES;
    $config = getGuideConfig($slug);
    if (!$config) return ['prev' => null, 'next' => null];
    
    $prev = null;
    $next = null;
    
    if ($config['prev'] && isset($GUIDES[$config['prev']])) {
        $prev = [
            'slug' => $config['prev'],
            'title' => $GUIDES[$config['prev']]['nav_title']
        ];
    }
    
    if ($config['next'] && isset($GUIDES[$config['next']])) {
        $next = [
            'slug' => $config['next'],
            'title' => $GUIDES[$config['next']]['nav_title']
        ];
    }
    
    return ['prev' => $prev, 'next' => $next];
}
