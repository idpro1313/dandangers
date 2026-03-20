#!/usr/bin/env bash
#
# Обновление кода с GitHub (только git pull). Для crontab на сервере.
#
# */15 * * * * SITE_ROOT=/opt/dandangers /opt/dandangers/scripts/update-site.sh >> /var/log/dandangers-update.log 2>&1
#
# SITE_ROOT — корень репозитория (по умолчанию каталог над scripts/)
# GIT_REMOTE, GIT_BRANCH — по умолчанию origin, main
# DOCKER_COMPOSE_RESTART=1 — после pull перезапустить web и php (редко нужно)

set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
REPO_ROOT="$(cd "$SCRIPT_DIR/.." && pwd)"
SITE_ROOT="${SITE_ROOT:-$REPO_ROOT}"
GIT_REMOTE="${GIT_REMOTE:-origin}"
GIT_BRANCH="${GIT_BRANCH:-main}"

cd "$SITE_ROOT"
git config --global --add safe.directory "$SITE_ROOT" 2>/dev/null || true

log() { echo "[$(date '+%Y-%m-%d %H:%M:%S')] $*"; }

log "git pull $GIT_REMOTE $GIT_BRANCH"
git pull "$GIT_REMOTE" "$GIT_BRANCH"

chmod -R 755 "$SITE_ROOT/content" 2>/dev/null || true
chmod -R 755 "$SITE_ROOT/backups" 2>/dev/null || true

if [ "${DOCKER_COMPOSE_RESTART:-0}" = "1" ] && [ -f "$SITE_ROOT/docker/docker-compose.yml" ] && [ -f "$SITE_ROOT/docker/.env" ]; then
  log "docker compose restart web php"
  (cd "$SITE_ROOT/docker" && docker compose --env-file .env restart web php) || log "docker compose restart не выполнен"
fi

log "Готово."
