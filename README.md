# GGMED 2.0

## Как стартануть

### Для первой инициализации

- `git clone https://github.com/Gobozzz/ggmed.git ggmed` 
- `cd ggmed`
- `docker compose up -d --build`
- `docker compose exec php bash`
- `composer setup`
- `php artisan migrate`

### From the second time onwards

`docker compose up -d`

### env.example -> .env

`cp .env.example .env`

### Laravel App
- URL: http://localhost
