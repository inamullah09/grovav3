# Grova — Deploy Guide

## 1. Database Setup
```bash
mysql -u root -p < schema.sql
```

## 2. Edit config.php — REQUIRED
```php
SITE_URL       → 'https://yourdomain.com'
GUMROAD_URL    → 'https://gumroad.com/l/yourproduct'
ADMIN_PASSWORD → 'strong-password-here'
API_SECRET     → 'random-secret-key-here'
DB_HOST        → 'localhost'
DB_NAME        → 'grova'
DB_USER        → 'your_db_user'
DB_PASS        → 'your_db_password'
PRODUCT_PRICE  → '$29'
```

## 3. Upload Files
Upload all files to your web root (public_html / htdocs).

## 4. Access
- Site:    https://yourdomain.com/
- Product: https://yourdomain.com/product.php
- Blog:    https://yourdomain.com/blog.php
- Admin:   https://yourdomain.com/admin.php

## n8n HTTP Request Node
```
Method:  POST
URL:     https://yourdomain.com/api/create-post.php
Headers: Authorization: Bearer YOUR_API_SECRET
Body:    {
           "title": "Post Title",
           "content": "<p>HTML here</p>",
           "excerpt": "Short summary",
           "category": "Guide",
           "read_time": 8,
           "published": true
         }
```

## Python
```python
import requests
requests.post('https://yourdomain.com/api/create-post.php',
  headers={'Authorization': 'Bearer YOUR_API_SECRET'},
  json={'title':'My Post','content':'<p>Content</p>','published':True})
```

## Requirements
- PHP 8.0+ with PDO + pdo_mysql
- MySQL 5.7+ or MariaDB 10.3+
- Apache with mod_rewrite (or Nginx equivalent)
