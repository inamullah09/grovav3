# Grova — SQLite Edition · Deploy Guide

## Why SQLite?
Zero config. No DB server. The database is a single file (`data/grova.db`).
Created automatically on first page load. Perfect for VPS solo deployments.

## Prerequisites (Ubuntu 22.04 / 24.04)
```bash
sudo apt update && sudo apt install -y nginx php8.3-fpm php8.3-sqlite3 certbot python3-certbot-nginx
```

> **Only `php8.3-sqlite3` needed — no MySQL, no credentials, no setup.**

---

## Deploy Steps

### 1. Upload files
```bash
sudo mkdir -p /var/www/grova
rsync -avz ./grova/ user@yourserver:/var/www/grova/
```

### 2. Edit config.php — required
```php
define('SITE_URL',        'https://grova.io');
define('GUMROAD_URL',     'https://gumroad.com/l/YOUR_LINK');
define('SITE_EMAIL',      'hello@grova.io');
define('ADMIN_PASSWORD',  'very-strong-password');
define('API_SECRET',      'random-64-char-string');
define('DB_PATH',         __DIR__.'/data/grova.db');  // default is fine
```

### 3. Permissions
```bash
sudo chown -R www-data:www-data /var/www/grova
sudo chmod 755 /var/www/grova/data   # or mkdir if not created yet
sudo chmod 600 /var/www/grova/config.php
```

### 4. Nginx config
```bash
sudo cp /var/www/grova/nginx.conf /etc/nginx/sites-available/grova.io
# Edit: change root path to /var/www/grova
sudo ln -s /etc/nginx/sites-available/grova.io /etc/nginx/sites-enabled/
sudo nginx -t && sudo systemctl reload nginx
```

### 5. SSL
```bash
sudo certbot --nginx -d grova.io -d www.grova.io
```

### 6. First load
Visit https://grova.io — the SQLite DB file is created automatically at `data/grova.db`.

---

## Backups (simple)
```bash
# Backup DB — just copy the file
cp /var/www/grova/data/grova.db /backups/grova-$(date +%Y%m%d).db

# Cron backup daily at 3am
0 3 * * * cp /var/www/grova/data/grova.db /backups/grova-$(date +\%Y\%m\%d).db
```

---

## API (n8n / Python)

### POST /api/create-post.php
```
Authorization: Bearer YOUR_API_SECRET
Content-Type: application/json
```

```json
{
  "title":          "Post Title",
  "content":        "<p>HTML content</p>",
  "excerpt":        "Short summary",
  "meta_title":     "SEO Title (optional)",
  "meta_desc":      "SEO description (optional)",
  "category":       "Guide",
  "tags":           ["n8n", "automation"],
  "featured_image": "https://yourcdn.com/img.jpg",
  "read_time":      8,
  "published":      true,
  "schema_type":    "Article"
}
```

```python
import requests
r = requests.post('https://grova.io/api/create-post.php',
    headers={'Authorization': 'Bearer YOUR_API_SECRET'},
    json={'title':'My Post','content':'<p>Content</p>','published':True})
print(r.json())
```

---

## File Structure
```
/var/www/grova/
├── config.php       Settings + SQLite connection + all helpers
├── index.php        Homepage
├── product.php      Sales page
├── blog.php         Blog listing (pagination + category filter)
├── post.php         Single post (Article schema + SEO)
├── admin.php        Admin panel
├── sitemap.php      Dynamic XML sitemap
├── robots.txt       Crawler rules
├── schema.sql       Reference only — auto-created on first run
├── nginx.conf       Server block config
├── api/
│   └── create-post.php
├── data/
│   └── grova.db     ← SQLite file (auto-created, keep backed up)
└── assets/
    └── og.jpg       ← Add: 1200×630px OG image
```
