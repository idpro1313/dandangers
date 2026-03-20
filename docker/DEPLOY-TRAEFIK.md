# Запуск dandangers.ru за Traefik ([webserver](https://github.com/idpro1313/webserver))

Caddy **не используется**: TLS и маршруты выдаёт **Traefik**, контейнеры сайта только **nginx + PHP-FPM** и подключаются к внешней сети `web`.

## Требования на сервере

1. Запущен Traefik из репозитория [idpro1313/webserver](https://github.com/idpro1313/webserver) (сеть **`web`**, resolver **`le`** или как у вас в `reverse-proxy`).
2. Создана сеть: `docker network create web` (если ещё нет).
3. DNS **A** для `dandangers.ru` и `www.dandangers.ru` → IP сервера.

## Установка

```bash
# Пример: код в /opt/dandangers
cd /opt
git clone https://github.com/idpro1313/dandangers.git dandangers
cd dandangers/docker
cp env.example .env
nano .env   # SITE_ROOT=/opt/dandangers и при необходимости имена контейнеров / TRAEFIK_*
```

Проверьте в `.env`:

- **`SITE_ROOT`** — абсолютный путь к корню репозитория (где лежат `index.php`, `content/`, `docker/`).
- **`TRAEFIK_RULE`** — домены с обратными кавычками: `` Host(`dandangers.ru`) || Host(`www.dandangers.ru`) ``
- **`TRAEFIK_ENTRYPOINT_HTTPS`** — как в вашем Traefik (часто `websecure`).
- **`TRAEFIK_CERT_RESOLVER`** — имя resolver в Traefik (в шаблоне webserver обычно `le`).

Запуск:

```bash
cd /opt/dandangers/docker
docker compose --env-file .env up -d
```

Через минуту проверьте `https://dandangers.ru` и сертификат Let’s Encrypt.

## Редирект HTTP → HTTPS

Роутер в compose только для **HTTPS**. Если HTTP-запросы не перенаправляются, включите глобальный редирект в Traefik или добавьте отдельный router в `docker-compose.yml` (см. документацию Traefik).

## Автообновление (cron)

Контейнер **`updater`** больше не используется. Обновление кода — **скрипт на хосте**, по расписанию в **crontab**.

1. Сделайте скрипт исполняемым:

   ```bash
   chmod +x /opt/dandangers/scripts/update-site.sh
   ```

2. Скрипт делает только **`git pull`** (без commit/push). Репозиторий на сервере должен быть клоном с GitHub с **read** доступом (обычно публичный clone достаточно). Если на сервере есть незакоммиченные правки, `git pull` может завершиться с ошибкой — тогда разберите вручную или сделайте `git stash` / `git reset --hard` (осторожно).

3. Пример **crontab** (каждые 15 минут):

   ```cron
   */15 * * * * SITE_ROOT=/opt/dandangers /opt/dandangers/scripts/update-site.sh >> /var/log/dandangers-update.log 2>&1
   ```

4. Скрипт **по умолчанию** после `git pull` выполняет **`docker compose restart web php`**, чтобы подхватить новый `nginx.conf` и код PHP (без перезапуска nginx внутри контейнера старый конфиг остаётся в памяти). Нужны файлы `docker/docker-compose.yml` и **`docker/.env`**.

   Чтобы **не** перезапускать контейнеры (только pull):

   ```bash
   SKIP_DOCKER_RESTART=1 ./scripts/update-site.sh
   ```

## Миграция со старого стека (Caddy + updater)

1. Остановите старый compose (с Caddy и updater):

   ```bash
   cd /var/static-site/site/docker   # или ваш путь
   docker compose down
   ```

2. Убедитесь, что Traefik на **80/443** и сеть **`web`** существует.

3. Разверните новый `docker-compose.yml` из этой папки с `.env` как выше.

4. Удалите старый `crontab` для контейнера updater, добавьте cron на `scripts/update-site.sh`.

## Файлы

| Файл | Назначение |
|------|------------|
| `docker/docker-compose.yml` | nginx + php, labels Traefik |
| `docker/env.example` | шаблон `.env` |
| `docker/nginx.conf` | nginx |
| `scripts/update-site.sh` | `git pull` + права + перезапуск контейнеров |
| `docker/update.sh` | полное обновление Docker: `pull`, `build --no-cache` (если есть build), `up --force-recreate` |
| `docker/Caddyfile` | **устарел** (оставлен только как напоминание; Caddy не используется) |

### Полная пересборка контейнеров без кэша (вручную)

После правок в `docker-compose` или когда нужно заново скачать образы и пересоздать контейнеры:

```bash
cd /opt/webserver/sites/dandangers/docker
./update.sh
```

Не делает `git pull` — только Docker. Код сайта на диске подхватывается из примонтированного `SITE_ROOT`.
