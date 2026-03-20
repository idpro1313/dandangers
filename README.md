# Сайт dandangers.ru

Статический фронт + PHP (роутер, админка), контент в Markdown.

## Развёртывание на сервере с Traefik

Инструкция: **[docker/DEPLOY-TRAEFIK.md](docker/DEPLOY-TRAEFIK.md)**  
Общее окружение: репозиторий [idpro1313/webserver](https://github.com/idpro1313/webserver) (Traefik, сеть `web`).

Кратко:

1. `docker/network create web` (если ещё нет).
2. `cd docker && cp env.example .env` — задать `SITE_ROOT`, домены в `TRAEFIK_RULE`.
3. `docker compose --env-file .env up -d`
4. Автообновление кода: **cron** → `scripts/update-site.sh` (см. DEPLOY-TRAEFIK.md).

Caddy в проекте **не используется**.
