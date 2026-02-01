#!/bin/sh

# Добавляем /site в safe.directory (решает "dubious ownership")
git config --global --add safe.directory /site

# Настройка git для коммитов
git config --global user.email "admin@dandangers.ru"
git config --global user.name "Admin Panel"

while true; do
  sleep 60  # Проверять каждую минуту
  cd /site
  
  # Проверяем есть ли локальные изменения
  if [ -n "$(git status --porcelain)" ]; then
    echo "[$(date)] Обнаружены локальные изменения, коммитим..."
    git add -A
    git commit -m "Изменения через админ-панель $(date '+%Y-%m-%d %H:%M')"
  fi
  
  # Подтягиваем изменения с rebase (локальные изменения будут поверх)
  git pull --rebase origin main || {
    echo "[$(date)] Конфликт при pull, откатываем rebase..."
    git rebase --abort
  }
  
  # Пушим локальные коммиты в репозиторий
  git push origin main 2>/dev/null || true
  
  # Устанавливаем права для PHP на папки с контентом
  chmod -R 755 /site/content 2>/dev/null || true
  chmod -R 755 /site/backups 2>/dev/null || true
done
