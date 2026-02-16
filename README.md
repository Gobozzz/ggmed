# GGMED 2.0

## О проекте

Проект представляет из себя REST API интерфейс.

Стэк: Laravel 12, MySQL,Redis, Sanctum, MoonShine.

## Как стартануть

### Для первой инициализации

- `git clone https://github.com/Gobozzz/ggmed.git ggmed` 
- `cd ggmed`
- `docker compose up -d --build`
- `docker compose exec php bash`
- `composer setup`
- `php artisan migrate`

### Для остальных инициализаций

`docker compose up -d`

### Создане .env файла

`cp .env.example .env`

### Laravel App
- URL: http://localhost

## Админка

Логин: admin@ggmed.ru
Пароль: ggmed_14&01&2026!
