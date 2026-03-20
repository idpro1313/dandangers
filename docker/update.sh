#!/bin/sh
#
# Полное обновление стека Docker-сайта без использования старых слоёв/контейнеров:
#   — актуальные образы с registry (pull)
#   — пересборка локальных образов без кэша, если в compose есть build:
#   — пересоздание контейнеров (force-recreate)
#
# Запуск из каталога docker/ или из корня репозитория:
#   ./docker/update.sh
#   cd docker && ./update.sh
#
# Нужен файл docker/.env (см. env.example).
#
set -e

SCRIPT_DIR=$(cd "$(dirname "$0")" && pwd)
cd "$SCRIPT_DIR"

if [ ! -f .env ]; then
  echo "Ошибка: нет $SCRIPT_DIR/.env — скопируйте env.example в .env и заполните SITE_ROOT и т.д."
  exit 1
fi

COMPOSE="docker compose --env-file .env"

echo "=== [1/4] docker compose pull — скачать свежие nginx/php с Docker Hub ==="
$COMPOSE pull

echo "=== [2/4] docker compose build --no-cache — пересборка образов из Dockerfile (если есть) ==="
# При отсутствии секции build команда обычно завершается успешно (нечего собирать)
$COMPOSE build --no-cache || true

echo "=== [3/4] docker compose up — пересоздать контейнеры без старых экземпляров ==="
$COMPOSE up -d --force-recreate --remove-orphans

echo "=== [4/4] проверка nginx внутри web ==="
$COMPOSE exec -T web nginx -t && echo "nginx -t: OK"

echo ""
echo "Готово: контейнеры пересозданы, образы обновлены."
