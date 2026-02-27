<?php require_once __DIR__.'/config.php';
$slug=$_GET['slug']??'';
$post=$slug?getPost($slug):null;
if(!$post||!($post['published']??0)){http_response_code(404);htmlHead('Not Found');echo '<div class="pt-28 text-center py-24 text-muted">Post not found. <a href="/blog.php" class="border-b border-muted">← Back</a></div>';htmlFoot();exit;}
// increment views
db()->prepare('UPDATE posts SET views=views+1 WHERE slug=?')->execute([$slug]);
htmlHead($post['title'],$post['excerpt']??'');
htmlNav('Blog');
?>
<div class="pt-14">
<div class="border-b border-border bg-surface">
  <div class="max-w-2xl mx-auto px-5 md:px-10 py-12">
    <a href="/blog.php" class="font-mono text-xs text-muted uppercase tracking-widest hover:text-brand transition-colors">← Writing</a>
    <div class="mt-7 mb-3 font-mono text-xs text-muted uppercase tracking-widest">
      <?=e($post['category']??'Article')?> · <?=$post['read_time']?> min read · <?=date('M j, Y',strtotime($post['created_at']))?>
    </div>
    <h1 class="font-display text-[clamp(2rem,5vw,3.5rem)] font-semibold leading-tight text-brand mb-5"><?=e($post['title'])?></h1>
    <?php if($post['excerpt']): ?><p class="text-muted text-lg leading-relaxed"><?=e($post['excerpt'])?></p><?php endif; ?>
  </div>
</div>
<div class="max-w-2xl mx-auto px-5 md:px-10 py-12">
  <div class="prose"><?=$post['content']?></div>
  <?php $tags=json_decode($post['tags']??'[]',true);if($tags): ?>
  <div class="mt-10 pt-8 border-t border-border flex flex-wrap gap-2">
    <?php foreach($tags as $t): ?><span class="font-mono text-xs border border-border px-3 py-1.5 text-muted"><?=e($t)?></span><?php endforeach; ?>
  </div>
  <?php endif; ?>
</div>
<div class="border-t border-border bg-surface">
  <div class="max-w-2xl mx-auto px-5 md:px-10 py-10">
    <p class="font-mono text-xs text-muted uppercase tracking-widest mb-3">Want to put this into practice?</p>
    <p class="font-display text-2xl font-semibold text-brand mb-5">AutoFlow Blueprint gives you the exact system, templates, and 28-day roadmap.</p>
    <a href="/product.php" class="inline-block bg-brand text-white text-sm font-semibold px-6 py-3 hover:bg-accent hover:text-brand transition-colors">Get the Blueprint — <?=PRODUCT_PRICE?></a>
  </div>
</div>
<?php $more=array_filter(getAllPosts(),fn($p)=>$p['slug']!==$post['slug']);$more=array_slice(array_values($more),0,2);if($more): ?>
<div class="border-t border-border">
  <div class="max-w-2xl mx-auto px-5 md:px-10 py-10">
    <p class="font-mono text-xs text-muted uppercase tracking-widest mb-7">More Writing</p>
    <div class="grid md:grid-cols-2 gap-0 divide-y md:divide-y-0 md:divide-x divide-border">
      <?php foreach($more as $i=>$p): ?>
      <div class="<?=$i===1?'md:pl-7 pt-5 md:pt-0':''?> <?=$i===0&&count($more)>1?'md:pr-7 pb-5 md:pb-0':''?>">
        <p class="font-mono text-xs text-muted uppercase tracking-widest mb-2"><?=e($p['category']??'Article')?></p>
        <h3 class="font-display text-lg font-semibold text-brand mb-3"><a href="/post.php?slug=<?=$p['slug']?>" class="hover:text-muted transition-colors"><?=e($p['title'])?></a></h3>
        <a href="/post.php?slug=<?=$p['slug']?>" class="text-xs font-semibold border-b border-border pb-0.5 hover:border-brand transition-colors">Read →</a>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>
<?php endif; ?>
</div>
<?php htmlFoot(); ?>
