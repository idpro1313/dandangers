#!/usr/bin/env bash
#
# Обновление кода с GitHub (git pull) и применение изменений в Docker.
#
# SITE_ROOT — корень репозитория (по умолчанию каталог над scripts/)
# GIT_REMOTE, GIT_BRANCH — по умолчанию origin, main
#
# После pull по умолчанию выполняется: docker compose restart web php
# (новый nginx.conf подхватывается только после reload/restart контейнера web).
# SKIP_DOCKER_RESTART=1 — не перезапускать контейнеры (только git pull).

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

if [ "${SKIP_DOCKER_RESTART:-0}" != "1" ] && [ -f "$SITE_ROOT/docker/docker-compose.yml" ] && [ -f "$SITE_ROOT/docker/.env" ]; then
  log "docker compose restart web php (применение nginx.conf и PHP-кода)"
  if (cd "$SITE_ROOT/docker" && docker compose --env-file .env restart web php); then
    log "контейнеры перезапущены"
  else
    log "ВНИМАНИЕ: docker compose restart не выполнен (проверьте docker, путь к .env, имя проекта)"
  fi
elif [ -f "$SITE_ROOT/docker/docker-compose.yml" ] && [ ! -f "$SITE_ROOT/docker/.env" ]; then
  log "ВНИМАНИЕ: есть docker/docker-compose.yml, но нет docker/.env — перезапуск контейнеров пропущен"
fi

log "Готово."
