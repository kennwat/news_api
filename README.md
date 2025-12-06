# 📰 News API

RESTful API сервіс для управління новинами з підтримкою перекладів та гнучкою системою контент-блоків.

![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?style=for-the-badge&logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.4-777BB4?style=for-the-badge&logo=php)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![Pest](https://img.shields.io/badge/Pest-4.0-42D392?style=for-the-badge)
![Docker](https://img.shields.io/badge/Docker-Ready-2496ED?style=for-the-badge&logo=docker&logoColor=white)

## 📋 Зміст

- [Про проект](#-про-проект)
- [Функціонал](#-функціонал)
- [Технологічний стек](#️-технологічний-стек)
- [Вимоги](#-вимоги)
- [Встановлення](#-встановлення)
- [Конфігурація](#️-конфігурація)
- [Запуск](#-запуск)
- [API Документація](#-api-документація)
- [Тестування](#-тестування)
- [Структура проекту](#-структура-проекту)

## 🎯 Про проект

**News API** - це сучасний RESTful API сервіс, побудований на Laravel 12, який надає повний функціонал для управління новинами. Проект включає:

- 🔐 Аутентифікацію через Laravel Sanctum (token-based)
- 👤 Управління профілем користувача
- 📝 Повний CRUD для новин
- 🌍 Мультимовність (EN/DE) через Spatie Translatable
- 🔍 Пошук та фільтрацію
- 🧱 Гнучку систему контент-блоків
- 👁️ Управління видимістю новин
- ♻️ Soft delete з можливістю відновлення
- 🛡️ Політики авторизації

## ✨ Функціонал

### Аутентифікація
- Реєстрація нових користувачів
- Логін з отриманням Bearer токену
- Logout (видалення токену)

### Профіль користувача
- Перегляд власного профілю
- Редагування імені, email, пароля

### Управління новинами
- **CRUD операції**: створення, перегляд, редагування, видалення
- **Slug генерація**: автоматична генерація унікальних slug'ів
- **Мультимовність**: підтримка EN/DE для title та short_description
- **Пошук**: по назві (всі мови)
- **Фільтрація**: по автору, даті публікації
- **Видимість**: приховування/показ новин (is_visible)
- **Soft delete**: м'яке видалення з можливістю відновлення
- **Force delete**: повне видалення з БД
- **Контент-блоки**: 5 типів блоків (text, image, text_image_right, text_image_left, slider)

### Авторизація
- Власник бачить всі свої новини (включно з прихованими)
- Інші користувачі бачать тільки видимі (is_visible = true) та опубліковані новини
- Тільки власник може редагувати/видаляти свої новини

## 🛠️ Технологічний стек

- **Backend**: Laravel 12 (PHP 8.4)
- **Database**: MySQL 8.0
- **Authentication**: Laravel Sanctum
- **Translations**: Spatie Laravel Translatable
- **Testing**: Pest 4 (49 тестів, 112 assertions)
- **Code Style**: Laravel Pint
- **Containerization**: Docker & Docker Compose

## 📦 Вимоги

- Docker & Docker Compose
- Git

**АБО якщо запускаєте локально:**

- PHP 8.4+
- Composer
- MySQL 8.0+
- Node.js & NPM

## 🚀 Встановлення

### Метод 1: Docker (Рекомендовано)

1. **Клонуйте репозиторій:**
```bash
git clone https://github.com/your-username/news_api.git
cd news_api
```

2. **Скопіюйте .env файл:**
```bash
cp .env.example .env
```

3. **Запустіть Docker контейнери:**
```bash
docker compose up -d
```

4. **Встановіть залежності:**
```bash
docker compose exec app composer install
```

5. **Згенеруйте ключ додатку:**
```bash
docker compose exec app php artisan key:generate
```

6. **Запустіть міграції:**
```bash
docker compose exec app php artisan migrate
```

7. **Опціонально: Наповніть БД тестовими даними:**
```bash
docker compose exec app php artisan db:seed
```

### Метод 2: Локальне встановлення

1. **Клонуйте репозиторій:**
```bash
git clone https://github.com/your-username/news_api.git
cd news_api
```

2. **Встановіть залежності:**
```bash
composer install
npm install
```

3. **Скопіюйте .env файл:**
```bash
cp .env.example .env
```

4. **Налаштуйте БД в .env:**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=news_api
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

5. **Згенеруйте ключ:**
```bash
php artisan key:generate
```

6. **Запустіть міграції:**
```bash
php artisan migrate
```

7. **Опціонально: Seed дані:**
```bash
php artisan db:seed
```

8. **Запустіть сервер:**
```bash
php artisan serve
```

## ⚙️ Конфігурація

### Основні налаштування .env

```env
APP_NAME="News API"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

# Database
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=news_api
DB_USERNAME=sail
DB_PASSWORD=password

# Sanctum
SANCTUM_STATEFUL_DOMAINS=localhost,127.0.0.1
```

### Мови для перекладів

Проект підтримує англійську (en) та німецьку (de) мови. Конфігурація в `config/translatable.php`:

```php
'locales' => ['en', 'de'],
'fallback_locale' => 'en',
```

## 🏃 Запуск

### Docker:
```bash
# Запустити контейнери
docker compose up -d

# Зупинити контейнери
docker compose down

# Перезапустити
docker compose restart

# Логи
docker compose logs -f app
```

### Локально:
```bash
# Запустити dev сервер
php artisan serve

# В окремому терміналі - queue worker (якщо потрібно)
php artisan queue:work

# Запустити Vite (якщо є фронтенд)
npm run dev
```

Додаток буде доступний за адресою: **http://localhost** (Docker) або **http://localhost:8000** (локально)

## 📚 API Документація

Детальна документація всіх endpoints знаходиться в файлі [`API_ENDPOINTS.md`](./API_ENDPOINTS.md).

### Основні endpoints:

#### Authentication
```http
POST   /api/register
POST   /api/login
POST   /api/logout
```

#### Profile (Auth required)
```http
GET    /api/profile
PATCH  /api/profile
```

#### News (Public)
```http
GET    /api/news
GET    /api/news/{slug}
```

#### News (Auth required)
```http
POST   /api/news
PATCH  /api/news/{slug}
DELETE /api/news/{slug}
PATCH  /api/news/{slug}/toggle-visibility
PATCH  /api/news/{id}/restore
DELETE /api/news/{id}/force
```

### Приклад запиту створення новини:

```bash
curl -X POST http://localhost/api/news \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "title": {
      "en": "Breaking News",
      "de": "Aktuelle Nachrichten"
    },
    "short_description": {
      "en": "This is a short description",
      "de": "Dies ist eine kurze Beschreibung"
    },
    "image_preview_path": "/path/to/image.jpg",
    "published_at": "2025-12-06 10:00:00",
    "is_visible": true,
    "content_blocks": [
      {
        "type": "text",
        "position": 1,
        "details": [
          {
            "text_content": {
              "en": "Full article text here",
              "de": "Vollständiger Artikeltext hier"
            },
            "position": 1
          }
        ]
      }
    ]
  }'
```

## 🧪 Тестування

Проект покритий **49 тестами** з **112 assertions** використовуючи **Pest**.

### Запуск тестів:

```bash
# Docker
docker compose exec app php artisan test

# Локально
php artisan test

# З покриттям коду
php artisan test --coverage

# Конкретний тест
php artisan test --filter=AuthTest
```

### Структура тестів:

- **AuthTest** (8 тестів) - Реєстрація, логін, logout
- **ProfileTest** (8 тестів) - Перегляд, редагування профілю
- **NewsTest** (18 тестів) - CRUD операції, пошук, фільтри, видимість
- **NewsPolicyTest** (13 тестів) - Авторизація та політики доступу

### Форматування коду:

```bash
# Перевірити код style
vendor/bin/pint --test

# Виправити код style
vendor/bin/pint
```

## 📁 Структура проекту

```
news_api/
├── app/
│   ├── Enums/
│   │   └── BlockTypeEnum.php          # Типи контент-блоків
│   ├── Http/
│   │   ├── Controllers/Api/
│   │   │   ├── AuthController.php     # Аутентифікація
│   │   │   ├── NewsController.php     # Управління новинами
│   │   │   └── ProfileController.php  # Управління профілем
│   │   ├── Requests/                  # Form Request валідація
│   │   ├── Resources/                 # API Resources
│   │   └── Traits/                    # Reusable traits
│   ├── Models/
│   │   ├── ContentBlock.php
│   │   ├── ContentBlockDetails.php
│   │   ├── News.php
│   │   └── User.php
│   └── Policies/
│       └── NewsPolicy.php             # Політики авторизації
├── database/
│   ├── factories/                     # Model factories
│   ├── migrations/                    # Database migrations
│   └── seeders/                       # Database seeders
├── routes/
│   └── api.php                        # API routes
├── tests/
│   └── Feature/
│       ├── AuthTest.php
│       ├── NewsTest.php
│       ├── NewsPolicyTest.php
│       └── ProfileTest.php
├── API_ENDPOINTS.md                   # API документація
├── WORK_PLAN.md                       # План робіт
├── compose.yaml                       # Docker Compose config
└── README.md                          # Цей файл
```

## 🗄️ Структура бази даних

### Таблиці:

- **users** - Користувачі (автори новин)
- **news** - Новини з перекладами, slug, видимістю
- **content_blocks** - Блоки контенту (text, image, slider, etc.)
- **content_block_details** - Деталі блоків (текст, зображення, позиції)
- **personal_access_tokens** - Sanctum токени

### Типи контент-блоків:

1. `text` - Тільки текст
2. `image` - Тільки зображення
3. `text_image_right` - Текст + зображення праворуч
4. `text_image_left` - Текст + зображення ліворуч
5. `slider` - Слайдер з декількома зображеннями

## 🔒 Безпека

- Всі паролі хешуються через bcrypt
- API захищено через Laravel Sanctum
- CSRF захист
- SQL injection prevention через Eloquent ORM
- XSS protection через Blade escaping
- Rate limiting на API endpoints

## 📝 Додаткова інформація

### Корисні команди:

```bash
# Очистити кеш
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Перезапустити міграції (видалить всі дані!)
php artisan migrate:fresh --seed

# Створити новий контролер
php artisan make:controller Api/ExampleController

# Створити новий тест
php artisan make:test ExampleTest --pest

# Перевірити роути
php artisan route:list
```

### Troubleshooting:

**Проблема**: Помилка з'єднання з БД
```bash
# Перевірте чи запущений MySQL контейнер
docker compose ps

# Перезапустіть контейнери
docker compose restart
```

**Проблема**: Permission denied
```bash
# Встановіть правильні права
chmod -R 777 storage bootstrap/cache
```

## 👥 Автори

- **Bohdan Lebedovskyi** - Initial work

## 📄 Ліцензія

Цей проект ліцензовано під MIT License.

## 🙏 Подяки

- [Laravel](https://laravel.com)
- [Spatie](https://spatie.be)
- [Pest PHP](https://pestphp.com)

---

**Створено з ❤️ використовуючи Laravel 12**
