# News API Endpoints

## ⚙️ Admin Panel

The project includes **Filament v4 Admin Panel** for managing news, users, and content through a beautiful web interface.

- **URL**: `http://localhost/admin`
- **Features**: News CRUD, User management, Content blocks builder, Media library
- **Multi-language**: Full support for EN/DE translations

### Creating Admin User

```bash
# Using Filament command
docker compose exec app php artisan make:filament-user

# Or using tinker
docker compose exec app php artisan tinker
> User::create(['name' => 'Admin', 'email' => 'admin@example.com', 'password' => bcrypt('password')]);
```

---

## 🔐 Authentication

### Register

-   **POST** `/api/register`
-   **Body:** `{ name, email, password, password_confirmation }`
-   **Response:** User + Token

**Example:**
```bash
curl -X POST http://localhost/api/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

### Login

-   **POST** `/api/login`
-   **Body:** `{ email, password }`
-   **Response:** User + Token

**Example:**
```bash
curl -X POST http://localhost/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "john@example.com",
    "password": "password123"
  }'
```

### Logout

-   **POST** `/api/logout`
-   **Auth:** Required (Bearer Token)
-   **Response:** Success message

**Example:**
```bash
curl -X POST http://localhost/api/logout \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

---

## 👤 Profile

### Get Profile

-   **GET** `/api/profile`
-   **Auth:** Required
-   **Response:** User data

**Example:**
```bash
curl -X GET http://localhost/api/profile \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

### Update Profile

-   **PUT/PATCH** `/api/profile`
-   **Auth:** Required
-   **Body:** `{ name?, email?, password?, password_confirmation? }`
-   **Response:** Updated user data

**Example:**
```bash
curl -X PATCH http://localhost/api/profile \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "John Smith",
    "email": "johnsmith@example.com"
  }'
```

---

## 📰 News (Public)

### List News

-   **GET** `/api/news`
-   **Auth:** Optional
-   **Query Params:**
    -   `search` - search by title/short_description
    -   `author` - author ID
    -   `date` - publication date (YYYY-MM-DD)
    -   `per_page` - items per page (default: 10)
    -   `include` - load relations (author,contentBlocks,contentBlocks.details)
-   **Response:** Paginated list of news
-   **Note:** Unauthenticated users see only `is_visible=true`, authors see all their own + others' visible news

**Examples:**
```bash
# Simple list
curl -X GET http://localhost/api/news

# With search
curl -X GET "http://localhost/api/news?search=laravel"

# With filters
curl -X GET "http://localhost/api/news?author=1&date=2025-12-06&per_page=5"

# With relations
curl -X GET "http://localhost/api/news?include=author,contentBlocks.details"
```

### View News

-   **GET** `/api/news/{slug}`
-   **Auth:** Optional
-   **Query Params:**
    -   `include` - load relations
-   **Response:** Single news item
-   **Note:** Unauthenticated users see only visible news, owner sees all their own

**Example:**
```bash
curl -X GET http://localhost/api/news/my-news-slug

# With relations
curl -X GET "http://localhost/api/news/my-news-slug?include=author,contentBlocks.details"
```

---

## 📰 News (Protected)

### Create News

-   **POST** `/api/news`
-   **Auth:** Required
-   **Body:**

```json
{
    "slug": "optional-auto-generated",
    "title": {
        "en": "English Title",
        "de": "German Title"
    },
    "short_description": {
        "en": "Short description",
        "de": "Kurze Beschreibung"
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
                        "en": "Content text",
                        "de": "Inhalt Text"
                    },
                    "position": 1
                }
            ]
        },
        {
            "type": "slider",
            "position": 2,
            "details": [
                {
                    "image_path": "/path/to/slide1.jpg",
                    "image_alt_text": {
                        "en": "Alt text",
                        "de": "Alt-Text"
                    },
                    "position": 1
                }
            ]
        }
    ]
}
```

-   **Response:** Created news with relations

### Update News

-   **PUT/PATCH** `/api/news/{slug}`
-   **Auth:** Required (Owner only)
-   **Body:** Same as Create (slug ignored)
-   **Response:** Updated news

**Example:**
```bash
curl -X PATCH http://localhost/api/news/my-news-slug \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -d '{
    "title": {
      "en": "Updated Title",
      "de": "Aktualisierter Titel"
    },
    "is_visible": false
  }'
```

### Delete News (Soft)

-   **DELETE** `/api/news/{slug}`
-   **Auth:** Required (Owner only)
-   **Response:** Success message
-   **Note:** Soft delete with cascading deletion of blocks

**Example:**
```bash
curl -X DELETE http://localhost/api/news/my-news-slug \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

### Toggle Visibility ⭐ Additional

-   **PATCH** `/api/news/{slug}/toggle-visibility`
-   **Auth:** Required (Owner only)
-   **Response:** Updated news
-   **Note:** Toggles `is_visible` between true/false

**Example:**
```bash
curl -X PATCH http://localhost/api/news/my-news-slug/toggle-visibility \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

### Restore Deleted News ⭐ Additional

-   **PATCH** `/api/news/{id}/restore`
-   **Auth:** Required (Owner only)
-   **Response:** Restored news
-   **Note:** Restores soft-deleted news with blocks

**Example:**
```bash
curl -X PATCH http://localhost/api/news/123/restore \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

### Force Delete News ⭐ Additional

-   **DELETE** `/api/news/{id}/force`
-   **Auth:** Required (Owner only)
-   **Response:** Success message
-   **Note:** Permanently deletes news from database (irreversible)

**Example:**
```bash
curl -X DELETE http://localhost/api/news/123/force \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

---

## 📦 Content Block Types

### Available Types (BlockTypeEnum)

-   `text` - Text only
-   `image` - Image only
-   `text_image_right` - Text with image on the right
-   `text_image_left` - Text with image on the left
-   `slider` - Slider (multiple images with positioning)

---

## 🔒 Authorization Rules

### News Policy

-   **viewAny:** Everyone (public + auth)
-   **view:** Owner sees all their own, others see only visible
-   **create:** Any authenticated user
-   **update:** Owner only
-   **delete:** Owner only
-   **restore:** Owner only
-   **forceDelete:** Owner only

### User Policy

-   **viewAny:** All authenticated users
-   **view:** User can view only their own profile
-   **update:** User can update only their own profile
-   **create:** Forbidden (registration via API)
-   **delete:** Forbidden
-   **restore:** Forbidden
-   **forceDelete:** Forbidden

---

## 💡 Tips

### Admin Panel

For easier content management, use the Filament Admin Panel at `/admin`. It provides:
- Visual content block builder
- Rich text editor for news
- Image upload and management
- Multi-language translation interface
- User-friendly forms with validation

### Dynamic Relations Loading

Add `?include=author,contentBlocks,contentBlocks.details` to any news endpoint to load relations.

### Search

`/api/news?search=keyword` - searches in title and short_description of both languages

### Filter by Author

`/api/news?author=1` - filters news by author

### Filter by Date

`/api/news?date=2025-12-06` - filters news by publication date

### Combine Filters

`/api/news?search=laravel&author=1&date=2025-12-06&per_page=20&include=author`
