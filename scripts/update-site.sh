#!/usr/bin/env bash
#
# Однократное обновление сайта с диска (для crontab на сервере).
# Не использует Docker внутри — только git + права + опциональный restart контейнеров.
#
# Crontab (каждые 15 минут):
#   */15 * * * * SITE_ROOT=/opt/dandangers /opt/dandangers/scripts/update-site.sh >> /var/log/dandangers-update.log 2>&1
#
# Переменные окружения:
#   SITE_ROOT  — корень репозитория (где лежат index.php и папка docker/). По умолчанию: каталог, в котором лежит scripts/
#   GIT_REMOTE — по умолчанию origin
#   GIT_BRANCH — по умолчанию main
#   DOCKER_COMPOSE_RESTART — если установлено в 1, после pull выполнится docker compose restart web php
#

set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
REPO_ROOT="$(cd "$SCRIPT_DIR/.." && pwd)"
SITE_ROOT="${SITE_ROOT:-$REPO_ROOT}"
GIT_REMOTE="${GIT_REMOTE:-origin}"
GIT_BRANCH="${GIT_BRANCH:-main}"

cd "$SITE_ROOT"

git config --global --add safe.directory "$SITE_ROOT" 2>/dev/null || true

# Локальный git user для коммитов с сервера (админка)
if ! git config --local user.email >/dev/null 2>&1; then
  git config --local user.email "${GIT_USER_EMAIL:-admin@dandangers.ru}"
  git config --local user.name "${GIT_USER_NAME:-dandangers server}"
fi

log() { echo "[$(date '+%Y-%m-%d %H:%M:%S')] $*"; }

# Локальные правки (админ-панель) — коммит перед pull
if [ -n "$(git status --porcelain 2>/dev/null)" ]; then
  log "Локальные изменения — коммит..."
  git add -A
  git commit -m "Сервер: изменения $(date '+%Y-%m-%d %H:%M')" || true
fi

log "git pull --rebase $GIT_REMOTE $GIT_BRANCH"
if ! git pull --rebase "$GIT_REMOTE" "$GIT_BRANCH"; then
  log "ОШИБКА: git pull --rebase, откат rebase"
  git rebase --abort || true
  exit 1
fi

git push "$GIT_REMOTE" "$GIT_BRANCH" 2>/dev/null || log "push пропущен (нет credentials или нечего пушить)"

chmod -R 755 "$SITE_ROOT/content" 2>/dev/null || true
chmod -R 755 "$SITE_ROOT/backups" 2>/dev/null || true

if [ "${DOCKER_COMPOSE_RESTART:-0}" = "1" ] && [ -f "$SITE_ROOT/docker/docker-compose.yml" ] && [ -f "$SITE_ROOT/docker/.env" ]; then
  log "docker compose restart web php"
  (cd "$SITE_ROOT/docker" && docker compose --env-file .env restart web php) || log "docker compose restart не выполнен (проверьте docker и .env)"
fi

log "Готово."
