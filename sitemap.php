<?php
require_once __DIR__.'/config.php';

header('Content-Type: application/xml; charset=utf-8');
header('X-Robots-Tag: noindex');

$posts = db()->query('SELECT slug, updated_at, created_at FROM posts WHERE published=1 ORDER BY created_at DESC')->fetchAll();

echo '<?xml version="1.0" encoding="UTF-8"?>'."\n";
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:news="http://www.google.com/schemas/sitemap-news/0.9"
        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">

  <!-- Static pages -->
  <url>
    <loc><?=SITE_URL?>/</loc>
    <changefreq>weekly</changefreq>
    <priority>1.0</priority>
  </url>
  <url>
    <loc><?=SITE_URL?>/product.php</loc>
    <changefreq>monthly</changefreq>
    <priority>0.9</priority>
  </url>
  <url>
    <loc><?=SITE_URL?>/blog.php</loc>
    <changefreq>daily</changefreq>
    <priority>0.8</priority>
  </url>

  <!-- Blog posts -->
  <?php foreach($posts as $p): ?>
  <url>
    <loc><?=SITE_URL?>/post.php?slug=<?=htmlspecialchars($p['slug'])?></loc>
    <lastmod><?=date('Y-m-d', strtotime($p['updated_at']?:$p['created_at']))?></lastmod>
    <changefreq>monthly</changefreq>
    <priority>0.7</priority>
  </url>
  <?php endforeach; ?>

</urlset>
