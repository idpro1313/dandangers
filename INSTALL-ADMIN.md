# Установка админ-панели на сервер

## Требования
- Docker + Docker Compose
- Ubuntu сервер

## Шаги установки

### 1. Загрузите файлы на сервер

Скопируйте в `/var/static-site/site/` файлы:
- `admin.html`
- `admin-api.php`
- `admin-config.php`

### 2. Измените пароль

Отредактируйте `admin-config.php` на сервере:

```bash
nano /var/static-site/site/admin-config.php
```

Измените строку:
```php
define('ADMIN_PASSWORD', 'ваш_надёжный_пароль');
```

### 3. Создайте папку для бэкапов

```bash
mkdir -p /var/static-site/site/backups
chmod 777 /var/static-site/site/backups
```

### 4. Обновите конфигурацию nginx

Замените текущий `nginx.conf`:

```bash
# Сделайте резервную копию
cp /var/static-site/nginx.conf /var/static-site/nginx.conf.backup

# Загрузите новый конфиг (nginx.conf.admin переименовать в nginx.conf)
```

### 5. Обновите docker-compose.yml

Замените текущий `docker-compose.yml` новой версией с PHP-FPM.

### 6. Перезапустите контейнеры

```bash
cd /var/static-site

# Остановите текущие контейнеры
docker-compose down

# Запустите с новой конфигурацией
docker-compose up -d

# Проверьте статус
docker-compose ps
```

### 7. Проверьте работу

Откройте в браузере: `https://dandangers.ru/admin.html`

---

## Быстрая установка (все команды)

```bash
# На сервере
cd /var/static-site

# Остановить контейнеры
docker-compose down

# Создать папку бэкапов
mkdir -p site/backups
chmod 777 site/backups

# Убрать :ro из монтирования site в docker-compose.yml
# Добавить PHP-FPM сервис

# Заменить nginx.conf

# Изменить пароль в admin-config.php
nano site/admin-config.php

# Запустить
docker-compose up -d

# Проверить логи
docker-compose logs -f
```

---

## Альтернатива: минимальные изменения

Если не хотите менять docker-compose, можно использовать контейнер с nginx+php:

```yaml
version: '3.8'

services:
  web:
    image: webdevops/php-nginx:8.2-alpine
    container_name: static_web
    restart: unless-stopped
    environment:
      - WEB_DOCUMENT_ROOT=/app
    volumes:
      - ./site:/app
    ports:
      - "80:80"
```

Этот образ уже включает nginx + PHP-FPM в одном контейнере.

---

## Troubleshooting

### PHP файлы скачиваются вместо выполнения
- Проверьте что PHP-FPM контейнер запущен: `docker-compose ps`
- Проверьте логи: `docker-compose logs php`

### Ошибка "Permission denied" при сохранении
- Проверьте права: `ls -la /var/static-site/site/`
- Убедитесь что монтирование без `:ro`

### Ошибка 502 Bad Gateway
- PHP-FPM не запущен или неверный адрес в nginx.conf
- Проверьте что имя сервиса `php` совпадает в docker-compose.yml и nginx.conf
