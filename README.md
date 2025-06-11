# Laravel REST API: Departments & Employees

## Функциональность

Для каждой сущности реализованы 5 стандартных методов:

> **index** — список сущностей с пагинацией и сортировкой

> **store** — создание новой записи

> **show** — получение сущности по ID

> **update** — обновление существующей записи

> **destroy** — удаление записи

## Особенности

> Пагинация в index: по умолчанию 20 записей для сотрудников (10 для отделов) на страницу, можно изменить через GET-параметр size

> Сортировка: поддерживаются параметры sort_by и sort_direction

> Валидация входящих данных с подробными сообщениями об ошибках

> Eloquent ORM для работы с базой данных

> Фабрики Laravel для наполнения базы тестовыми данными

> Защита от удаления отделов: нельзя удалить отдел, если в нём есть сотрудники

> Формат ошибок — JSON с понятной структурой

## Маршруты

### Departments

```text
GET     /api/departments 
POST    /api/departments 
GET     /api/departments/{id}
PUT     /api/departments/{id}
DELETE  /api/departments/{id}  
```

### Employees

```text
GET     /api/employees
POST    /api/employees
GET     /api/employees/{id}
PUT     /api/employees/{id}
DELETE  /api/employees/{id} 
 
```

## Как развернуть проект:

Установите docker и docker-compose.

Скопируйте .env

```shell
cp .env.example .env
```

Ставим зависимости
```shell
docker-compose run --rm back composer install
```

Запускаем
```shell
docker-compose up -d
```

Создаем базу данных
```shell
docker-compose exec db psql -U postgres -c "create database backend;"
```

Запускаем миграции
```shell
docker-compose exec back php artisan migrate
```

Запускаем фабрики
```shell
docker-compose exec back php artisan db:seed
```

В браузере открываем http://localhost

## Примеры запросов 

```http
GET /api/employees?size=10&sort_by=last_name&sort_direction=asc

POST /api/employees
{
  "first_name": "Иван",
  "last_name": "Иванов",
  "middle_name": "Иванович",
  "email": "ivan@example.com",
  "sex": 1,
  "salary": 5000,
  "department_id": [1, 2]
}

GET /api/employees/5

PUT /api/employees/5
{
  "first_name": "Иван",
  "last_name": "Иванов",
  "middle_name": "Иванович",
  "email": "ivan@example.com",
  "salary": 5000,
  "department_id": [11, 2]
}

DELETE /api/employees/5
```

## Что хотелось сделать 

Изначально планировала развернуть проект на hoster.by, но стандартные тарифы не поддерживают Docker 
Запуск возможен только на VPS или выделенном сервере, что требует отдельного тарифа

## Примерное затраченное время 

10 часов 

