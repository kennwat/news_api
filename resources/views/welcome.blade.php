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
            <p>RESTful API for News Management with Multi-language Support</p>
            <div>
                <span class="badge">Laravel 12</span>
                <span class="badge">PHP 8.4</span>
                <span class="badge">MySQL</span>
                <span class="badge">Sanctum Auth</span>
                <span class="badge">Filament Admin</span>
            </div>
        </div>
        
        <div class="content">
            <!-- Project Description -->
            <div class="section">
                <h2><span class="emoji">🎯</span>About the Project</h2>
                <p>
                    <strong>News API</strong> is a modern RESTful API service for managing news with multi-language support. 
                    Built with Laravel 12, it provides full CRUD functionality for news management, 
                    including authentication, authorization, and a flexible content block system.
                </p>
            </div>

            <!-- Key Features -->
            <div class="section">
                <h2><span class="emoji">✨</span>Key Features</h2>
                <div class="features">
                    <div class="feature-card">
                        <h4>🔐 Authentication</h4>
                        <p>Register, login, logout via Laravel Sanctum with token-based authorization</p>
                    </div>
                    <div class="feature-card">
                        <h4>👤 Profile Management</h4>
                        <p>View and edit user profile (name, email, password)</p>
                    </div>
                    <div class="feature-card">
                        <h4>📝 News CRUD</h4>
                        <p>Full news management cycle: create, view, edit, delete</p>
                    </div>
                    <div class="feature-card">
                        <h4>🌍 Multi-language</h4>
                        <p>Translation support (EN/DE) for titles and descriptions via Spatie Translatable</p>
                    </div>
                    <div class="feature-card">
                        <h4>🔍 Search & Filters</h4>
                        <p>Search by title, filter by author and publication date</p>
                    </div>
                    <div class="feature-card">
                        <h4>👁️ Visibility Control</h4>
                        <p>Ability to hide/show news, soft delete with restore</p>
                    </div>
                    <div class="feature-card">
                        <h4>🧱 Content Blocks</h4>
                        <p>Flexible block system: text, images, sliders with different layout types</p>
                    </div>
                    <div class="feature-card">
                        <h4>🔗 Unique Slugs</h4>
                        <p>Automatic generation of unique slugs for SEO-friendly URLs</p>
                    </div>
                    <div class="feature-card">
                        <h4>🛡️ Authorization</h4>
                        <p>Access policies: owners can edit their news, everyone sees public news</p>
                    </div>
                    <div class="feature-card">
                        <h4>⚙️ Admin Panel</h4>
                        <p>Filament v4 admin interface for managing news, users, and content</p>
                    </div>
                </div>
            </div>

            <!-- Tech Stack -->
            <div class="section">
                <h2><span class="emoji">🛠️</span>Tech Stack</h2>
                <div class="tech-stack">
                    <span class="tech-badge">Laravel 12</span>
                    <span class="tech-badge">PHP 8.4</span>
                    <span class="tech-badge">MySQL</span>
                    <span class="tech-badge">Laravel Sanctum</span>
                    <span class="tech-badge">Filament v4</span>
                    <span class="tech-badge">Spatie Translatable</span>
                    <span class="tech-badge">Pest (Testing)</span>
                    <span class="tech-badge">Laravel Pint</span>
                    <span class="tech-badge">Docker</span>
                </div>
            </div>

            <!-- Main Endpoints -->
            <div class="section">
                <h2><span class="emoji">🚀</span>Main API Endpoints</h2>
                
                <h3>Authentication</h3>
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
                
                <h3>Profile (auth required)</h3>
                <div class="endpoint">
                    <span class="method get">GET</span>
                    <span>/api/profile</span>
                </div>
                <div class="endpoint">
                    <span class="method patch">PATCH</span>
                    <span>/api/profile</span>
                </div>
                
                <h3>News (public)</h3>
                <div class="endpoint">
                    <span class="method get">GET</span>
                    <span>/api/news?search=query&author=1&date=2025-12-06</span>
                </div>
                <div class="endpoint">
                    <span class="method get">GET</span>
                    <span>/api/news/{slug}</span>
                </div>
                
                <h3>News (auth required)</h3>
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
                    <span>/api/news/{id}/restore</span>
                </div>
                <div class="endpoint">
                    <span class="method delete">DELETE</span>
                    <span>/api/news/{id}/force</span>
                </div>
            </div>

            <!-- Content Block Types -->
            <div class="section">
                <h2><span class="emoji">🧩</span>Content Block Types</h2>
                <ul>
                    <li><code>text</code> - Text content only</li>
                    <li><code>image</code> - Image only</li>
                    <li><code>text_image_right</code> - Text with image on the right</li>
                    <li><code>text_image_left</code> - Text with image on the left</li>
                    <li><code>slider</code> - Slider (multiple images with positioning)</li>
                </ul>
            </div>

            <!-- Database Structure -->
            <div class="section">
                <h2><span class="emoji">🗄️</span>Database Structure</h2>
                <ul>
                    <li><strong>users</strong> - System users (news authors)</li>
                    <li><strong>news</strong> - News with translations, slug, visibility, publish date</li>
                    <li><strong>content_blocks</strong> - Content blocks with types and positions</li>
                    <li><strong>content_block_details</strong> - Block details (text, images, alt texts)</li>
                    <li><strong>personal_access_tokens</strong> - Sanctum tokens for authentication</li>
                </ul>
                <p style="margin-top: 15px; color: #666;">
                    <em>All tables support Soft Deletes with cascading deletion of related records.</em>
                </p>
            </div>

            <!-- Features Highlight -->
            <div class="section">
                <h2><span class="emoji">💡</span>Implementation Highlights</h2>
                <ul>
                    <li>✅ <strong>Form Requests</strong> for data validation</li>
                    <li>✅ <strong>API Resources</strong> for response formatting</li>
                    <li>✅ <strong>Policies</strong> for authorization (News, User)</li>
                    <li>✅ <strong>Traits</strong> for reusable code (HasSlug, CanLoadRelationships)</li>
                    <li>✅ <strong>Scopes</strong> in models (visible, published)</li>
                    <li>✅ <strong>Factories & Seeders</strong> for test data</li>
                    <li>✅ <strong>Soft Deletes</strong> with cascading deletion</li>
                    <li>✅ <strong>Dynamic Relations Loading</strong> via <code>?include=author,contentBlocks</code></li>
                    <li>✅ <strong>Optimized DB Indexes</strong> for fast search</li>
                    <li>✅ <strong>Laravel Pint</strong> for code formatting</li>
                </ul>
            </div>

            <!-- Quick Start -->
            <div class="section">
                <h2><span class="emoji">🚀</span>Quick Start</h2>
                <div class="info-box">
                    <p>Detailed installation and setup instructions can be found in the <strong>README.md</strong> file in the project root.</p>
                </div>
                <div class="cta-buttons">
                    <a href="/api/news" class="btn btn-primary">View News</a>
                    <a href="/admin" class="btn btn-primary">Admin Panel</a>
                    <a href="#" class="btn btn-secondary" onclick="alert('Documentation can be found in the API_ENDPOINTS.md file in the project root'); return false;">API Documentation</a>
                </div>
            </div>
        </div>
        
        <div class="footer">
            <p>Built with ❤️ using Laravel 12 | © {{ date('Y') }} Bohdan Lebedovskyi</p>
            <p style="margin-top: 10px; font-size: 0.9em;">
                API version: <strong>1.0.0</strong> | 
                Laravel: <strong>{{ app()->version() }}</strong> | 
                PHP: <strong>{{ PHP_VERSION }}</strong>
            </p>
        </div>
    </div>
</body>
</html>
