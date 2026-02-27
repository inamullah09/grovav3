<?php
// ── SETTINGS — edit before deploy ─────────────────────────────────────────
define('SITE_NAME',        'Grova');
define('SITE_URL',         'https://grova.xyz');
define('GUMROAD_URL',      'https://YOUR_GUMROAD_LINK_HERE');
define('PRODUCT_PRICE',    '$29');
define('ADMIN_PASSWORD',   '12345');   // CHANGE
define('API_SECRET',       'grova12345'); // CHANGE
// ── MYSQL ─────────────────────────────────────────────────────────────────
define('DB_HOST', 'localhost');
define('DB_NAME', 'grova');
define('DB_USER', 'root');     // CHANGE
define('DB_PASS', '12345');         // CHANGE

// ── DB CONNECTION ─────────────────────────────────────────────────────────
function db(): PDO {
    static $pdo;
    if (!$pdo) {
        $pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8mb4',
            DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
    }
    return $pdo;
}

// ── HELPERS ───────────────────────────────────────────────────────────────
function getAllPosts(bool $pubOnly = true): array {
    $sql = 'SELECT * FROM posts' . ($pubOnly ? ' WHERE published=1' : '') . ' ORDER BY created_at DESC';
    return db()->query($sql)->fetchAll();
}
function getPost(string $slug): ?array {
    $s = db()->prepare('SELECT * FROM posts WHERE slug=? LIMIT 1');
    $s->execute([$slug]);
    return $s->fetch() ?: null;
}
function savePost(array $d): string {
    $slug = slugify($d['slug'] ?: $d['title']);
    $tags = json_encode(array_filter(array_map('trim', explode(',', $d['tags'] ?? ''))));
    $existing = getPost($slug);
    if ($existing) {
        db()->prepare('UPDATE posts SET title=?,excerpt=?,category=?,content=?,tags=?,read_time=?,published=?,updated_at=NOW() WHERE slug=?')
           ->execute([$d['title'],$d['excerpt'],$d['category'],$d['content'],$tags,(int)$d['read_time'],(int)$d['published'],$slug]);
    } else {
        db()->prepare('INSERT INTO posts (title,slug,excerpt,category,content,tags,read_time,published) VALUES (?,?,?,?,?,?,?,?)')
           ->execute([$d['title'],$slug,$d['excerpt'],$d['category'],$d['content'],$tags,(int)$d['read_time'],(int)$d['published']]);
    }
    return $slug;
}
function deletePost(int $id): void { db()->prepare('DELETE FROM posts WHERE id=?')->execute([$id]); }
function slugify(string $t): string { return trim(preg_replace('/[^a-z0-9]+/','-',strtolower($t)),'-'); }
function timeAgo(string $ts): string {
    $d = time()-strtotime($ts);
    if($d<60) return 'just now';
    if($d<3600) return floor($d/60).'m ago';
    if($d<86400) return floor($d/3600).'h ago';
    return date('M j, Y',strtotime($ts));
}
function adminCheck(): void { session_start(); if(empty($_SESSION['admin'])){header('Location:/admin.php');exit;} }
function e(string $s): string { return htmlspecialchars($s, ENT_QUOTES); }

// ── SHARED HEAD ───────────────────────────────────────────────────────────
function htmlHead(string $title, string $desc=''): void {
    $desc = $desc ?: 'Premium automation blueprints. Zero coding. First sale in 7 days.';
    echo <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width,initial-scale=1"/>
<meta name="description" content="{$desc}"/>
<title>{$title} · Grova</title>
<script src="https://cdn.tailwindcss.com"></script>
<script>
tailwind.config={theme:{extend:{
  fontFamily:{
    display:['"Fraunces"','Georgia','serif'],
    body:['"Plus Jakarta Sans"','sans-serif'],
    mono:['"JetBrains Mono"','monospace'],
  },
  colors:{
    brand:'#1a1a2e',
    accent:'#f5c842',
    lime:'#b8ff3c',
    surface:'#f8f7f4',
    border:'#e8e6e0',
    muted:'#6b6b6b',
  }
}}}
</script>
<link rel="preconnect" href="https://fonts.googleapis.com"/>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,300;0,9..144,600;1,9..144,400&family=Plus+Jakarta+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet"/>
<style>
body{-webkit-font-smoothing:antialiased;background:#fff;color:#1a1a1a;font-family:'Plus Jakarta Sans',sans-serif;}
.ticker-inner{display:flex;width:max-content;animation:tick 40s linear infinite;}
@keyframes tick{to{transform:translateX(-50%);}}
.prose h2{font-family:'Fraunces',serif;font-size:1.6rem;font-weight:600;margin:2.25rem 0 .75rem;color:#1a1a1a;}
.prose h3{font-size:1.05rem;font-weight:700;margin:1.75rem 0 .5rem;}
.prose p{margin-bottom:1.35rem;line-height:1.85;color:#3a3a3a;font-size:.97rem;}
.prose ul{margin:1rem 0 1.35rem 1.5rem;list-style:disc;}
.prose li{margin-bottom:.5rem;line-height:1.75;color:#3a3a3a;font-size:.97rem;}
.prose blockquote{border-left:3px solid #f5c842;padding:.75rem 1.5rem;margin:1.75rem 0;background:#fdfcf7;}
.prose blockquote p{color:#1a1a1a;font-style:italic;margin:0;}
.prose strong{color:#1a1a1a;font-weight:700;}
.prose a{color:#1a1a1a;border-bottom:1px solid #f5c842;}
.nav-link{font-size:.875rem;font-weight:500;color:#6b6b6b;transition:color .15s;}
.nav-link:hover,.nav-link.active{color:#1a1a1a;}
</style>
</head>
<body>
HTML;
}

// ── SHARED NAV ────────────────────────────────────────────────────────────
function htmlNav(string $active=''): void {
    $p = PRODUCT_PRICE;
    echo <<<HTML
<nav class="fixed top-0 inset-x-0 z-50 h-14 bg-white/96 backdrop-blur-md border-b border-border flex items-center justify-between px-5 md:px-10">
  <a href="/index.php" class="font-display text-[1.25rem] font-semibold tracking-tight text-brand">
    Gro<span class="bg-lime px-0.5 text-brand">v</span>a
  </a>
  <!-- desktop links -->
  <div class="hidden md:flex items-center gap-7">
    <a href="/index.php#about" class="nav-link <?= $active==='About'?'active':'' ?>">About</a>
    <a href="/product.php" class="nav-link <?= $active==='Product'?'active':'' ?>">Blueprint</a>
    <a href="/blog.php" class="nav-link <?= $active==='Blog'?'active':'' ?>">Writing</a>
  </div>
  <div class="flex items-center gap-3">
    <a href="/product.php" class="hidden md:block bg-brand text-white text-xs font-semibold px-4 py-2 hover:bg-accent hover:text-brand transition-colors">
      AutoFlow — {$p}
    </a>
    <!-- mobile hamburger -->
    <button onclick="document.getElementById('mob').classList.toggle('hidden')" class="md:hidden p-2 text-brand" aria-label="Menu">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
      </svg>
    </button>
  </div>
</nav>
<!-- mobile drawer -->
<div id="mob" class="hidden fixed top-14 inset-x-0 z-40 bg-white border-b border-border shadow-lg md:hidden">
  <div class="flex flex-col px-5 py-4 gap-1">
    <a href="/index.php#about" class="py-3 text-sm font-medium text-muted hover:text-brand border-b border-border">About</a>
    <a href="/product.php"     class="py-3 text-sm font-medium text-muted hover:text-brand border-b border-border">Blueprint</a>
    <a href="/blog.php"        class="py-3 text-sm font-medium text-muted hover:text-brand">Writing</a>
    <a href="/product.php" class="mt-3 block bg-brand text-white text-sm font-semibold text-center py-3">Get AutoFlow Blueprint — {$p}</a>
  </div>
</div>
HTML;
}

// ── SHARED FOOTER ─────────────────────────────────────────────────────────
function htmlFoot(): void {
    $y=date('Y'); $p=PRODUCT_PRICE;
    echo <<<HTML
<footer class="border-t border-border bg-surface">
  <div class="max-w-5xl mx-auto px-5 md:px-10 py-12 grid grid-cols-2 md:grid-cols-4 gap-8">
    <div class="col-span-2 md:col-span-1">
      <a href="/index.php" class="font-display text-lg font-semibold text-brand">Gro<span class="bg-lime px-0.5">v</span>a</a>
      <p class="text-muted text-sm mt-2 leading-relaxed">Automation blueprints for people who build things for a living.</p>
    </div>
    <div>
      <p class="font-mono text-xs text-muted uppercase tracking-widest mb-4">Product</p>
      <a href="/product.php" class="block text-sm text-muted hover:text-brand mb-2">AutoFlow Blueprint</a>
    </div>
    <div>
      <p class="font-mono text-xs text-muted uppercase tracking-widest mb-4">Read</p>
      <a href="/blog.php" class="block text-sm text-muted hover:text-brand mb-2">Writing</a>
    </div>
    <div>
      <p class="font-mono text-xs text-muted uppercase tracking-widest mb-4">Company</p>
      <a href="/index.php#about" class="block text-sm text-muted hover:text-brand mb-2">About</a>
    </div>
  </div>
  <div class="border-t border-border px-5 md:px-10 py-4 flex justify-between text-xs text-muted/70">
    <span>© {$y} Grova</span><span>grova.xyz</span>
  </div>
</footer></body></html>
HTML;
}
