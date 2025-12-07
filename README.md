# 📰 News API

RESTful API service for news management with multi-language support and flexible content block system.

![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?style=for-the-badge&logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.4-777BB4?style=for-the-badge&logo=php)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![Filament](https://img.shields.io/badge/Filament-4.0-FFAA00?style=for-the-badge)
![Pest](https://img.shields.io/badge/Pest-4.0-42D392?style=for-the-badge)
![Docker](https://img.shields.io/badge/Docker-Ready-2496ED?style=for-the-badge&logo=docker&logoColor=white)

## 📋 Table of Contents

- [About](#-about)
- [Features](#-features)
- [Tech Stack](#️-tech-stack)
- [Requirements](#-requirements)
- [Installation](#-installation)
- [Running the Application](#-running-the-application)
- [API Documentation](#-api-documentation)
- [Testing](#-testing)
- [Project Structure](#-project-structure)

## 🎯 About

**News API** is a modern RESTful API service built with Laravel 12 for news management. The project includes:

- 🔐 Token-based authentication via Laravel Sanctum
- 👤 User profile management
- 📝 Full CRUD operations for news
- 🌍 Multi-language support (EN/DE) via Spatie Translatable
- 🔍 Search and filtering
- 🧱 Flexible content block system
- 👁️ News visibility control
- ♻️ Soft delete with restore capability
- 🛡️ Authorization policies
- ⚙️ Filament v4 Admin Panel for content management

## ✨ Features

### Authentication
- User registration
- Login with Bearer token
- Logout

### User Profile
- View profile
- Update name, email, password

### News Management
- **CRUD operations**: create, read, update, delete
- **Slug generation**: automatic unique slug generation
- **Multi-language**: EN/DE support for title and short_description
- **Search**: by title (all languages)
- **Filtering**: by author and publish date
- **Visibility control**: hide/show news (is_visible)
- **Soft delete**: with restore capability
- **Force delete**: permanent deletion
- **Content blocks**: 5 block types (text, image, text_image_right, text_image_left, slider)

### Authorization
- Owners see all their news (including hidden)
- Other users see only visible and published news
- Only owners can edit/delete their news

### Admin Panel (Filament v4)
- **Dashboard**: Overview of news statistics and recent activity
- **News Management**: Create, edit, delete news with rich text editor
- **User Management**: Manage system users and authors
- **Content Blocks**: Visual builder for news content
- **Multi-language**: Manage translations for EN/DE
- **Media Library**: Upload and manage images
- **Access Control**: Role-based access (coming soon)

## 🛠️ Tech Stack

- **Backend**: Laravel 12 (PHP 8.4)
- **Database**: MySQL 8.0
- **Authentication**: Laravel Sanctum
- **Admin Panel**: Filament v4
- **Translations**: Spatie Laravel Translatable
- **Testing**: Pest
- **Code Style**: Laravel Pint
- **Containerization**: Docker & Docker Compose

## 📦 Requirements

- Docker & Docker Compose
- Git

## 🚀 Installation

1. **Clone the repository:**
```bash
git clone https://github.com/kennwat/news_api.git
cd news_api
```

2. **Copy the .env file:**
```bash
cp .env.example .env
```

3. **Start Docker containers:**
```bash
docker compose up -d
```

4. **Install dependencies:**
```bash
docker compose exec app composer install
```

5. **Generate application key:**
```bash
docker compose exec app php artisan key:generate
```

6. **Run migrations:**
```bash
docker compose exec app php artisan migrate
```

7. **Optional: Seed the database with test data:**
```bash
docker compose exec app php artisan db:seed
```

## 🏃 Running the Application

```bash
# Start containers
docker compose up -d

# Stop containers
docker compose down

# Restart containers
docker compose restart

# View logs
docker compose logs -f app
```

The application will be available at: **http://localhost**

### Admin Panel Access

The Filament admin panel is available at: **http://localhost/admin**

To create an admin user:
```bash
docker compose exec app php artisan make:filament-user
```

Or use tinker to create a user manually:
```bash
docker compose exec app php artisan tinker
> User::create(['name' => 'Admin', 'email' => 'admin@example.com', 'password' => bcrypt('password')]);
```

## 📚 API Documentation

Detailed documentation for all endpoints is available in [`API_ENDPOINTS.md`](./API_ENDPOINTS.md).

### Main Endpoints:

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

### Example - Creating News:

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

## 🧪 Testing

The project is covered with **Pest** tests.

### Running Tests:

```bash
# Run all tests
docker compose exec app php artisan test

# Run specific test
docker compose exec app php artisan test --filter=AuthTest

# Run with coverage
docker compose exec app php artisan test --coverage
```

### Code Formatting:

```bash
# Check code style
docker compose exec app vendor/bin/pint --test

# Fix code style
docker compose exec app vendor/bin/pint
```

## 📁 Project Structure

```
news_api/
├── app/
│   ├── Enums/
│   │   └── BlockTypeEnum.php          # Content block types
│   ├── Filament/
│   │   └── Resources/                 # Filament admin resources
│   ├── Http/
│   │   ├── Controllers/Api/
│   │   │   ├── AuthController.php     # Authentication
│   │   │   ├── NewsController.php     # News management
│   │   │   └── ProfileController.php  # Profile management
│   │   ├── Requests/                  # Form Request validation
│   │   ├── Resources/                 # API Resources
│   │   └── Traits/                    # Reusable traits
│   ├── Models/
│   │   ├── ContentBlock.php
│   │   ├── ContentBlockDetails.php
│   │   ├── News.php
│   │   └── User.php
│   └── Policies/
│       └── NewsPolicy.php             # Authorization policies
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
├── API_ENDPOINTS.md                   # API documentation
├── compose.yaml                       # Docker Compose config
└── README.md                          # This file
```

## 🗄️ Database Structure

### Tables:

- **users** - System users (news authors)
- **news** - News with translations, slug, visibility
- **content_blocks** - Content blocks (text, image, slider, etc.)
- **content_block_details** - Block details (text, images, positions)
- **personal_access_tokens** - Sanctum authentication tokens

### Content Block Types:

1. `text` - Text only
2. `image` - Image only
3. `text_image_right` - Text with image on the right
4. `text_image_left` - Text with image on the left
5. `slider` - Slider with multiple images

## 🔒 Security

- Password hashing via bcrypt
- API protection via Laravel Sanctum
- CSRF protection
- SQL injection prevention via Eloquent ORM
- XSS protection via Blade escaping
- API rate limiting

## 📝 Useful Commands

```bash
# Clear cache
docker compose exec app php artisan cache:clear
docker compose exec app php artisan config:clear
docker compose exec app php artisan route:clear

# Refresh migrations (deletes all data!)
docker compose exec app php artisan migrate:fresh --seed

# Check routes
docker compose exec app php artisan route:list
```

## 👥 Author

- **Bohdan Lebedovskyi** - Initial work

## 📄 License

This project is licensed under the MIT License.

---

**Built with ❤️ using Laravel 12**
