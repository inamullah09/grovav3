<?php
require_once __DIR__.'/config.php';

$page = max(1,(int)($_GET['page']??1));
$cat  = trim($_GET['cat']??'');
$tag  = trim($_GET['tag']??'');

$posts    = getPosts($page,$cat,$tag);
$total    = countPosts($cat,$tag);
$pages    = (int)ceil($total/POSTS_PER_PAGE);
$cats     = getCategories();
$featured = (!$cat&&!$tag&&$page===1&&$posts) ? array_shift($posts) : null;

$pageTitle = $cat ? "Articles in $cat" : ($tag ? "Tagged: $tag" : 'Writing');
$pageDesc  = 'Practical guides on n8n automation, digital product creation, and building workflows that sell.';

pageOpen($pageTitle,[
    'title' => $pageTitle,
    'desc'  => $pageDesc,
    'canonical' => SITE_URL.'/blog.php'.($page>1?'?page='.$page:''),
],'Writing');
?>

<main id="main-content" class="pt-14">

<div class="border-b border-border bg-cream">
  <div class="max-w-site mx-auto px-5 lg:px-8 py-12">
    <p class="font-mono text-[.65rem] text-muted uppercase tracking-widest mb-3">Writing</p>
    <h1 class="font-display font-bold text-[clamp(2rem,5vw,4rem)] leading-[.95] text-ink">
      <?=$cat?e($cat):($tag?'Tagged: '.e($tag):'For operators,')?>
      <?php if(!$cat&&!$tag): ?><br/><em class="font-medium text-muted">by operators.</em><?php endif; ?>
    </h1>
    <?php if($total>0): ?>
    <p class="font-mono text-[.65rem] text-muted uppercase tracking-widest mt-4"><?=$total?> post<?=$total!==1?'s':''?></p>
    <?php endif; ?>
  </div>
</div>

<!-- Category filter -->
<?php if($cats): ?>
<div class="border-b border-border bg-stone/50 overflow-x-auto">
  <div class="max-w-site mx-auto px-5 lg:px-8">
    <div class="flex gap-0 min-w-max">
      <a href="/blog.php" class="font-mono text-[.65rem] uppercase tracking-widest px-4 py-3 border-b-2 <?=!$cat&&!$tag?'border-ink text-ink':'border-transparent text-muted hover:text-ink'?> transition-colors whitespace-nowrap">All</a>
      <?php foreach($cats as $c): ?>
      <a href="/blog.php?cat=<?=urlencode($c['category'])?>" class="font-mono text-[.65rem] uppercase tracking-widest px-4 py-3 border-b-2 <?=$cat===$c['category']?'border-ink text-ink':'border-transparent text-muted hover:text-ink'?> transition-colors whitespace-nowrap">
        <?=e($c['category'])?> <span class="text-muted/60">(<?=$c['cnt']?>)</span>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</div>
<?php endif; ?>

<?php if(!$posts&&!$featured): ?>
<div class="max-w-site mx-auto px-5 lg:px-8 py-20 text-center">
  <p class="text-muted font-ui">No posts yet. <a href="/blog.php" class="text-accent">View all →</a></p>
</div>
<?php else: ?>

<!-- Featured post (first page, no filter) -->
<?php if($featured): ?>
<div class="border-b border-border">
  <div class="max-w-site mx-auto px-5 lg:px-8 grid lg:grid-cols-[1fr_300px] gap-0">
    <article class="py-12 lg:pr-12 lg:border-r border-border">
      <p class="font-mono text-[.6rem] text-muted uppercase tracking-widest mb-4">
        <?=e($featured['category']??'Article')?> · Featured
      </p>
      <h2 class="font-display font-bold text-[clamp(1.5rem,3vw,2.25rem)] leading-tight text-ink mb-4">
        <a href="/post.php?slug=<?=e($featured['slug'])?>" class="hover:text-accent transition-colors">
          <?=e($featured['title'])?>
        </a>
      </h2>
      <p class="font-ui text-muted leading-relaxed max-w-lg mb-6"><?=e($featured['excerpt']??'')?></p>
      <a href="/post.php?slug=<?=e($featured['slug'])?>" class="font-ui font-semibold text-sm text-accent hover:text-ink transition-colors">
        Read — <?=$featured['read_time']?> min →
      </a>
    </article>
    <div class="py-12 lg:pl-12 flex flex-col gap-4">
      <span class="font-mono text-[.6rem] text-muted uppercase tracking-widest">
        <?=date('M j, Y',strtotime($featured['created_at']))?>
      </span>
      <?php $tags=json_decode($featured['tags']??'[]',true);if($tags): ?>
      <div class="flex flex-wrap gap-2 mt-auto">
        <?php foreach($tags as $tg): ?>
        <a href="/blog.php?tag=<?=urlencode($tg)?>" class="font-mono text-[.6rem] border border-border px-2.5 py-1 text-muted hover:text-ink hover:border-ink transition-colors">
          <?=e($tg)?>
        </a>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
    </div>
  </div>
</div>
<?php endif; ?>

<!-- Posts grid -->
<?php if($posts): ?>
<div class="max-w-site mx-auto px-5 lg:px-8">
  <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-px bg-border border-b border-border">
    <?php foreach($posts as $post): ?>
    <article class="bg-cream p-7 hover:bg-white transition-colors">
      <p class="font-mono text-[.6rem] text-muted uppercase tracking-widest mb-3">
        <a href="/blog.php?cat=<?=urlencode($post['category']??'')?>" class="hover:text-ink transition-colors"><?=e($post['category']??'Article')?></a>
      </p>
      <h2 class="font-display font-semibold text-base leading-snug text-ink mb-3">
        <a href="/post.php?slug=<?=e($post['slug'])?>" class="hover:text-accent transition-colors">
          <?=e($post['title'])?>
        </a>
      </h2>
      <p class="font-ui text-sm text-muted leading-relaxed mb-4 line-clamp-2"><?=e($post['excerpt']??'')?></p>
      <div class="flex justify-between items-center">
        <a href="/post.php?slug=<?=e($post['slug'])?>" class="font-mono text-[.65rem] text-muted uppercase tracking-widest hover:text-accent transition-colors">
          Read — <?=$post['read_time']?> min →
        </a>
        <span class="font-mono text-[.6rem] text-muted/60"><?=timeAgo($post['created_at'])?></span>
      </div>
    </article>
    <?php endforeach; ?>
  </div>
</div>
<?php endif; ?>

<!-- Pagination -->
<?php if($pages>1): ?>
<nav aria-label="Blog pagination" class="border-b border-border">
  <div class="max-w-site mx-auto px-5 lg:px-8 py-6 flex items-center justify-between gap-4">
    <?php if($page>1): ?>
    <a href="<?=paginateUrl($page-1)?>" class="font-ui text-sm font-medium text-muted hover:text-ink flex items-center gap-2 transition-colors">← Previous</a>
    <?php else: ?><span></span><?php endif; ?>
    <div class="flex items-center gap-1">
      <?php for($i=1;$i<=$pages;$i++): ?>
      <a href="<?=paginateUrl($i)?>" class="font-mono text-xs w-8 h-8 flex items-center justify-center border <?=$i===$page?'border-ink bg-ink text-white':'border-border text-muted hover:border-ink hover:text-ink'?> transition-colors">
        <?=$i?>
      </a>
      <?php endfor; ?>
    </div>
    <?php if($page<$pages): ?>
    <a href="<?=paginateUrl($page+1)?>" class="font-ui text-sm font-medium text-muted hover:text-ink flex items-center gap-2 transition-colors">Next →</a>
    <?php else: ?><span></span><?php endif; ?>
  </div>
</nav>
<?php endif; ?>
<?php endif; ?>

<!-- CTA strip -->
<div class="bg-stone/50">
  <div class="max-w-site mx-auto px-5 lg:px-8 py-10 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-5">
    <div>
      <p class="font-display font-semibold text-lg text-ink">Ready to build, not just read?</p>
      <p class="font-ui text-sm text-muted mt-1">AutoFlow Blueprint takes you from zero to first sale in 28 days.</p>
    </div>
    <a href="/product.php" class="shrink-0 bg-ink text-white font-ui font-semibold text-sm px-6 py-3 hover:bg-accent transition-colors">
      Get the Blueprint — <?=PRODUCT_PRICE?>
    </a>
  </div>
</div>

</main>
<?php pageClose(); ?>
