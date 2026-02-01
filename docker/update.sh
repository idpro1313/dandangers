#!/bin/sh

# Добавляем /site в safe.directory (решает "dubious ownership")
git config --global --add safe.directory /site

while true; do
  sleep 60  # Проверять каждую минуту
  cd /site
  git pull origin main
  
  # Устанавливаем права для PHP на папки с контентом
  chmod -R 755 /site/content 2>/dev/null || true
  chmod -R 755 /site/backups 2>/dev/null || true
done
