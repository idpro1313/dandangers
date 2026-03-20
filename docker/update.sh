#!/bin/sh
#
# Полная пересборка образа и пересоздание контейнера (nginx + PHP в одном).
#
set -e

SCRIPT_DIR=$(cd "$(dirname "$0")" && pwd)
cd "$SCRIPT_DIR"

if [ ! -f .env ]; then
  echo "Ошибка: нет $SCRIPT_DIR/.env — скопируйте env.example в .env"
  exit 1
fi

COMPOSE="docker compose --env-file .env"

echo "=== [1/3] docker compose build --no-cache (локальный образ из Dockerfile) ==="
$COMPOSE build --no-cache

echo "=== [2/3] docker compose up — пересоздать контейнер ==="
$COMPOSE up -d --force-recreate --remove-orphans

echo "=== [3/3] nginx -t в контейнере web ==="
$COMPOSE exec -T web nginx -t && echo "nginx -t: OK"

echo ""
echo "Готово."
