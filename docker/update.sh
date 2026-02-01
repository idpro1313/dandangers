#!/bin/sh
while true; do
  sleep 300  # Проверять каждые 5 минут
  cd /site
  git pull origin main
  
  # Устанавливаем права для PHP на папки с контентом
  chmod -R 755 /site/content 2>/dev/null || true
  chmod -R 755 /site/backups 2>/dev/null || true
done
