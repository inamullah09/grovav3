<?php
require_once __DIR__.'/config.php';

$posts = getPosts(1);
$recentPosts = array_slice($posts,0,3);

$schema = orgSchema();

pageOpen('', [
    'title'  => '',
    'desc'   => 'Grova builds premium automation workflows, templates, and digital tools for operators who want to work smarter and earn more.',
    'schema' => $schema,
], 'Home');
?>

<main id="main-content" class="pt-14">

<!-- ══ HERO ══════════════════════════════════════════════════════════════════ -->
<section aria-label="Hero" class="border-b border-border">
  <div class="max-w-site mx-auto px-5 lg:px-8 py-20 lg:py-28">
    <div class="max-w-3xl fade-up">
      <p class="font-mono text-[.65rem] text-muted uppercase tracking-widest mb-6">Grova Digital Ecosystem · Est. 2026</p>
      <h1 class="font-display font-bold text-[clamp(2.75rem,7vw,5.5rem)] leading-[.95] tracking-tight text-ink mb-7">
        The operating<br/>system for<br/><em class="not-italic text-accent">digital builders.</em>
      </h1>
      <p class="font-ui text-lg text-muted max-w-md leading-relaxed mb-10">
        Premium automation workflows, ready-to-sell templates, and practical systems. 
        Built for operators who want to work less and earn more.
      </p>
      <div class="flex flex-wrap gap-4 items-center">
        <a href="/product.php" class="bg-accent text-white font-ui font-semibold text-sm px-7 py-3.5 hover:bg-ink transition-colors">
          Get <?=PRODUCT_NAME?> — <?=PRODUCT_PRICE?>
        </a>
        <a href="#ecosystem" class="font-ui text-sm text-ink font-medium border-b border-ink/40 pb-0.5 hover:border-ink transition-colors">
          Explore the ecosystem →
        </a>
      </div>
    </div>

    <!-- Stats grid -->
    <div class="mt-16 pt-8 border-t border-border grid grid-cols-2 lg:grid-cols-4 gap-0">
      <?php foreach([
        ['1,000+','Automation workflows'],
        ['28','Day launch roadmap'],
        ['$0','Startup cost'],
        [PRODUCT_PRICE,'One-time, yours forever'],
      ] as [$n,$l]): ?>
      <div class="py-3 pr-6 border-r border-border last:border-0">
        <div class="font-display font-bold text-[2.25rem] leading-none text-ink"><?=$n?></div>
        <div class="font-mono text-[.6rem] text-muted uppercase tracking-widest mt-1.5"><?=$l?></div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ══ TICKER ════════════════════════════════════════════════════════════════ -->
<div class="ticker-track border-b border-border bg-ink py-2.5" aria-hidden="true">
  <div class="ticker-reel">
    <?php $ti=['1,000+ n8n templates','Zero coding required','First sale in 7 days','Gmail · Slack · Notion · Telegram','$0 startup cost','28-day launch roadmap','50+ AI prompts','14-day guarantee','Gumroad · Etsy · Lemon Squeezy'];
    foreach(array_merge($ti,$ti,$ti) as $t): ?>
    <span class="font-mono text-[.6rem] text-white/30 uppercase tracking-widest px-8"><?=$t?><span class="text-accent mx-4">–</span></span>
    <?php endforeach; ?>
  </div>
</div>

<!-- ══ ABOUT / MISSION ═══════════════════════════════════════════════════════ -->
<section id="about" aria-labelledby="about-heading" class="border-b border-border">
  <div class="max-w-site mx-auto px-5 lg:px-8 py-16 grid md:grid-cols-[240px_1fr] gap-10 lg:gap-20 items-start">
    <div>
      <p class="font-mono text-[.65rem] text-muted uppercase tracking-widest">Our mission</p>
    </div>
    <div>
      <h2 id="about-heading" class="font-display font-bold text-[clamp(1.5rem,3vw,2.5rem)] leading-tight text-ink mb-5">
        Close the gap between automation builders and the businesses that need them.
      </h2>
      <p class="font-ui text-muted leading-relaxed mb-4 max-w-prose">
        Thousands of production-ready automation workflows exist on GitHub, n8n.io, and Reddit right now. 
        Businesses desperately need them. Most can't find, configure, or deploy them. We bridge that gap — 
        and teach you to do the same for profit.
      </p>
      <p class="font-ui text-muted leading-relaxed max-w-prose mb-8">
        No gatekeeping. No jargon. One product to start, an ecosystem to grow with.
      </p>
      <div class="flex flex-wrap gap-2">
        <?php foreach(['Operator-First','No-Code Friendly','$0 to Start','Production-Ready','SEO-Optimized'] as $t): ?>
        <span class="font-mono text-[.6rem] border border-border px-3 py-1.5 text-muted uppercase tracking-wide"><?=$t?></span>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<!-- ══ FEATURED PRODUCT ══════════════════════════════════════════════════════ -->
<section id="ecosystem" aria-labelledby="product-heading" class="border-b border-border">
  <div class="max-w-site mx-auto px-5 lg:px-8 py-10 flex justify-between items-end flex-wrap gap-4 border-b border-border">
    <div>
      <p class="font-mono text-[.65rem] text-muted uppercase tracking-widest mb-2">Featured Product</p>
      <h2 id="product-heading" class="font-display font-bold text-[clamp(1.75rem,3.5vw,2.75rem)] text-ink"><?=PRODUCT_NAME?></h2>
    </div>
    <a href="/product.php" class="font-ui text-sm font-semibold bg-accent text-white px-5 py-2.5 hover:bg-ink transition-colors">View full details →</a>
  </div>
  <div class="max-w-site mx-auto px-5 lg:px-8 grid lg:grid-cols-[1fr_380px] gap-0">
    <!-- Left: what's inside -->
    <div class="py-12 lg:pr-12 lg:border-r border-border">
      <p class="font-ui text-muted leading-relaxed max-w-xl mb-8">
        The complete system to find free n8n automation workflows, package them with AI-written documentation, 
        and sell them for $19–$97 each. No coding. No prior audience required.
      </p>
      <div class="grid sm:grid-cols-2 gap-3 mb-8">
        <?php foreach([
          ['1,000+','Curated workflows, 20 categories'],
          ['28-day','Pre-decided daily roadmap'],
          ['50+','Copy-paste ChatGPT prompts'],
          ['3 platforms','Gumroad, Etsy, Lemon Squeezy'],
          ['16-point','Sellability scorecard'],
          ['80 min','Average per product'],
        ] as [$n,$l]): ?>
        <div class="flex items-start gap-3 border border-border p-3.5">
          <span class="font-display font-bold text-accent text-base shrink-0"><?=$n?></span>
          <span class="font-ui text-sm text-muted"><?=$l?></span>
        </div>
        <?php endforeach; ?>
      </div>
      <div class="flex flex-wrap gap-4 items-center">
        <a href="/product.php" class="bg-ink text-white font-ui font-semibold text-sm px-6 py-3 hover:bg-accent transition-colors">
          Get <?=PRODUCT_NAME?> — <?=PRODUCT_PRICE?>
        </a>
        <span class="font-mono text-xs text-muted">14-day money-back guarantee</span>
      </div>
    </div>
    <!-- Right: proof panel -->
    <div class="py-12 lg:pl-12">
      <div class="font-display font-bold text-6xl text-ink mb-1"><?=PRODUCT_PRICE?></div>
      <p class="font-mono text-[.6rem] text-muted uppercase tracking-widest mb-6">One-time · instant PDF download</p>
      <div class="border-b border-border mb-6"></div>
      <!-- Sellability scores -->
      <p class="font-mono text-[.6rem] text-muted uppercase tracking-widest mb-4">Top-scoring workflows</p>
      <?php foreach([
        ['WhatsApp AI RAG Chatbot',94,'$97'],
        ['Invoice Extraction → CSV',87,'$77'],
        ['Gmail AI Label System',75,'$47'],
      ] as [$name,$pct,$price]): ?>
      <div class="mb-4">
        <div class="flex justify-between items-baseline mb-1.5">
          <span class="font-ui text-sm font-medium text-ink"><?=$name?></span>
          <span class="font-mono text-xs text-muted"><?=$price?></span>
        </div>
        <div class="h-[2px] bg-stone"><div class="h-[2px] bg-accent transition-all" style="width:<?=$pct?>%"></div></div>
      </div>
      <?php endforeach; ?>
      <!-- Income projection -->
      <div class="bg-ink text-white p-5 mt-6">
        <div class="font-display font-bold text-2xl">$500+/mo</div>
        <p class="font-mono text-[.6rem] text-white/50 uppercase tracking-widest mt-1">15 products · avg $37 · 14 sales/mo</p>
      </div>
    </div>
  </div>
</section>

<!-- ══ ECOSYSTEM VISION ══════════════════════════════════════════════════════ -->
<section aria-labelledby="vision-heading" class="border-b border-border bg-ink text-white">
  <div class="max-w-site mx-auto px-5 lg:px-8 py-16">
    <div class="max-w-2xl">
      <p class="font-mono text-[.65rem] text-white/40 uppercase tracking-widest mb-4">Where we're going</p>
      <h2 id="vision-heading" class="font-display font-bold text-[clamp(1.75rem,3.5vw,2.75rem)] leading-tight mb-5">
        More than one product.<br/>A full builder ecosystem.
      </h2>
      <p class="font-ui text-white/60 leading-relaxed mb-8 max-w-prose">
        AutoFlow Blueprint is the foundation. Coming next: SEO workflow kits, client acquisition systems, 
        AI-powered document templates, and free community tools. Every product is designed to compound — 
        each one making the next more valuable.
      </p>
      <div class="grid sm:grid-cols-3 gap-4">
        <?php foreach([
          ['Now','AutoFlow Blueprint','The complete workflow packaging system.'],
          ['Q2 2026','SEO Kit','Rank your workflow listings on Etsy and Google.'],
          ['Q3 2026','Client Acquisition System','Land recurring clients with automation assets.'],
        ] as [$when,$name,$desc]): ?>
        <div class="border border-white/10 p-5">
          <p class="font-mono text-[.6rem] text-accent uppercase tracking-widest mb-2"><?=$when?></p>
          <h3 class="font-display font-semibold text-sm text-white mb-2"><?=$name?></h3>
          <p class="font-ui text-xs text-white/50 leading-relaxed"><?=$desc?></p>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<!-- ══ BLOG PREVIEW ══════════════════════════════════════════════════════════ -->
<?php if ($recentPosts): ?>
<section aria-labelledby="blog-heading" class="border-b border-border">
  <div class="max-w-site mx-auto px-5 lg:px-8 py-10 flex justify-between items-end flex-wrap gap-4 border-b border-border">
    <div>
      <p class="font-mono text-[.65rem] text-muted uppercase tracking-widest mb-2">Writing</p>
      <h2 id="blog-heading" class="font-display font-bold text-[clamp(1.5rem,3vw,2rem)] text-ink">
        Practical guides for operators
      </h2>
    </div>
    <a href="/blog.php" class="font-ui text-sm text-muted border-b border-border pb-0.5 hover:text-ink hover:border-ink transition-colors">
      All posts →
    </a>
  </div>
  <div class="max-w-site mx-auto px-5 lg:px-8 grid md:grid-cols-3 divide-y md:divide-y-0 md:divide-x divide-border">
    <?php foreach($recentPosts as $i=>$post): ?>
    <article class="py-8 <?=$i>0?'md:pl-8':''?> <?=$i<2?'md:pr-8':''?>">
      <p class="font-mono text-[.6rem] text-muted uppercase tracking-widest mb-3"><?=e($post['category']??'Article')?></p>
      <h3 class="font-display font-semibold text-base leading-snug text-ink mb-3">
        <a href="/post.php?slug=<?=e($post['slug'])?>" class="hover:text-accent transition-colors"><?=e($post['title'])?></a>
      </h3>
      <p class="font-ui text-sm text-muted leading-relaxed mb-4 line-clamp-2"><?=e($post['excerpt']??'')?></p>
      <a href="/post.php?slug=<?=e($post['slug'])?>" class="font-mono text-[.7rem] text-muted uppercase tracking-widest hover:text-accent transition-colors">
        Read — <?=$post['read_time']?> min →
      </a>
    </article>
    <?php endforeach; ?>
  </div>
</section>
<?php endif; ?>

<!-- ══ BOTTOM CTA ════════════════════════════════════════════════════════════ -->
<section aria-label="Call to action" class="border-b border-border">
  <div class="max-w-site mx-auto px-5 lg:px-8 py-20 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-10">
    <div>
      <h2 class="font-display font-bold text-[clamp(2rem,5vw,3.75rem)] leading-[.95] text-ink">
        Start building.<br/><span class="text-accent">Stop waiting.</span>
      </h2>
      <p class="font-ui text-muted mt-4 max-w-sm leading-relaxed">
        The system works for complete beginners. 
        <?=PRODUCT_PRICE?> once. First sale target: Day 7.
      </p>
    </div>
    <div class="shrink-0">
      <a href="/product.php" class="block bg-accent text-white font-ui font-bold text-sm px-8 py-4 hover:bg-ink transition-colors text-center">
        Get <?=PRODUCT_NAME?> — <?=PRODUCT_PRICE?>
      </a>
      <p class="font-mono text-[.6rem] text-muted uppercase tracking-widest mt-3 text-center">Instant download · 14-day guarantee</p>
    </div>
  </div>
</section>

</main>
<?php pageClose(); ?>
