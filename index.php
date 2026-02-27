<?php require_once __DIR__.'/config.php';
htmlHead('Automate Your Work. Own Your Time.');
htmlNav();
$posts = array_slice(getAllPosts(), 0, 3);
?>
<div class="pt-14">

<!-- HERO -->
<section class="min-h-[92vh] flex flex-col justify-center border-b border-border px-5 md:px-10 py-20 max-w-5xl mx-auto">
  <p class="font-mono text-xs text-muted uppercase tracking-widest mb-6">Grova · Est. 2026</p>
  <h1 class="font-display text-[clamp(3rem,8vw,6.5rem)] leading-[.92] font-semibold tracking-tight mb-8 max-w-[13ch] text-brand">
    Automate your work.<br/><span class="text-muted font-light italic">Own your time.</span>
  </h1>
  <p class="text-muted text-lg max-w-md leading-relaxed mb-10">
    The complete system to turn free n8n workflows into a digital product business. No coding. No audience. First sale in 7 days.
  </p>
  <div class="flex flex-wrap gap-4 items-center">
    <a href="/product.php" class="bg-brand text-white font-semibold text-sm px-8 py-4 hover:bg-accent hover:text-brand transition-colors">
      Get AutoFlow Blueprint — <?=PRODUCT_PRICE?>
    </a>
    <a href="#what" class="text-sm font-medium text-brand border-b border-brand/30 pb-0.5 hover:border-brand transition-colors">See what's inside →</a>
  </div>
  <div class="mt-16 pt-8 border-t border-border grid grid-cols-2 md:grid-cols-4 gap-0">
    <?php foreach([['1,000+','Workflow Templates'],['28','Day Roadmap'],['$0','To Start'],[PRODUCT_PRICE,'One-Time']] as [$n,$l]): ?>
    <div class="pr-6 py-2 border-r border-border last:border-0 md:last:border-0">
      <div class="font-display text-4xl font-semibold text-brand"><?=$n?></div>
      <div class="font-mono text-[.65rem] text-muted uppercase tracking-widest mt-1"><?=$l?></div>
    </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- TICKER -->
<div class="overflow-hidden border-b border-border bg-brand py-2.5">
  <div class="ticker-inner">
    <?php $ti=['1,000+ n8n templates','zero coding required','first sale in 7 days','Gmail · Slack · Notion · Telegram','$0 startup cost','28-day roadmap','50+ AI prompts','14-day guarantee'];
    foreach(array_merge($ti,$ti) as $t): ?>
    <span class="font-mono text-xs text-white/40 uppercase tracking-widest px-10"><?=$t?> <span class="text-accent mx-2">·</span></span>
    <?php endforeach; ?>
  </div>
</div>

<!-- ABOUT -->
<section id="about" class="border-b border-border">
  <div class="max-w-5xl mx-auto px-5 md:px-10 py-16 grid md:grid-cols-[1fr_2fr] gap-14 items-start">
    <p class="font-mono text-xs text-muted uppercase tracking-widest pt-1">What we do</p>
    <div>
      <h2 class="font-display text-[clamp(1.75rem,3.5vw,2.75rem)] font-semibold leading-snug text-brand mb-5">
        We close the gap between automation builders and businesses that need them.
      </h2>
      <p class="text-muted leading-relaxed mb-4 text-[.97rem]">Thousands of free, working automation workflows sit on GitHub and n8n.io right now. Most businesses want them — most can't find, use, or package them. Grova shows you how to close that gap and get paid doing it.</p>
      <p class="text-muted leading-relaxed text-[.97rem] mb-8">One product. No gatekeeping. A complete system you can start tonight for zero dollars.</p>
      <div class="flex flex-wrap gap-2">
        <?php foreach(['Operator-First','No-Code Friendly','$0 Upfront','Built to Scale'] as $t): ?>
        <span class="font-mono text-xs border border-border px-3 py-1.5 text-muted"><?=$t?></span>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<!-- PRODUCT PREVIEW -->
<section id="what" class="border-b border-border">
  <div class="max-w-5xl mx-auto px-5 md:px-10 py-10 flex justify-between items-end flex-wrap gap-4 border-b border-border">
    <div>
      <p class="font-mono text-xs text-muted uppercase tracking-widest mb-2">Flagship Product · 2026</p>
      <h2 class="font-display text-[clamp(2rem,4vw,3rem)] font-semibold text-brand">AutoFlow Blueprint</h2>
    </div>
    <a href="/product.php" class="bg-accent text-brand text-sm font-bold px-6 py-3 hover:opacity-90 transition-opacity">Full Details →</a>
  </div>
  <div class="max-w-5xl mx-auto px-5 md:px-10 grid md:grid-cols-[1.4fr_1fr]">
    <div class="py-10 md:pr-10 md:border-r border-border">
      <p class="text-muted text-[.95rem] leading-relaxed max-w-lg mb-7">The complete system to find free n8n workflows, package them with AI-written docs, and sell them for $19–$97 each. No coding. No prior audience. Yours forever.</p>
      <?php foreach(['1,000+ curated workflows across 20 categories','28-day pre-decided daily launch roadmap','50+ copy-paste ChatGPT prompts for every doc','Gumroad, Etsy &amp; Lemon Squeezy setup guides','Sellability scorecard + pricing formula'] as $item): ?>
      <div class="flex items-start gap-3 mb-3 text-sm text-muted">
        <span class="bg-brand text-accent w-5 h-5 flex items-center justify-center text-[10px] flex-shrink-0 mt-0.5 font-mono">→</span>
        <?=$item?>
      </div>
      <?php endforeach; ?>
      <div class="mt-8 flex flex-wrap gap-4 items-center">
        <a href="/product.php" class="bg-brand text-white text-sm font-semibold px-6 py-3 hover:bg-accent hover:text-brand transition-colors">Get the Blueprint — <?=PRODUCT_PRICE?></a>
        <span class="text-xs text-muted">14-day money-back guarantee</span>
      </div>
    </div>
    <div class="py-10 md:pl-10 bg-surface -mx-5 md:mx-0 px-5">
      <div class="font-display text-6xl font-semibold text-brand"><?=PRODUCT_PRICE?></div>
      <div class="font-mono text-xs text-muted uppercase tracking-widest mt-1 mb-6">One-time · instant PDF</div>
      <div class="h-px bg-border mb-5"></div>
      <?php foreach([['WhatsApp AI Chatbot',94],['Invoice Extraction Pipeline',81],['Gmail AI Label System',69]] as [$name,$pct]): ?>
      <div class="mb-4">
        <div class="flex justify-between text-xs mb-1.5">
          <span class="font-medium text-brand"><?=$name?></span>
          <span class="font-display text-sm"><?=$pct?>/100</span>
        </div>
        <div class="h-0.5 bg-border"><div class="h-0.5 bg-lime" style="width:<?=$pct?>%"></div></div>
      </div>
      <?php endforeach; ?>
      <div class="bg-accent p-5 mt-5">
        <div class="font-display text-3xl font-semibold text-brand">$500+/mo</div>
        <div class="font-mono text-xs text-brand/70 uppercase tracking-widest mt-1">15 products · avg $37 · 14 sales/mo</div>
      </div>
    </div>
  </div>
</section>

<!-- BLOG PREVIEW -->
<?php if($posts): ?>
<section class="border-b border-border">
  <div class="max-w-5xl mx-auto px-5 md:px-10 py-10 flex justify-between items-end flex-wrap gap-4 border-b border-border">
    <h2 class="font-display text-[clamp(1.75rem,3vw,2.5rem)] font-semibold text-brand">For operators, <em class="font-light">by operators.</em></h2>
    <a href="/blog.php" class="text-sm font-medium text-muted border-b border-border pb-0.5 hover:text-brand hover:border-brand transition-colors">All posts →</a>
  </div>
  <div class="max-w-5xl mx-auto px-5 md:px-10 grid md:grid-cols-<?=min(count($posts),3)?> divide-y md:divide-y-0 md:divide-x divide-border">
    <?php foreach($posts as $i=>$post): ?>
    <div class="py-8 <?=$i>0?'md:pl-8':''?> <?=$i<count($posts)-1?'md:pr-8':''?>">
      <p class="font-mono text-xs text-muted uppercase tracking-widest mb-3"><?=e($post['category'])?></p>
      <h3 class="font-display text-lg font-semibold leading-snug text-brand mb-3"><?=e($post['title'])?></h3>
      <p class="text-muted text-sm leading-relaxed mb-4 line-clamp-2"><?=e($post['excerpt']??'')?></p>
      <a href="/post.php?slug=<?=$post['slug']?>" class="text-xs font-semibold border-b border-border pb-0.5 hover:border-brand transition-colors">Read — <?=$post['read_time']?> min →</a>
    </div>
    <?php endforeach; ?>
  </div>
</section>
<?php endif; ?>

<!-- CTA -->
<section class="bg-brand text-white">
  <div class="max-w-5xl mx-auto px-5 md:px-10 py-20 flex flex-col md:flex-row justify-between items-center gap-12">
    <h2 class="font-display text-[clamp(2.5rem,6vw,4.5rem)] font-semibold leading-[.9] tracking-tight">
      Start building.<br/><em class="text-accent font-light">Stop waiting.</em>
    </h2>
    <div class="w-full max-w-xs">
      <p class="text-white/50 text-sm leading-relaxed mb-6">The system works at <?=PRODUCT_PRICE?>. The only question is whether you start today.</p>
      <a href="/product.php" class="block bg-accent text-brand text-sm font-bold text-center px-6 py-4 hover:opacity-90 transition-opacity">
        Get AutoFlow Blueprint — <?=PRODUCT_PRICE?>
      </a>
      <p class="text-white/30 text-xs mt-3 text-center">Instant download · 14-day guarantee</p>
    </div>
  </div>
</section>

</div>
<?php htmlFoot(); ?>
