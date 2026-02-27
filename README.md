# Grova — Production Deploy Guide

## Prerequisites (Ubuntu 22.04 / 24.04)
```bash
sudo apt update && sudo apt install -y nginx php8.3-fpm php8.3-mysql php8.3-mbstring php8.3-xml mysql-server certbot python3-certbot-nginx
```

## 1. MySQL Setup
```bash
sudo mysql -u root -p
CREATE USER 'grova_user'@'localhost' IDENTIFIED BY 'strong_password';
GRANT ALL PRIVILEGES ON grova.* TO 'grova_user'@'localhost';
FLUSH PRIVILEGES; EXIT;

mysql -u grova_user -p < schema.sql
```

## 2. Upload Files
```bash
sudo mkdir -p /var/www/grova
sudo rsync -avz ./grova/ user@yourserver:/var/www/grova/
# OR via FTP/SFTP to /var/www/grova/
```

## 3. Permissions
```bash
sudo chown -R www-data:www-data /var/www/grova
sudo chmod 644 /var/www/grova/*.php
sudo chmod 600 /var/www/grova/config.php
```

## 4. Edit config.php — REQUIRED
```php
define('SITE_URL',         'https://grova.io');
define('GUMROAD_URL',      'https://gumroad.com/l/YOUR_LINK');
define('SITE_EMAIL',       'hello@grova.io');
define('ADMIN_PASSWORD',   'very-strong-password');
define('API_SECRET',       'random-64-char-secret');
define('DB_USER',          'grova_user');
define('DB_PASS',          'strong_password');
```

## 5. Nginx Config
```bash
sudo cp /var/www/grova/nginx.conf /etc/nginx/sites-available/grova.io
sudo ln -s /etc/nginx/sites-available/grova.io /etc/nginx/sites-enabled/
sudo nginx -t && sudo systemctl reload nginx
```

## 6. SSL Certificate
```bash
sudo certbot --nginx -d grova.io -d www.grova.io
sudo systemctl reload nginx
```

## 7. Verify
- https://grova.io → Homepage
- https://grova.io/product.php → Sales page
- https://grova.io/blog.php → Blog
- https://grova.io/admin.php → Admin (use your ADMIN_PASSWORD)
- https://grova.io/sitemap.php → XML sitemap
- https://grova.io/robots.txt → Robots file

---

## API Usage (n8n / Python)

### Full Post Object
```json
{
  "title":          "Your Post Title",
  "content":        "<p>HTML content here</p>",
  "excerpt":        "Short summary (155 chars for SEO)",
  "meta_title":     "SEO Title — Grova (optional)",
  "meta_desc":      "SEO meta description (optional)",
  "category":       "Guide",
  "tags":           ["n8n", "automation", "workflows"],
  "featured_image": "https://yourcdn.com/image.jpg",
  "read_time":      8,
  "published":      true,
  "schema_type":    "Article"
}
```

### n8n HTTP Request Node
```
Method:  POST
URL:     https://grova.io/api/create-post.php
Headers: Authorization: Bearer YOUR_API_SECRET
         Content-Type: application/json
Body:    (JSON object above)
```

### Python
```python
import requests

r = requests.post(
    'https://grova.io/api/create-post.php',
    headers={'Authorization': 'Bearer YOUR_API_SECRET'},
    json={
        'title': 'How to Automate Your Email Workflow',
        'content': '<p>HTML content...</p>',
        'excerpt': 'Short summary for cards and SEO.',
        'category': 'Tutorial',
        'tags': ['n8n', 'email', 'automation'],
        'read_time': 6,
        'published': True,
    }
)
print(r.json())  # {"ok": true, "slug": "how-to-automate-your-email-workflow", "url": "/post.php?slug=..."}
```

---

## File Structure
```
/var/www/grova/
├── config.php          All settings + DB helpers + shared HTML
├── index.php           Homepage (ecosystem positioning)
├── product.php         Sales page (full conversion page)
├── blog.php            Blog listing (pagination + category filter)
├── post.php            Single post (Article schema + SEO meta)
├── admin.php           Admin panel (password protected)
├── sitemap.php         Dynamic XML sitemap
├── robots.txt          Crawler rules
├── schema.sql          MySQL schema
├── nginx.conf          Nginx server block
├── api/
│   └── create-post.php n8n/Python automation endpoint
└── assets/
    └── og.jpg          Default OG image (1200×630 — ADD THIS)
```

## SEO Checklist
- [ ] Add og.jpg (1200×630px) to /assets/
- [ ] Submit sitemap to Google Search Console: https://grova.io/sitemap.php
- [ ] Verify site in Google Search Console
- [ ] Set up Google Analytics (add tracking script to config.php head)
- [ ] Test: https://developers.google.com/search/docs/appearance/structured-data
- [ ] Test: https://pagespeed.web.dev/
