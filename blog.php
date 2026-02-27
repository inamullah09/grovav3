<?php require_once __DIR__.'/config.php';
htmlHead('Writing — Automation Guides');
htmlNav('Blog');
$posts = getAllPosts();
$featured = $posts[0] ?? null;
$rest = array_slice($posts,1);
?>
<div class="pt-14">
<div class="border-b border-border bg-surface">
  <div class="max-w-5xl mx-auto px-5 md:px-10 py-14">
    <p class="font-mono text-xs text-muted uppercase tracking-widest mb-4">Writing</p>
    <h1 class="font-display text-[clamp(3rem,7vw,5.5rem)] font-semibold leading-[.92] text-brand">For operators,<br/><em class="font-light text-muted">by operators.</em></h1>
  </div>
</div>
<?php if(!$posts): ?>
<div class="max-w-5xl mx-auto px-5 md:px-10 py-24 text-center text-muted">No posts yet.</div>
<?php else: ?>
<?php if($featured): ?>
<div class="border-b border-border">
  <div class="max-w-5xl mx-auto px-5 md:px-10 grid md:grid-cols-[1.5fr_1fr] gap-0">
    <div class="py-12 md:pr-10 md:border-r border-border">
      <p class="font-mono text-xs text-muted uppercase tracking-widest mb-4"><?=e($featured['category'])?> · Featured</p>
      <h2 class="font-display text-[clamp(1.5rem,3.5vw,2.5rem)] font-semibold leading-snug text-brand mb-4">
        <a href="/post.php?slug=<?=$featured['slug']?>" class="hover:text-muted transition-colors"><?=e($featured['title'])?></a>
      </h2>
      <p class="text-muted text-sm leading-relaxed max-w-lg mb-5"><?=e($featured['excerpt']??'')?></p>
      <a href="/post.php?slug=<?=$featured['slug']?>" class="text-sm font-semibold border-b border-brand pb-0.5 hover:text-muted hover:border-muted transition-colors">Read — <?=$featured['read_time']?> min →</a>
    </div>
    <div class="py-12 md:pl-10 flex flex-col gap-4">
      <span class="text-xs text-muted"><?=date('M j, Y',strtotime($featured['created_at']))?></span>
      <?php $tags=json_decode($featured['tags']??'[]',true);if($tags): ?>
      <div class="flex flex-wrap gap-2 mt-auto">
        <?php foreach($tags as $tag): ?><span class="font-mono text-xs border border-border px-2.5 py-1 text-muted"><?=e($tag)?></span><?php endforeach; ?>
      </div>
      <?php endif; ?>
    </div>
  </div>
</div>
<?php endif; ?>
<?php if($rest): ?>
<div class="max-w-5xl mx-auto px-5 md:px-10 grid md:grid-cols-2 gap-0">
  <?php foreach($rest as $i=>$post): ?>
  <div class="py-8 border-b border-border <?=$i%2===0&&count($rest)>1?'md:border-r md:pr-8':''?> <?=$i%2===1?'md:pl-8':''?>">
    <p class="font-mono text-xs text-muted uppercase tracking-widest mb-3"><?=e($post['category'])?></p>
    <h3 class="font-display text-xl font-semibold leading-snug text-brand mb-3">
      <a href="/post.php?slug=<?=$post['slug']?>" class="hover:text-muted transition-colors"><?=e($post['title'])?></a>
    </h3>
    <p class="text-muted text-sm leading-relaxed mb-4 line-clamp-2"><?=e($post['excerpt']??'')?></p>
    <div class="flex justify-between items-center">
      <a href="/post.php?slug=<?=$post['slug']?>" class="text-xs font-semibold border-b border-border pb-0.5 hover:border-brand transition-colors">Read — <?=$post['read_time']?> min →</a>
      <span class="text-xs text-muted"><?=timeAgo($post['created_at'])?></span>
    </div>
  </div>
  <?php endforeach; ?>
</div>
<?php endif;endif; ?>
<div class="bg-surface border-t border-border">
  <div class="max-w-5xl mx-auto px-5 md:px-10 py-10 flex flex-col md:flex-row justify-between items-center gap-5">
    <div><p class="font-display text-2xl font-semibold text-brand">Ready to build, not just read?</p><p class="text-muted text-sm mt-1">AutoFlow Blueprint gets you from zero to first sale in 28 days.</p></div>
    <a href="/product.php" class="flex-shrink-0 bg-brand text-white text-sm font-semibold px-6 py-3 hover:bg-accent hover:text-brand transition-colors">Get the Blueprint — <?=PRODUCT_PRICE?></a>
  </div>
</div>
</div>
<?php htmlFoot(); ?>
