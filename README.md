# Travel Hub - Сайт туристического агентства

## Описание
Travel Hub - это веб-сайт для бронирования туров с системой регистрации и авторизации пользователей.

## Структура проекта
- `index.php` — корневой загрузчик, подключает `frontend/index.php`
- `frontend/`
  - `index.php` и `sss.php` — публичная главная страница
  - `window/` — все клиентские страницы (login, registration, dashboard, profile, tours и др.)
- `backend/`
  - `config/` — конфигурация и SQLite база
  - `api/` — REST/endpoints (например, `tours.php`)
  - `scripts/` — серверные обработчики форм (login, registration, logout, subscribe и др.)
  - `admin/` — служебные инструменты (панель администратора, создание БД, сидер и т.д.)
- `database/schema_sqlite.sql` — схема БД для SQLite

## Запуск сайта

### Требования
- PHP 7.4 или выше
- SQLite (встроен в PHP)

### Локальный запуск
1. Откройте терминал в корне проекта
2. Примените SQLite-схему (один раз): `php backend/admin/init_sqlite.php`
3. При необходимости создайте временного администратора: `php backend/admin/seed_admin.php`
4. Запустите встроенный сервер: `php -S localhost:8080`
5. Откройте браузер и перейдите по адресу `http://localhost:8080`

### Полезные ссылки
- [Главная](http://localhost:8080/index.php)
- [Админ-панель](http://localhost:8080/backend/admin/admin.php)
- [Создать БД](http://localhost:8080/backend/admin/create_db.php)
- [Назначить роль admin](http://localhost:8080/backend/admin/set_admin_role.php)
- [Туры](http://localhost:8080/frontend/window/tours.php)
- [Личный кабинет](http://localhost:8080/frontend/window/dashboard.php)
- [Профиль](http://localhost:8080/frontend/window/profile.php)
- [Регистрация](http://localhost:8080/frontend/window/registration.html)
- [Вход](http://localhost:8080/frontend/window/login.html)

## Функционал
- Регистрация и авторизация пользователей
- Просмотр туров
- Бронирование туров (с валидацией полей)
- Личный кабинет пользователя
- Админ-панель

## Особенности
- Валидация формы покупки: если не все поля заполнены, появляется сообщение с предложением зарегистрироваться
- Модальные окна для уведомлений
- Адаптивный дизайн
