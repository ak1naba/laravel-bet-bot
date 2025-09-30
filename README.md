# Laravel Blogs

## Стек: [php, laravel, postgres. nginx, docker, docker-compose]

## Запуск
```shell
docker-compose up -d --build

docker-compose exec app bash

chmod -R 777 storage

composer install

php artisan key:gen

php artisan migrate --seed
```

## О сервисе
Сервис контенеризирован, в контейнере содержатся: Laravel, Postgres, Nginx.
Запуск описан выше.
Может возникнуть проблема с ращрешениями файлов, устарняла так

```shell
sudo chmod -R 777 laravel-blogs/
```

## Пользователь
admin@mail.ru - qweqweqwe

## Задание
Выполнил все, кроме вебсокетов.
Проект запускается по адресу: http://localhost:8000

## О системе и инструментах
OS Ubuntu 24.04.1 LTS
IDE PhpStorm 2024.1.2

## Для связи
https://t.me/ak1naba

