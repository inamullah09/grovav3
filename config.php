<?php
// ══════════════════════════════════════════════════════════════════════════════
// GROVA CONFIG — edit all values marked CHANGE before deploying
// ══════════════════════════════════════════════════════════════════════════════

// Site
define('SITE_NAME',    'Grova');
define('SITE_URL',     'https://grova.xyz');           // CHANGE — no trailing slash
define('SITE_TAGLINE', 'Digital Products for Builders');
define('SITE_EMAIL',   'hello@grova.xyz');             // CHANGE
define('OG_IMAGE',     SITE_URL.'/assets/og.jpg');   // CHANGE — 1200×630 image

// Product
define('PRODUCT_NAME',  'AutoFlow Blueprint');
define('PRODUCT_PRICE', '$29');
define('GUMROAD_URL',   'https://gumroad.com/l/CHANGE');  // CHANGE

// Auth
define('ADMIN_PASSWORD', '12345');   // CHANGE
define('API_SECRET',     '12345'); // CHANGE

// Database
define('DB_HOST', 'localhost');
define('DB_PORT', '3306');
define('DB_NAME', 'grova_db');        // matches created DB
define('DB_USER', 'grova_user');      // same
define('DB_PASS', 'GrovaRoot@2026!');     // matches created password

// Blog
define('POSTS_PER_PAGE', 12);

// ── PDO singleton ─────────────────────────────────────────────────────────────
function db(): PDO {
    static $pdo;
    if (!$pdo) $pdo = new PDO(
        'mysql:host='.DB_HOST.';port='.DB_PORT.';dbname='.DB_NAME.';charset=utf8mb4',
        DB_USER, DB_PASS,
        [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
         PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC,
         PDO::ATTR_EMULATE_PREPARES=>false] 
    );
    return $pdo;
}

// ── Post helpers ──────────────────────────────────────────────────────────────
function getPosts(int $page=1, string $cat='', string $tag=''): array {
    $offset = ($page-1)*POSTS_PER_PAGE;
    $where = ['published=1'];
    $params = [];
    if ($cat) { $where[] = 'category=?'; $params[] = $cat; }
    if ($tag) { $where[] = 'JSON_CONTAINS(tags, JSON_QUOTE(?))'; $params[] = $tag; }
    $sql = 'SELECT id,title,slug,excerpt,category,tags,featured_image,read_time,views,created_at
            FROM posts WHERE '.implode(' AND ',$where).'
            ORDER BY created_at DESC LIMIT '.POSTS_PER_PAGE.' OFFSET '.$offset;
    $s = db()->prepare($sql); $s->execute($params); return $s->fetchAll();
}
function countPosts(string $cat='', string $tag=''): int {
    $where = ['published=1']; $params = [];
    if ($cat) { $where[] = 'category=?'; $params[] = $cat; }
    if ($tag) { $where[] = 'JSON_CONTAINS(tags, JSON_QUOTE(?))'; $params[] = $tag; }
    $s = db()->prepare('SELECT COUNT(*) FROM posts WHERE '.implode(' AND ',$where));
    $s->execute($params); return (int)$s->fetchColumn();
}
function getPost(string $slug): ?array {
    $s = db()->prepare('SELECT * FROM posts WHERE slug=? LIMIT 1');
    $s->execute([$slug]); return $s->fetch() ?: null;
}
function getRelatedPosts(int $id, string $cat, int $limit=3): array {
    $s = db()->prepare('SELECT id,title,slug,excerpt,category,read_time,created_at FROM posts WHERE published=1 AND id!=? AND category=? ORDER BY created_at DESC LIMIT ?');
    $s->execute([$id,$cat,$limit]); return $s->fetchAll();
}
function getCategories(): array {
    return db()->query('SELECT category, COUNT(*) cnt FROM posts WHERE published=1 GROUP BY category ORDER BY cnt DESC')->fetchAll();
}
function savePost(array $d): string {
    $slug = trim($d['slug']) ? slugify($d['slug']) : slugify($d['title']);
    $tags = is_array($d['tags']??null) ? json_encode($d['tags'])
          : json_encode(array_values(array_filter(array_map('trim',explode(',', $d['tags']??'')))));
    $fields = ['title','slug','excerpt','content','meta_title','meta_desc',
               'category','featured_image','read_time','published','schema_type'];
    $row = [];
    foreach ($fields as $f) $row[$f] = $d[$f] ?? ($f==='slug'?$slug:($f==='schema_type'?'Article':($f==='read_time'?5:($f==='published'?0:''))));
    $row['slug']       = $slug;
    $row['meta_title'] = $row['meta_title'] ?: $row['title'];
    $row['meta_desc']  = $row['meta_desc']  ?: $row['excerpt'];

    $existing = getPost($slug);
    if ($existing) {
        $set = implode(',', array_map(fn($f)=>"$f=?", $fields));
        $set .= ',tags=?,updated_at=NOW()';
        db()->prepare("UPDATE posts SET $set WHERE slug=?")
            ->execute([...array_values($row), $tags, $slug]);
    } else {
        $cols = implode(',', $fields).',tags';
        $ph   = implode(',', array_fill(0, count($fields)+1, '?'));
        db()->prepare("INSERT INTO posts ($cols) VALUES ($ph)")
            ->execute([...array_values($row), $tags]);
    }
    return $slug;
}
function deletePost(int $id): void {
    db()->prepare('DELETE FROM posts WHERE id=?')->execute([$id]);
}

// ── Utilities ─────────────────────────────────────────────────────────────────
function slugify(string $t): string { return trim(preg_replace('/[^a-z0-9]+/','-',strtolower($t)),'-'); }
function e(string $s): string { return htmlspecialchars($s,ENT_QUOTES|ENT_SUBSTITUTE,'UTF-8'); }
function timeAgo(string $ts): string {
    $d=time()-strtotime($ts);
    return $d<60?'just now':($d<3600?floor($d/60).'m ago':($d<86400?floor($d/3600).'h ago':date('M j, Y',strtotime($ts))));
}
function readingTime(string $html): int {
    return max(1, (int)ceil(str_word_count(strip_tags($html))/200));
}
function currentUrl(): string {
    $scheme = (!empty($_SERVER['HTTPS'])&&$_SERVER['HTTPS']!='off')?'https':'http';
    return $scheme.'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
}
function paginateUrl(int $page, string $extra=''): string {
    $base = strtok($_SERVER['REQUEST_URI'],'?');
    $q    = array_filter(array_merge($_GET,['page'=>$page>1?$page:null]));
    unset($q['page']); if ($page>1) $q['page']=$page;
    return $base.($q?'?'.http_build_query($q):'');
}

// ── SEO / Head ─────────────────────────────────────────────────────────────────
function seoHead(array $seo=[]): void {
    $title    = e(($seo['title']??'') ? $seo['title'].' · '.SITE_NAME : SITE_NAME.' — '.SITE_TAGLINE);
    $desc     = e($seo['desc']    ?? 'Premium automation workflows, templates and digital tools for builders and operators.');
    $url      = e($seo['url']     ?? currentUrl());
    $image    = e($seo['image']   ?? OG_IMAGE);
    $type     = $seo['og_type']   ?? 'website';
    $schema   = $seo['schema']    ?? '';
    $canon    = e($seo['canonical'] ?? SITE_URL.parse_url(currentUrl(),PHP_URL_PATH));
    echo <<<HTML
    <meta name="description" content="{$desc}"/>
    <link rel="canonical" href="{$canon}"/>
    <meta property="og:type" content="{$type}"/>
    <meta property="og:title" content="{$title}"/>
    <meta property="og:description" content="{$desc}"/>
    <meta property="og:url" content="{$url}"/>
    <meta property="og:image" content="{$image}"/>
    <meta property="og:site_name" content="Grova"/>
    <meta name="twitter:card" content="summary_large_image"/>
    <meta name="twitter:title" content="{$title}"/>
    <meta name="twitter:description" content="{$desc}"/>
    <meta name="twitter:image" content="{$image}"/>
HTML;
    if ($schema) echo '<script type="application/ld+json">'."\n".$schema."\n</script>\n";
}

// ── HTML document open (head + nav) ──────────────────────────────────────────
function pageOpen(string $title, array $seo=[], string $active=''): void {
    $seo['title'] = $seo['title'] ?? $title;
    $t = e($title ? $title.' · '.SITE_NAME : SITE_NAME.' — '.SITE_TAGLINE);
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width,initial-scale=1"/>
<title><?=$t?></title>
<script src="https://cdn.tailwindcss.com"></script>
<script>
tailwind.config = {
  theme: { extend: {
    fontFamily: {
      display: ['"Bricolage Grotesque"','system-ui','sans-serif'],
      body:    ['"Newsreader"','Georgia','serif'],
      ui:      ['"Instrument Sans"','system-ui','sans-serif'],
      mono:    ['"JetBrains Mono"','monospace'],
    },
    colors: {
      ink:     '#0B0B0F',
      white:   '#FFFFFF',
      cream:   '#F7F6F2',
      stone:   '#EEEAE2',
      border:  '#DDD9D0',
      muted:   '#747474',
      accent:  '#2C42FF',
      'accent-dim': '#EEF0FF',
      success: '#15803D',
      warning: '#B45309',
    },
    maxWidth: { site: '1200px' },
  }}
}
</script>
<link rel="preconnect" href="https://fonts.googleapis.com"/>
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
<link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,400;12..96,500;12..96,600;12..96,700&family=Newsreader:ital,opsz,wght@0,6..72,400;0,6..72,500;1,6..72,400;1,6..72,500&family=Instrument+Sans:wght@400;500;600&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet"/>
<style>
*{box-sizing:border-box;margin:0;padding:0;}
html{scroll-behavior:smooth;}
body{-webkit-font-smoothing:antialiased;background:#F7F6F2;color:#0B0B0F;font-family:'Instrument Sans',system-ui,sans-serif;line-height:1.6;}
/* Ticker */
.ticker-track{overflow:hidden;}
.ticker-reel{display:flex;width:max-content;animation:ticker 45s linear infinite;}
@keyframes ticker{to{transform:translateX(-50%);}}
/* Prose */
.prose{font-family:'Newsreader',Georgia,serif;font-size:1.0625rem;line-height:1.85;color:#2a2a2a;}
.prose h2{font-family:'Bricolage Grotesque',sans-serif;font-size:1.5rem;font-weight:600;line-height:1.2;margin:2.5rem 0 .75rem;color:#0B0B0F;}
.prose h3{font-family:'Bricolage Grotesque',sans-serif;font-size:1.125rem;font-weight:600;margin:2rem 0 .5rem;color:#0B0B0F;}
.prose p{margin-bottom:1.4rem;}
.prose ul,.prose ol{margin:0 0 1.4rem 1.5rem;}
.prose li{margin-bottom:.4rem;}
.prose blockquote{border-left:2px solid #2C42FF;padding:.5rem 1.25rem;margin:1.75rem 0;background:#F0F2FF;}
.prose blockquote p{color:#1a1a1a;font-style:italic;margin:0;}
.prose strong{font-weight:600;color:#0B0B0F;}
.prose a{color:#2C42FF;text-decoration:none;border-bottom:1px solid #c7ccff;}
.prose a:hover{border-color:#2C42FF;}
.prose code{font-family:'JetBrains Mono',monospace;font-size:.85em;background:#EEEAE2;padding:.15em .4em;border-radius:2px;}
.prose pre{background:#0B0B0F;color:#e8e8e8;padding:1.25rem;overflow-x:auto;margin:1.5rem 0;font-family:'JetBrains Mono',monospace;font-size:.85rem;}
/* Nav active */
.nav-a{font-size:.875rem;font-weight:500;color:#747474;transition:color .12s;font-family:'Instrument Sans',sans-serif;}
.nav-a:hover,.nav-a.on{color:#0B0B0F;}
/* Fade in */
@keyframes fadeUp{from{opacity:0;transform:translateY(12px);}to{opacity:1;transform:translateY(0);}}
.fade-up{animation:fadeUp .5s ease both;}
</style>
<?php seoHead($seo); ?>
</head>
<body>
<?php echoNav($active); ?>
<?php
}

function echoNav(string $active=''): void {
    $p = PRODUCT_PRICE; $pu = PRODUCT_NAME;
    $links = ['/index.php'=>'Home','/product.php'=>'Blueprint','/blog.php'=>'Writing'];
    $nav='';
    foreach($links as $href=>$label)
        $nav .= '<a href="'.$href.'" class="nav-a'.($active===$label?' on':'').'">'.$label.'</a>';
?>
<header class="fixed top-0 inset-x-0 z-50 bg-cream/95 backdrop-blur-sm border-b border-border" role="banner">
  <div class="max-w-site mx-auto px-5 lg:px-8 h-14 flex items-center justify-between gap-6">
    <a href="/index.php" class="font-display font-bold text-xl text-ink tracking-tight shrink-0" aria-label="Grova home">
      Grova<span class="text-accent">.</span>
    </a>
    <nav class="hidden md:flex items-center gap-7" aria-label="Main navigation">
      <?=$nav?>
    </nav>
    <div class="flex items-center gap-3">
      <a href="/product.php" class="hidden md:inline-flex bg-accent text-white text-xs font-ui font-semibold px-4 py-2 hover:bg-ink transition-colors whitespace-nowrap">
        <?=$pu?> — <?=$p?>
      </a>
      <button id="mob-btn" class="md:hidden p-2 text-ink" aria-label="Open menu" aria-expanded="false" aria-controls="mob-nav">
        <svg id="mob-ico" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
        <svg id="mob-ico-x" class="hidden" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><line x1="4" y1="4" x2="20" y2="20"/><line x1="20" y1="4" x2="4" y2="20"/></svg>
      </button>
    </div>
  </div>
  <div id="mob-nav" class="hidden md:hidden border-t border-border bg-cream" role="navigation" aria-label="Mobile navigation">
    <div class="max-w-site mx-auto px-5 py-4 flex flex-col gap-0.5">
      <?php foreach(['/index.php'=>'Home','/product.php'=>'Blueprint','/blog.php'=>'Writing'] as $h=>$l): ?>
      <a href="<?=$h?>" class="py-3 text-sm font-ui font-medium text-muted hover:text-ink border-b border-border last:border-0 transition-colors"><?=$l?></a>
      <?php endforeach; ?>
      <a href="/product.php" class="mt-3 bg-accent text-white text-sm font-ui font-semibold text-center py-3 hover:bg-ink transition-colors">
        <?=$pu?> — <?=$p?>
      </a>
    </div>
  </div>
</header>
<script>
(function(){
  var btn=document.getElementById('mob-btn'),
      nav=document.getElementById('mob-nav'),
      ico=document.getElementById('mob-ico'),
      icox=document.getElementById('mob-ico-x');
  btn.addEventListener('click',function(){
    var open=nav.classList.toggle('hidden');
    btn.setAttribute('aria-expanded',!open);
    ico.classList.toggle('hidden'); icox.classList.toggle('hidden');
  });
})();
</script>
<?php
}

function pageClose(): void {
    $y = date('Y');
?>
<footer class="border-t border-border bg-cream mt-0" role="contentinfo">
  <div class="max-w-site mx-auto px-5 lg:px-8 py-14 grid grid-cols-2 md:grid-cols-4 gap-8">
    <div class="col-span-2 md:col-span-1">
      <a href="/index.php" class="font-display font-bold text-xl text-ink">Grova<span class="text-accent">.</span></a>
      <p class="text-muted text-sm mt-3 leading-relaxed font-ui max-w-[200px]">Premium digital products and workflows for builders and operators.</p>
    </div>
    <div>
      <p class="font-mono text-[.65rem] text-muted uppercase tracking-widest mb-4">Products</p>
      <a href="/product.php" class="block text-sm text-muted hover:text-ink mb-2 font-ui transition-colors">AutoFlow Blueprint</a>
      <a href="/product.php" class="block text-sm text-muted hover:text-ink mb-2 font-ui transition-colors">1,000 Workflows</a>
    </div>
    <div>
      <p class="font-mono text-[.65rem] text-muted uppercase tracking-widest mb-4">Resources</p>
      <a href="/blog.php" class="block text-sm text-muted hover:text-ink mb-2 font-ui transition-colors">Writing</a>
      <a href="/sitemap.php" class="block text-sm text-muted hover:text-ink mb-2 font-ui transition-colors">Sitemap</a>
    </div>
    <div>
      <p class="font-mono text-[.65rem] text-muted uppercase tracking-widest mb-4">Company</p>
      <a href="/index.php#about" class="block text-sm text-muted hover:text-ink mb-2 font-ui transition-colors">About</a>
      <a href="mailto:<?=SITE_EMAIL?>" class="block text-sm text-muted hover:text-ink mb-2 font-ui transition-colors">Contact</a>
    </div>
  </div>
  <div class="border-t border-border">
    <div class="max-w-site mx-auto px-5 lg:px-8 py-4 flex flex-wrap justify-between gap-3">
      <p class="text-xs text-muted font-mono">© <?=$y?> Grova. All rights reserved.</p>
      <p class="text-xs text-muted font-mono">grova.xyz</p>
    </div>
  </div>
</footer>
</body></html>
<?php
}

// ── Organization schema (sitewide) ────────────────────────────────────────────
function orgSchema(): string {
    return json_encode(['@context'=>'https://schema.org','@type'=>'Organization',
        'name'=>SITE_NAME,'url'=>SITE_URL,'email'=>SITE_EMAIL,
        'logo'=>['@type'=>'ImageObject','url'=>SITE_URL.'/assets/logo.png']],
        JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);
}
