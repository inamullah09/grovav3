<?php
require_once __DIR__.'/config.php';

$slug = preg_replace('/[^a-z0-9\-]/', '', $_GET['slug'] ?? '');
$post = $slug ? getPost($slug) : null;

if (!$post || !$post['published']) {
    http_response_code(404);
    pageOpen('Page Not Found', ['title'=>'404 — Page Not Found']);
    echo '<main class="pt-14 flex items-center justify-center min-h-[60vh]">
    <div class="text-center px-5">
      <p class="font-mono text-[.65rem] text-muted uppercase tracking-widest mb-4">404</p>
      <h1 class="font-display font-bold text-4xl text-ink mb-4">Page not found</h1>
      <p class="font-ui text-muted mb-6">This post doesn\'t exist or was removed.</p>
      <a href="/blog.php" class="font-ui font-semibold text-sm text-accent hover:text-ink transition-colors">← Back to Writing</a>
    </div></main>';
    pageClose(); exit;
}

// increment views
db()->prepare('UPDATE posts SET views=views+1 WHERE slug=?')->execute([$slug]);

$related = getRelatedPosts($post['id'], $post['category']??'');
$tags    = json_decode($post['tags']??'[]', true) ?: [];
$rt      = $post['read_time'] ?: readingTime($post['content']??'');
$imgUrl  = $post['featured_image'] ?: OG_IMAGE;

// Article JSON-LD schema
$articleSchema = json_encode([
    '@context' => 'https://schema.org',
    '@type'    => $post['schema_type'] ?: 'Article',
    'headline' => $post['meta_title'] ?: $post['title'],
    'description' => $post['meta_desc'] ?: $post['excerpt'],
    'image'    => $imgUrl,
    'url'      => SITE_URL.'/post.php?slug='.$slug,
    'datePublished' => $post['created_at'],
    'dateModified'  => $post['updated_at'],
    'author'   => ['@type'=>'Organization','name'=>SITE_NAME,'url'=>SITE_URL],
    'publisher'=> ['@type'=>'Organization','name'=>SITE_NAME,'url'=>SITE_URL,
                   'logo'=>['@type'=>'ImageObject','url'=>SITE_URL.'/assets/logo.png']],
    'mainEntityOfPage' => ['@type'=>'WebPage','@id'=>SITE_URL.'/post.php?slug='.$slug],
    'keywords' => implode(', ',$tags),
    'articleSection' => $post['category'],
], JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);

// Breadcrumb schema
$breadcrumbSchema = json_encode([
    '@context' => 'https://schema.org',
    '@type'    => 'BreadcrumbList',
    'itemListElement' => [
        ['@type'=>'ListItem','position'=>1,'name'=>'Home','item'=>SITE_URL],
        ['@type'=>'ListItem','position'=>2,'name'=>'Writing','item'=>SITE_URL.'/blog.php'],
        ['@type'=>'ListItem','position'=>3,'name'=>$post['title'],'item'=>SITE_URL.'/post.php?slug='.$slug],
    ],
], JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);

pageOpen($post['meta_title']?:$post['title'], [
    'title'    => $post['meta_title'] ?: $post['title'],
    'desc'     => $post['meta_desc']  ?: $post['excerpt'],
    'og_type'  => 'article',
    'image'    => $imgUrl,
    'url'      => SITE_URL.'/post.php?slug='.$slug,
    'canonical'=> SITE_URL.'/post.php?slug='.$slug,
    'schema'   => $articleSchema."\n".$breadcrumbSchema,
], 'Writing');
?>

<main id="main-content" class="pt-14">

<!-- Breadcrumb -->
<div class="border-b border-border bg-cream">
  <div class="max-w-site mx-auto px-5 lg:px-8">
    <nav aria-label="Breadcrumb" class="py-3 flex items-center gap-2 text-xs font-mono text-muted">
      <a href="/index.php" class="hover:text-ink transition-colors">Home</a>
      <span class="text-border">/</span>
      <a href="/blog.php" class="hover:text-ink transition-colors">Writing</a>
      <span class="text-border">/</span>
      <?php if($post['category']): ?>
      <a href="/blog.php?cat=<?=urlencode($post['category'])?>" class="hover:text-ink transition-colors"><?=e($post['category'])?></a>
      <span class="text-border">/</span>
      <?php endif; ?>
      <span class="text-muted/60 truncate max-w-[200px]"><?=e($post['title'])?></span>
    </nav>
  </div>
</div>

<!-- Post header -->
<header class="border-b border-border">
  <div class="max-w-2xl mx-auto px-5 lg:px-0 py-12">
    <div class="flex items-center gap-3 mb-5 flex-wrap">
      <a href="/blog.php?cat=<?=urlencode($post['category']??'')?>"
         class="font-mono text-[.6rem] text-white bg-ink uppercase tracking-widest px-2.5 py-1 hover:bg-accent transition-colors">
        <?=e($post['category']??'Article')?>
      </a>
      <span class="font-mono text-[.6rem] text-muted uppercase tracking-widest"><?=$rt?> min read</span>
      <span class="font-mono text-[.6rem] text-muted uppercase tracking-widest"><?=date('M j, Y',strtotime($post['created_at']))?></span>
      <span class="font-mono text-[.6rem] text-muted/40 uppercase tracking-widest"><?=$post['views']?> views</span>
    </div>
    <h1 class="font-display font-bold text-[clamp(1.75rem,4vw,3rem)] leading-tight text-ink mb-5">
      <?=e($post['title'])?>
    </h1>
    <?php if($post['excerpt']): ?>
    <p class="font-body text-lg text-muted leading-relaxed"><?=e($post['excerpt'])?></p>
    <?php endif; ?>
    <?php if($tags): ?>
    <div class="flex flex-wrap gap-2 mt-5">
      <?php foreach($tags as $t): ?>
      <a href="/blog.php?tag=<?=urlencode($t)?>"
         class="font-mono text-[.6rem] border border-border px-2.5 py-1 text-muted hover:text-ink hover:border-ink transition-colors">
        <?=e($t)?>
      </a>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>
</header>

<!-- Post content -->
<div class="max-w-2xl mx-auto px-5 lg:px-0 py-12">
  <article class="prose" aria-label="Post content">
    <?=$post['content']?>
  </article>
</div>

<!-- Inline CTA -->
<div class="border-t border-b border-border bg-accent-dim">
  <div class="max-w-2xl mx-auto px-5 lg:px-0 py-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-5">
    <div>
      <p class="font-mono text-[.6rem] text-accent uppercase tracking-widest mb-2">Ready to put this into practice?</p>
      <p class="font-display font-semibold text-lg text-ink">AutoFlow Blueprint — the complete system.</p>
    </div>
    <a href="/product.php" class="shrink-0 bg-accent text-white font-ui font-semibold text-sm px-6 py-3 hover:bg-ink transition-colors">
      Get Blueprint — <?=PRODUCT_PRICE?>
    </a>
  </div>
</div>

<!-- Related posts -->
<?php if($related): ?>
<div class="border-b border-border">
  <div class="max-w-site mx-auto px-5 lg:px-8 py-10">
    <p class="font-mono text-[.65rem] text-muted uppercase tracking-widest mb-7">More in <?=e($post['category']??'Writing')?></p>
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-px bg-border">
      <?php foreach($related as $r): ?>
      <article class="bg-cream p-6 hover:bg-white transition-colors">
        <p class="font-mono text-[.6rem] text-muted uppercase tracking-widest mb-3"><?=e($r['category']??'')?></p>
        <h3 class="font-display font-semibold text-base leading-snug text-ink mb-3">
          <a href="/post.php?slug=<?=e($r['slug'])?>" class="hover:text-accent transition-colors"><?=e($r['title'])?></a>
        </h3>
        <a href="/post.php?slug=<?=e($r['slug'])?>" class="font-mono text-[.65rem] text-muted uppercase tracking-widest hover:text-accent transition-colors">
          Read — <?=$r['read_time']?> min →
        </a>
      </article>
      <?php endforeach; ?>
    </div>
  </div>
</div>
<?php endif; ?>

</main>
<?php pageClose(); ?>
