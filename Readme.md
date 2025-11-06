# Документация проекта Products Management

## Описание
Проект состоит из:
- **Backend**: Laravel приложение с PostgreSQL
- **Frontend**: Vue.js приложение
- **База данных**: PostgreSQL
- **Веб-сервер**: Nginx

## Основные команды Docker

### Запуск проекта
```bash
# Запустить все сервисы
docker-compose up -d

# Запустить с пересборкой образов
docker-compose up --build -d
```

### Остановка проекта
```bash
# Остановить все сервисы
docker-compose down

# Остановить с удалением volumes (данные БД будут удалены)
docker-compose down -v
```

### Просмотр логов
```bash
# Все логи
docker-compose logs

# Логи конкретного сервиса
docker-compose logs backend
docker-compose logs frontend  
docker-compose logs nginx
docker-compose logs postgres

# Логи в реальном времени
docker-compose logs -f backend
```

### Работа с контейнерами
```bash
# Показать статус контейнеров
docker-compose ps

# Перезапустить конкретный сервис
docker-compose restart backend

# Перезапустить все сервисы
docker-compose restart
```

## Команды внутри контейнеров

### Backend (Laravel) команды
```bash
# Войти в контейнер backend
docker exec -it laravel_backend bash

# Выполнить миграции (извне контейнера)
docker exec -it laravel_backend php /var/www/artisan migrate

# Создать новую миграцию
docker exec -it laravel_backend php /var/www/artisan make:migration create_table_name

# Запустить сиды (тестовые данные)
docker exec -it laravel_backend php /var/www/artisan db:seed

# Очистить кэш
docker exec -it laravel_backend php /var/www/artisan cache:clear
docker exec -it laravel_backend php /var/www/artisan config:clear
```

### Frontend команды
```bash
# Войти в контейнер frontend
docker exec -it vue_frontend bash

# Установить зависимости (извне контейнера)
docker exec -it vue_frontend npm install

# Собрать проект для production
docker exec -it vue_frontend npm run build
```

### База данных
```bash
# Подключиться к PostgreSQL
docker exec -it products_db psql -U postgres -d products_db

# Создать дамп базы данных
docker exec -it products_db pg_dump -U postgres products_db > backup.sql

# Восстановить из дампа
docker exec -i products_db psql -U postgres products_db < backup.sql
```

## Полезные команды для разработки

### Проверка работы
```bash
# Проверить доступность backend
curl http://localhost:8000

# Проверить доступность frontend  
curl http://localhost:3000

# Проверить подключение к БД
docker exec -it products_db pg_isready -U postgres
```

### Очистка
```bash
# Удалить все неиспользуемые образы, контейнеры, сети
docker system prune

# Удалить только остановленные контейнеры
docker container prune
```

## Доступ к приложениям

- **Frontend**: http://localhost:3000
- **Backend API**: http://localhost:8000
- **База данных**: localhost:5432

## Переменные окружения
Файл `.env` в корне проекта содержит настройки подключения к базе данных.