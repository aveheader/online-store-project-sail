# Online Store Project

Небольшой pet-проект интернет-магазина на Laravel.

Делал его как практику разработки e-commerce приложения: каталог товаров, категории, корзина, оформление заказов, платежный сценарий, личный кабинет пользователя и базовая административная часть.

Проект не претендует на production-ready интернет-магазин, но показывает работу с предметной моделью, пользовательскими сценариями покупки, интеграцией оплаты, ролями и Docker-окружением через Laravel Sail.

## Основной функционал

### Каталог
- просмотр списка товаров
- просмотр отдельного товара
- просмотр категорий
- фильтрация товаров по категориям

### Пользовательская часть
- регистрация и авторизация
- профиль пользователя
- личный кабинет
- история заказов

### Корзина
- добавление товара в корзину
- удаление товара из корзины
- изменение количества товаров
- получение состояния корзины через AJAX

### Заказы
- оформление заказа
- просмотр списка заказов
- просмотр отдельного заказа
- отмена заказа

### Оплата
- запуск платежа по заказу
- фейковый платежный шлюз для тестового сценария оплаты
- интеграция со Stripe
- обработка возврата после оплаты
- webhook для обработки событий Stripe

### Доступ и роли
- базовая ролевая модель
- permissions через Spatie Laravel Permission
- административная часть на Filament

## Стек

- PHP 8.2
- Laravel 12
- Eloquent ORM
- Laravel Breeze
- Laravel Sail
- Filament 3
- Spatie Laravel Permission
- Stripe PHP SDK
- MySQL
- Redis
- RabbitMQ
- Mailpit
- Vite
- Tailwind CSS
- Pest
- Laravel Pint

## Основные сущности

В проекте используются следующие основные модели:

- `Product`
- `Category`
- `Cart`
- `CartItem`
- `Order`
- `OrderItem`
- `Payment`
- `User`
- `UserProfile`

## Что реализовано по маршрутам

Публичная часть:
- каталог товаров
- карточка товара
- список категорий
- просмотр категории

Под авторизацией:
- dashboard
- профиль пользователя
- корзина
- AJAX-эндпоинты для корзины
- оформление и просмотр заказов

Платежный сценарий:
- запуск оплаты заказа
- тестовый fake gateway
- Stripe success / cancel
- Stripe webhook

## Разворачивание проекта

Проект контейнеризирован через Laravel Sail.

Для запуска нужен:
- Docker
- Docker Compose

## Первый запуск

Клонировать репозиторий:

```bash
git clone https://github.com/aveheader/online-store-project-sail.git
cd online-store-project-sail
```

Установить зависимости и подготовить окружение:

```bash
composer install
cp .env.example .env
```

Поднять Docker окружение:

```bash
./vendor/bin/sail up -d
```

Сгенерировать ключ приложения и выполнить миграции:

```bash
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate
```

Установить frontend зависимости и собрать ассеты:

```bash
./vendor/bin/sail npm install
./vendor/bin/sail npm run build
```

После запуска приложение будет доступно по адресу:

```
http://localhost
```

## Полезные команды

Запуск контейнеров:

```bash
./vendor/bin/sail up -d
```

Остановка контейнеров:

```bash
./vendor/bin/sail down
```

Выполнение artisan-команд:

```bash
./vendor/bin/sail artisan migrate
./vendor/bin/sail artisan db:seed
./vendor/bin/sail artisan test
```

Установка npm-зависимостей:

```bash
./vendor/bin/sail npm install
```

Запуск dev режима Vite:

```bash
./vendor/bin/sail npm run dev
```

Сборка production ассетов:

```bash
./vendor/bin/sail npm run build
```

## Docker окружение

В docker-compose окружении поднимаются следующие сервисы:

- `laravel.test` — контейнер приложения
- `mysql` — база данных
- `redis` — кэш
- `rabbitmq` — брокер сообщений
- `mailpit` — локальный SMTP сервер для тестирования почты

## Переменные окружения

Основные параметры подключения:

- `APP_URL=http://localhost`
- `DB_CONNECTION=mysql`
- `DB_HOST=mysql`
- `DB_PORT=3306`
- `DB_DATABASE=laravel`
- `DB_USERNAME=sail`
- `DB_PASSWORD=password`
- `REDIS_HOST=redis`
- `MAIL_HOST=mailpit`
- `MAIL_PORT=1025`

## Что хотел показать этим проектом

Этим pet-проектом хотел отработать на практике:

- разработку интернет-магазина на Laravel
- построение предметной модели e-commerce приложения
- сценарии корзины, заказа и оплаты
- интеграцию со Stripe
- работу с ролями и правами доступа
- запуск проекта в Docker через Laravel Sail

## Возможные улучшения

Что можно добавить в дальнейшем:

- полноценное REST API для клиентских приложений
- тестовое покрытие ключевых сценариев корзины и заказов
- фильтрацию и сортировку каталога
- промокоды и скидки
- избранные товары
- статусы доставки
- e-mail уведомления
- очереди для фоновых задач
- расширенную административную часть

## Автор

GitHub: https://github.com/aveheader
