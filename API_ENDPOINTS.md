# News API Endpoints

## 🔐 Authentication

### Register

-   **POST** `/api/register`
-   **Body:** `{ name, email, password, password_confirmation }`
-   **Response:** User + Token

### Login

-   **POST** `/api/login`
-   **Body:** `{ email, password }`
-   **Response:** User + Token

### Logout

-   **POST** `/api/logout`
-   **Auth:** Required (Bearer Token)
-   **Response:** Success message

---

## 👤 Profile

### Get Profile

-   **GET** `/api/profile`
-   **Auth:** Required
-   **Response:** User data

### Update Profile

-   **PUT/PATCH** `/api/profile`
-   **Auth:** Required
-   **Body:** `{ name?, email?, password?, password_confirmation? }`
-   **Response:** Updated user data

---

## 📰 News (Public)

### List News

-   **GET** `/api/news`
-   **Auth:** Optional
-   **Query Params:**
    -   `search` - пошук по title/short_description
    -   `author` - ID автора
    -   `date` - дата публікації (YYYY-MM-DD)
    -   `per_page` - кількість на сторінці (default: 10)
    -   `include` - завантаження relations (author,contentBlocks,contentBlocks.details)
-   **Response:** Paginated list of news
-   **Note:** Неавторизовані бачать тільки `is_visible=true`, автори бачать свої всі + інших видимі

### View News

-   **GET** `/api/news/{slug}`
-   **Auth:** Optional
-   **Query Params:**
    -   `include` - завантаження relations
-   **Response:** Single news item
-   **Note:** Неавторизовані бачать тільки видимі новини, власник бачить свої всі

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

### Delete News (Soft)

-   **DELETE** `/api/news/{slug}`
-   **Auth:** Required (Owner only)
-   **Response:** Success message
-   **Note:** Soft delete з каскадним видаленням блоків

### Toggle Visibility ⭐ NEW

-   **PATCH** `/api/news/{slug}/toggle-visibility`
-   **Auth:** Required (Owner only)
-   **Response:** Updated news
-   **Note:** Перемикає `is_visible` між true/false

### Restore Deleted News ⭐ NEW

-   **PATCH** `/api/news/{id}/restore`
-   **Auth:** Required (Owner only)
-   **Response:** Restored news
-   **Note:** Відновлює софт-видалену новину з блоками

### Force Delete News ⭐ NEW

-   **DELETE** `/api/news/{id}/force`
-   **Auth:** Required (Owner only)
-   **Response:** Success message
-   **Note:** Повністю видаляє новину з БД (незворотньо)

---

## 📦 Content Block Types

### Available Types (BlockTypeEnum)

-   `text` - Тільки текст
-   `image` - Тільки зображення
-   `text_image_right` - Текст + зображення праворуч
-   `text_image_left` - Текст + зображення ліворуч
-   `slider` - Слайдер (декілька зображень з позицією)

---

## 🔒 Authorization Rules

### News Policy

-   **viewAny:** Всі (public + auth)
-   **view:** Власник бачить всі свої, інші бачать тільки видимі
-   **create:** Будь-який авторизований
-   **update:** Тільки власник
-   **delete:** Тільки власник
-   **restore:** Тільки власник
-   **forceDelete:** Тільки власник

---

## 💡 Tips

### Dynamic Relations Loading

Додайте `?include=author,contentBlocks,contentBlocks.details` до будь-якого news endpoint для завантаження зв'язків.

### Search

`/api/news?search=keyword` - шукає в title та short_description обох мов

### Filter by Author

`/api/news?author=1` - фільтрує новини по автору

### Filter by Date

`/api/news?date=2025-12-06` - фільтрує новини по даті публікації

### Combine Filters

`/api/news?search=laravel&author=1&date=2025-12-06&per_page=20&include=author`
