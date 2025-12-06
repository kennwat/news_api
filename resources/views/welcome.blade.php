<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>News API - RESTful API for News Management</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #333;
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            max-width: 1200px;
            width: 100%;
            overflow: hidden;
        }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 60px 40px;
            text-align: center;
        }
        
        .header h1 {
            font-size: 3em;
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        .header p {
            font-size: 1.2em;
            opacity: 0.9;
        }
        
        .badge {
            display: inline-block;
            background: rgba(255,255,255,0.2);
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.9em;
            margin: 20px 5px 0;
        }
        
        .content {
            padding: 40px;
        }
        
        .section {
            margin-bottom: 40px;
        }
        
        .section h2 {
            font-size: 1.8em;
            color: #667eea;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .section h3 {
            font-size: 1.3em;
            color: #764ba2;
            margin-top: 25px;
            margin-bottom: 15px;
        }
        
        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        
        .feature-card {
            background: #f8f9ff;
            padding: 25px;
            border-radius: 12px;
            border-left: 4px solid #667eea;
        }
        
        .feature-card h4 {
            font-size: 1.1em;
            color: #333;
            margin-bottom: 10px;
            font-weight: 600;
        }
        
        .feature-card p {
            font-size: 0.95em;
            color: #666;
        }
        
        .endpoint {
            background: #f8f9ff;
            padding: 15px 20px;
            border-radius: 8px;
            margin: 10px 0;
            font-family: 'Courier New', monospace;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .method {
            font-weight: 700;
            padding: 4px 12px;
            border-radius: 6px;
            font-size: 0.85em;
        }
        
        .method.get { background: #10b981; color: white; }
        .method.post { background: #3b82f6; color: white; }
        .method.put { background: #f59e0b; color: white; }
        .method.patch { background: #8b5cf6; color: white; }
        .method.delete { background: #ef4444; color: white; }
        
        .tech-stack {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 15px;
        }
        
        .tech-badge {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 0.9em;
            font-weight: 500;
        }
        
        .cta-buttons {
            display: flex;
            gap: 20px;
            margin-top: 30px;
            flex-wrap: wrap;
        }
        
        .btn {
            padding: 15px 30px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1em;
            transition: transform 0.2s, box-shadow 0.2s;
            display: inline-block;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .btn-secondary {
            background: white;
            color: #667eea;
            border: 2px solid #667eea;
        }
        
        .info-box {
            background: #fff9e6;
            border-left: 4px solid #f59e0b;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        
        .info-box strong {
            color: #f59e0b;
        }
        
        ul {
            margin-left: 20px;
            margin-top: 10px;
        }
        
        li {
            margin: 8px 0;
            color: #555;
        }
        
        code {
            background: #f3f4f6;
            padding: 2px 8px;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
            font-size: 0.9em;
            color: #e83e8c;
        }
        
        .footer {
            background: #f8f9ff;
            padding: 30px 40px;
            text-align: center;
            color: #666;
            border-top: 1px solid #e5e7eb;
        }
        
        .emoji {
            font-size: 1.5em;
            margin-right: 10px;
        }
        
        @media (max-width: 768px) {
            .header h1 {
                font-size: 2em;
            }
            
            .content {
                padding: 20px;
            }
            
            .features {
                grid-template-columns: 1fr;
            }
            
            .endpoint {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📰 News API</h1>
            <p>RESTful API для управління новинами з підтримкою перекладів</p>
            <div>
                <span class="badge">Laravel 12</span>
                <span class="badge">PHP 8.4</span>
                <span class="badge">MySQL</span>
                <span class="badge">Sanctum Auth</span>
            </div>
        </div>
        
        <div class="content">
            <!-- Project Description -->
            <div class="section">
                <h2><span class="emoji">🎯</span>Про проект</h2>
                <p>
                    <strong>News API</strong> - це сучасний RESTful API сервіс для управління новинами з багатомовною підтримкою. 
                    Проект створений з використанням Laravel 12 і надає повний CRUD функціонал для роботи з новинами, 
                    включаючи аутентифікацію, авторизацію та гнучку систему контент-блоків.
                </p>
            </div>

            <!-- Key Features -->
            <div class="section">
                <h2><span class="emoji">✨</span>Основний функціонал</h2>
                <div class="features">
                    <div class="feature-card">
                        <h4>🔐 Аутентифікація</h4>
                        <p>Реєстрація, логін, logout через Laravel Sanctum з token-based авторизацією</p>
                    </div>
                    <div class="feature-card">
                        <h4>👤 Управління профілем</h4>
                        <p>Перегляд та редагування профілю користувача (ім'я, email, пароль)</p>
                    </div>
                    <div class="feature-card">
                        <h4>📝 CRUD новин</h4>
                        <p>Повний цикл управління новинами: створення, перегляд, редагування, видалення</p>
                    </div>
                    <div class="feature-card">
                        <h4>🌍 Мультимовність</h4>
                        <p>Підтримка перекладів (EN/DE) для заголовків та описів через Spatie Translatable</p>
                    </div>
                    <div class="feature-card">
                        <h4>🔍 Пошук та фільтри</h4>
                        <p>Пошук по назві, фільтрація по автору та даті публікації</p>
                    </div>
                    <div class="feature-card">
                        <h4>👁️ Управління видимістю</h4>
                        <p>Можливість приховувати/показувати новини, soft delete з відновленням</p>
                    </div>
                    <div class="feature-card">
                        <h4>🧱 Контент-блоки</h4>
                        <p>Гнучка система блоків: текст, зображення, слайдери з різними типами компоновки</p>
                    </div>
                    <div class="feature-card">
                        <h4>🔗 Унікальні slug'и</h4>
                        <p>Автоматична генерація унікальних slug'ів для SEO-friendly URLs</p>
                    </div>
                    <div class="feature-card">
                        <h4>🛡️ Авторизація</h4>
                        <p>Політики доступу: власник може редагувати свої новини, всі бачать публічні</p>
                    </div>
                </div>
            </div>

            <!-- Tech Stack -->
            <div class="section">
                <h2><span class="emoji">🛠️</span>Технологічний стек</h2>
                <div class="tech-stack">
                    <span class="tech-badge">Laravel 12</span>
                    <span class="tech-badge">PHP 8.4</span>
                    <span class="tech-badge">MySQL</span>
                    <span class="tech-badge">Laravel Sanctum</span>
                    <span class="tech-badge">Spatie Translatable</span>
                    <span class="tech-badge">Pest (Testing)</span>
                    <span class="tech-badge">Laravel Pint</span>
                    <span class="tech-badge">Docker</span>
                </div>
            </div>

            <!-- Main Endpoints -->
            <div class="section">
                <h2><span class="emoji">🚀</span>Головні API Endpoints</h2>
                
                <h3>Аутентифікація</h3>
                <div class="endpoint">
                    <span class="method post">POST</span>
                    <span>/api/register</span>
                </div>
                <div class="endpoint">
                    <span class="method post">POST</span>
                    <span>/api/login</span>
                </div>
                <div class="endpoint">
                    <span class="method post">POST</span>
                    <span>/api/logout</span>
                </div>
                
                <h3>Профіль (auth required)</h3>
                <div class="endpoint">
                    <span class="method get">GET</span>
                    <span>/api/profile</span>
                </div>
                <div class="endpoint">
                    <span class="method patch">PATCH</span>
                    <span>/api/profile</span>
                </div>
                
                <h3>Новини (публічні)</h3>
                <div class="endpoint">
                    <span class="method get">GET</span>
                    <span>/api/news?search=query&author=1&date=2025-12-06</span>
                </div>
                <div class="endpoint">
                    <span class="method get">GET</span>
                    <span>/api/news/{slug}</span>
                </div>
                
                <h3>Новини (auth required)</h3>
                <div class="endpoint">
                    <span class="method post">POST</span>
                    <span>/api/news</span>
                </div>
                <div class="endpoint">
                    <span class="method patch">PATCH</span>
                    <span>/api/news/{slug}</span>
                </div>
                <div class="endpoint">
                    <span class="method delete">DELETE</span>
                    <span>/api/news/{slug}</span>
                </div>
                <div class="endpoint">
                    <span class="method patch">PATCH</span>
                    <span>/api/news/{slug}/toggle-visibility</span>
                </div>
                <div class="endpoint">
                    <span class="method patch">PATCH</span>
                    <span class="method delete">DELETE</span>
                    <span>/api/news/{id}/restore</span>
                </div>
                <div class="endpoint">
                    <span class="method delete">DELETE</span>
                    <span>/api/news/{id}/force</span>
                </div>
            </div>

            <!-- Content Block Types -->
            <div class="section">
                <h2><span class="emoji">🧩</span>Типи контент-блоків</h2>
                <ul>
                    <li><code>text</code> - Тільки текстовий контент</li>
                    <li><code>image</code> - Тільки зображення</li>
                    <li><code>text_image_right</code> - Текст з зображенням праворуч</li>
                    <li><code>text_image_left</code> - Текст з зображенням ліворуч</li>
                    <li><code>slider</code> - Слайдер (декілька зображень з позиціонуванням)</li>
                </ul>
            </div>

            <!-- Database Structure -->
            <div class="section">
                <h2><span class="emoji">🗄️</span>Структура бази даних</h2>
                <ul>
                    <li><strong>users</strong> - Користувачі системи (автори новин)</li>
                    <li><strong>news</strong> - Новини з перекладами, slug, видимістю, датою публікації</li>
                    <li><strong>content_blocks</strong> - Блоки контенту з типами та позиціями</li>
                    <li><strong>content_block_details</strong> - Деталі блоків (текст, зображення, alt-тексти)</li>
                    <li><strong>personal_access_tokens</strong> - Sanctum токени для аутентифікації</li>
                </ul>
                <p style="margin-top: 15px; color: #666;">
                    <em>Всі таблиці підтримують Soft Deletes з каскадним видаленням зв'язаних записів.</em>
                </p>
            </div>

            <!-- Testing -->
            <div class="section">
                <h2><span class="emoji">🧪</span>Тестування</h2>
                <p>Проект покритий <strong>Pest тестами</strong> (49 тестів, 112 асертів):</p>
                <ul>
                    <li><strong>AuthTest</strong> - 8 тестів (реєстрація, логін, logout)</li>
                    <li><strong>ProfileTest</strong> - 8 тестів (перегляд, редагування профілю)</li>
                    <li><strong>NewsTest</strong> - 18 тестів (CRUD, пошук, фільтри, видимість)</li>
                    <li><strong>NewsPolicyTest</strong> - 13 тестів (авторизація та політики доступу)</li>
                </ul>
                <div class="info-box">
                    <strong>✅ Всі тести проходять успішно!</strong> Запустіть <code>php artisan test</code> для перевірки.
                </div>
            </div>

            <!-- Features Highlight -->
            <div class="section">
                <h2><span class="emoji">💡</span>Особливості реалізації</h2>
                <ul>
                    <li>✅ <strong>Form Requests</strong> для валідації даних</li>
                    <li>✅ <strong>API Resources</strong> для форматування відповідей</li>
                    <li>✅ <strong>Policies</strong> для авторизації дій</li>
                    <li>✅ <strong>Traits</strong> для переvisного коду (HasSlug, CanLoadRelationships)</li>
                    <li>✅ <strong>Scopes</strong> в моделях (visible, published)</li>
                    <li>✅ <strong>Factories & Seeders</strong> для тестових даних</li>
                    <li>✅ <strong>Soft Deletes</strong> з каскадним видаленням</li>
                    <li>✅ <strong>Dynamic Relations Loading</strong> через <code>?include=author,contentBlocks</code></li>
                    <li>✅ <strong>Оптимізовані індекси БД</strong> для швидкого пошуку</li>
                    <li>✅ <strong>Laravel Pint</strong> для форматування коду</li>
                </ul>
            </div>

            <!-- Quick Start -->
            <div class="section">
                <h2><span class="emoji">🚀</span>Швидкий старт</h2>
                <div class="info-box">
                    <p>Детальні інструкції по встановленню та налаштуванню знаходяться в <strong>README.md</strong> файлі в корені проекту.</p>
                </div>
                <div class="cta-buttons">
                    <a href="/api/news" class="btn btn-primary">Переглянути новини</a>
                    <a href="#" class="btn btn-secondary" onclick="alert('Документацію можна знайти в файлі API_ENDPOINTS.md в корені проекту'); return false;">API Документація</a>
                </div>
            </div>
        </div>
        
        <div class="footer">
            <p>Built with ❤️ using Laravel 12 | © {{ date('Y') }}</p>
            <p style="margin-top: 10px; font-size: 0.9em;">
                API версія: <strong>1.0.0</strong> | 
                Laravel: <strong>{{ app()->version() }}</strong> | 
                PHP: <strong>{{ PHP_VERSION }}</strong>
            </p>
        </div>
    </div>
</body>
</html>
