<?php require_once __DIR__.'/config.php';
htmlHead('AutoFlow Blueprint — Turn Free Workflows Into Monthly Income','The complete system to find free n8n automation workflows, package them with AI docs, and sell them as digital products.');
htmlNav('Product');
?>
<div class="pt-14">

<!-- DARK HERO -->
<div class="bg-brand text-white border-b border-white/10">
  <!-- ticker -->
  <div class="overflow-hidden border-b border-white/10 py-2.5">
    <div class="ticker-inner">
      <?php $ti=['1,000+ real workflows','zero coding required','first sale in 7 days','$0 startup cost','50+ AI prompts','14-day guarantee'];
      foreach(array_merge($ti,$ti) as $t): ?>
      <span class="font-mono text-xs text-white/30 uppercase tracking-widest px-10"><?=$t?> <span class="text-accent mx-2">·</span></span>
      <?php endforeach; ?>
    </div>
  </div>
  <!-- hero content -->
  <div class="max-w-5xl mx-auto px-5 md:px-10 py-16 grid md:grid-cols-[1.2fr_1fr] gap-10 items-start">
    <div>
      <p class="font-mono text-xs text-white/40 uppercase tracking-widest mb-6">Grova · AutoFlow Blueprint · 2026</p>
      <h1 class="font-display text-[clamp(2.5rem,6.5vw,5.5rem)] leading-[.9] font-semibold tracking-tight mb-6">
        Turn free workflows<br/>into <em class="text-accent font-light">monthly income.</em>
      </h1>
      <p class="text-white/60 text-base leading-relaxed max-w-md mb-8">The complete system to find free n8n workflows, package them with AI-written docs, and sell them as digital products. No coding. No experience.</p>
      <a href="<?=GUMROAD_URL?>" target="_blank" class="inline-block bg-accent text-brand font-bold text-base px-8 py-4 hover:opacity-90 transition-opacity">
        Get Instant Access — <?=PRODUCT_PRICE?> →
      </a>
      <div class="flex flex-wrap gap-5 mt-5">
        <?php foreach(['14-day money-back guarantee','Instant PDF download','Zero coding required'] as $t): ?>
        <span class="text-xs text-white/50 flex items-center gap-1.5"><span class="text-accent font-bold">✓</span><?=$t?></span>
        <?php endforeach; ?>
      </div>
    </div>
    <!-- price card -->
    <div class="bg-white/5 border border-white/10 p-7 self-start">
      <p class="font-mono text-xs text-white/40 uppercase tracking-widest mb-2">One-time purchase</p>
      <div class="font-display text-7xl font-semibold text-white"><?=PRODUCT_PRICE?></div>
      <div class="text-white/30 text-xs line-through mt-1 mb-5">VALUE: $400+</div>
      <a href="<?=GUMROAD_URL?>" target="_blank" class="block bg-accent text-brand font-bold text-center py-4 text-sm hover:opacity-90 mb-4">Buy Now — Instant Download →</a>
      <?php foreach(['158-page PDF blueprint','1,000+ curated workflow library','50+ copy-paste ChatGPT prompts','28-day launch roadmap','Platform setup guides (Gumroad, Etsy, LS)'] as $item): ?>
      <div class="flex items-start gap-2.5 text-sm text-white/60 mb-2.5"><span class="text-accent font-bold flex-shrink-0">→</span><?=$item?></div>
      <?php endforeach; ?>
      <div class="mt-5 bg-white/5 border border-white/10 p-4">
        <p class="text-xs text-white/50 leading-relaxed"><span class="text-accent font-semibold">14-Day Guarantee.</span> Follow Days 1–7. No product listed? Full refund. No questions.</p>
      </div>
    </div>
  </div>
</div>

<?php
$sections = [
  ['01','The Opportunity','2,400+ free workflows.<br/><em class="font-light text-muted">Nobody\'s packaging them.</em>',function(){?>
  <div class="grid md:grid-cols-3 gap-px bg-border mb-10">
    <?php foreach([
      ['The gap','Developers share working automations on GitHub, Reddit, n8n.io constantly. Most businesses <strong>want them but can\'t find or deploy them.</strong> That gap is worth money.'],
      ['The value','Businesses pay $19–$97 for a packaged workflow with a clear setup guide. The value is documentation and clarity — <strong>not the code.</strong>'],
      ['The model','You find the workflow (free). Package it with AI docs. Sell it. Fulfill automatically. <strong>Repeat until you\'re making $500/month.</strong>'],
    ] as [$t,$d]): ?>
    <div class="bg-white p-7"><p class="font-mono text-xs text-muted uppercase tracking-widest mb-4"><?=$t?></p><p class="text-sm text-muted leading-relaxed"><?=$d?></p></div>
    <?php endforeach; ?>
  </div>
  <div class="bg-brand text-white p-8 max-w-2xl">
    <p class="font-display text-xl italic leading-snug font-light">"The automation market is worth $26 billion. You don't need to build the product. You need to be the <span class="text-accent not-italic">bridge</span> between the builders and the buyers."</p>
  </div>
<?php }],
];

foreach($sections as [$n,$label,$heading,$render]): ?>
<section class="border-b border-border">
  <div class="max-w-5xl mx-auto px-5 md:px-10 py-14">
    <p class="font-mono text-xs text-muted uppercase tracking-widest mb-3"><?=$n?> — <?=$label?></p>
    <h2 class="font-display text-[clamp(2rem,4vw,3rem)] font-semibold text-brand mb-10"><?=$heading?></h2>
    <?php $render(); ?>
  </div>
</section>
<?php endforeach; ?>

<!-- WHAT'S INSIDE -->
<section class="border-b border-border">
  <div class="max-w-5xl mx-auto px-5 md:px-10 py-14">
    <p class="font-mono text-xs text-muted uppercase tracking-widest mb-3">02 — What's Included</p>
    <h2 class="font-display text-[clamp(2rem,4vw,3rem)] font-semibold text-brand mb-10">Everything you need.<br/><em class="font-light text-muted">Nothing you don't.</em></h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-px bg-border">
      <?php foreach([
        ['01','28-Day Launch Roadmap','Day-by-day task cards. Zero decision fatigue. Products listed by Day 7. First sale by Day 14.',false,false],
        ['02','1,000+ Workflow Library','20 categories. Every workflow scored, priced, matched to its ideal buyer.',true,false],
        ['03','50+ ChatGPT Prompts','Setup guides, listings, social posts, landing copy. Copy → paste → done.',false,false],
        ['04','Workflow Sourcing System','Exact URLs, search terms, step-by-step. Find high-scoring workflows in under 2 hours.',true,false],
        ['05','Packaging System','ZIP structure, naming formulas, listing templates. $47–$97 product in 80 minutes.',false,false],
        ['06','Pricing &amp; Platform Playbook','The formula that works. Gumroad, Etsy, Lemon Squeezy setup guides. Scaling roadmap.',false,true],
      ] as [$n,$title,$desc,$alt,$lime]): ?>
      <div class="p-7 <?=$lime?'bg-accent':($alt?'bg-surface':'bg-white')?>">
        <div class="font-display text-4xl font-light text-border mb-5"><?=$n?></div>
        <h3 class="font-body font-semibold text-sm mb-2 <?=$lime?'text-brand':''?>"><?=$title?></h3>
        <p class="text-sm <?=$lime?'text-brand/70':'text-muted'?> leading-relaxed"><?=$desc?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- WORKFLOW TABLE -->
<section class="border-b border-border">
  <div class="max-w-5xl mx-auto px-5 md:px-10 py-14">
    <p class="font-mono text-xs text-muted uppercase tracking-widest mb-3">03 — Real Workflows Inside</p>
    <h2 class="font-display text-[clamp(2rem,4vw,3rem)] font-semibold text-brand mb-8">1,000+ templates.<br/><em class="font-light text-muted">20 real categories.</em></h2>
    <div class="overflow-x-auto">
      <table class="w-full text-sm border border-border">
        <thead><tr class="bg-brand text-white">
          <th class="text-left font-mono text-xs tracking-widest uppercase px-5 py-3.5">Workflow</th>
          <th class="text-left font-mono text-xs tracking-widest uppercase px-5 py-3.5 hidden md:table-cell">Category</th>
          <th class="text-left font-mono text-xs tracking-widest uppercase px-5 py-3.5">Price</th>
        </tr></thead>
        <tbody>
          <?php foreach([
            ['Agentic Telegram Bot + LangChain Memory','Context-aware AI bot with Supabase long-term memory','Support','$67'],
            ['Auto-Label Gmail with AI','Analyze incoming emails, auto-assign smart labels','Ops / Email','$47'],
            ['Invoice Extraction → CSV','Structured data extracted automatically, no manual entry','Finance','$77'],
            ['WhatsApp AI RAG Chatbot','Complete chatbot with retrieval-augmented generation','Customer Service','$97'],
            ['Slack + Linear Ticketing','Auto-create Linear tickets from Slack emoji reactions','Support / IT','$77'],
            ['Lead Qualifier in Google Sheets','Score and prioritize leads with GPT-4 automatically','Sales / CRM','$57'],
            ['AI Resume Screener → Sheets','Vision AI parses resumes, scores fit, notifies HR','HR','$87'],
            ['Notion AI Knowledge Base','AI that answers questions from any Notion database','Productivity','$67'],
          ] as $i=>[$name,$desc,$cat,$price]): ?>
          <tr class="<?=$i%2===1?'bg-surface':'bg-white'?> border-t border-border hover:bg-yellow-50 transition-colors">
            <td class="px-5 py-4"><div class="font-semibold text-brand"><?=$name?></div><div class="text-xs text-muted mt-0.5"><?=$desc?></div></td>
            <td class="px-5 py-4 hidden md:table-cell"><span class="font-mono text-xs text-muted"><?=$cat?></span></td>
            <td class="px-5 py-4 font-display text-xl font-semibold text-brand"><?=$price?></td>
          </tr>
          <?php endforeach; ?>
          <tr class="bg-surface border-t border-border">
            <td colspan="2" class="px-5 py-4 text-muted text-sm italic">+ 992 more across Gmail, Discord, Airtable, DevOps, Social Media…</td>
            <td class="px-5 py-4 font-display text-lg text-muted">$17–$297</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</section>

<!-- STEPS -->
<section class="border-b border-border">
  <div class="max-w-5xl mx-auto px-5 md:px-10 py-14">
    <p class="font-mono text-xs text-muted uppercase tracking-widest mb-3">04 — The Process</p>
    <h2 class="font-display text-[clamp(2rem,4vw,3rem)] font-semibold text-brand mb-10">Zero to first sale.<br/><em class="font-light text-muted">Seven steps.</em></h2>
    <?php foreach([
      ['01','Find a free workflow','Browse n8n.io, GitHub, or Reddit using our exact search queries. 15 minutes.',false],
      ['02','Score it','8 criteria, 5 minutes. Score 12+? Sellable product. Below 8? Move on.',false],
      ['03','Import and test in n8n','One-click import. Run it once. Export the .json. Under 30 minutes.',false],
      ['04','Paste 5 ChatGPT prompts','Setup guide, credentials, troubleshooting, customization guide. 60 minutes.',false],
      ['05','Design PDFs in Canva','Template included. No design skills needed. ~3 hours total product time.',false],
      ['06','Publish on 3 platforms','Gumroad, Etsy, Lemon Squeezy. Three revenue streams, one upload.',false],
      ['07','Collect revenue. Repeat.','Products sell and fulfill automatically. Build the next one while the first earns.',true],
    ] as [$n,$title,$desc,$final]): ?>
    <div class="flex gap-7 py-6 border-b <?=$final?'border-accent bg-yellow-50 -mx-5 md:-mx-10 px-5 md:px-10':'border-border'?> items-start">
      <div class="font-display text-5xl font-semibold leading-none <?=$final?'text-accent':'text-border'?> w-14 flex-shrink-0"><?=$n?></div>
      <div><h3 class="font-semibold text-sm mb-1 text-brand"><?=$title?></h3><p class="text-muted text-sm leading-relaxed"><?=$desc?></p></div>
    </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- COMPARISON -->
<section class="border-b border-border">
  <div class="max-w-5xl mx-auto px-5 md:px-10 py-14">
    <p class="font-mono text-xs text-muted uppercase tracking-widest mb-3">05 — Why This Model</p>
    <h2 class="font-display text-[clamp(2rem,4vw,3rem)] font-semibold text-brand mb-10">vs. every other<br/><em class="font-light text-muted">digital product model.</em></h2>
    <div class="overflow-x-auto">
      <table class="w-full text-sm">
        <thead><tr class="bg-surface border-b border-border">
          <th class="text-left font-mono text-xs tracking-widest uppercase px-5 py-3.5 text-muted">What You Need</th>
          <th class="text-left font-mono text-xs tracking-widest uppercase px-5 py-3.5 bg-brand text-white">AutoFlow Blueprint</th>
          <th class="text-left font-mono text-xs tracking-widest uppercase px-5 py-3.5 text-muted hidden md:table-cell">Other Models</th>
        </tr></thead>
        <tbody>
          <?php foreach([
            ['Coding / tech skills','✓ Zero required','Usually essential'],
            ['Upfront investment','✓ $0 to launch','$200–$2,000+'],
            ['Content creation','✓ AI generates it all','Weeks of writing'],
            ['Time per product','✓ ~80 minutes','Days to weeks'],
            ['Source material cost','✓ Free (open source)','Paid licenses'],
            ['Order fulfillment','✓ 100% automated','Manual delivery'],
            ['Time to first sale','✓ 7–14 days','2–6 months'],
          ] as [$need,$ours,$theirs]): ?>
          <tr class="border-b border-border">
            <td class="px-5 py-3.5 text-muted"><?=$need?></td>
            <td class="px-5 py-3.5 font-semibold text-green-700 bg-green-50"><?=$ours?></td>
            <td class="px-5 py-3.5 text-muted line-through opacity-40 hidden md:table-cell"><?=$theirs?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</section>

<!-- TESTIMONIALS -->
<section class="border-b border-border bg-surface">
  <div class="max-w-5xl mx-auto px-5 md:px-10 py-14">
    <p class="font-mono text-xs text-muted uppercase tracking-widest mb-3">06 — Results</p>
    <h2 class="font-display text-[clamp(2rem,4vw,3rem)] font-semibold text-brand mb-10">Real buyers.<br/><em class="font-light text-muted">Real outcomes.</em></h2>
    <div class="grid md:grid-cols-3 gap-px bg-border">
      <?php foreach([
        ['Had my first Gumroad sale on Day 9. Posted in r/n8n, one person bought. Made $47 while at the gym. Nothing else I\'ve tried worked this fast.','Marcus T.','Freelance Designer → Workflow Seller',false],
        ['I was a VA for 6 years. First product took 4 hours total. It\'s sold 23 times at $47. The math speaks for itself.','Samira K.','Virtual Assistant → Digital Creator',true],
        ['34 products live. Making $1,200–$2,800/month. The workflow library alone is worth 10× the price.','Ryan O.','Software Tester → Full-Time Creator',false],
      ] as [$q,$name,$role,$alt]): ?>
      <div class="<?=$alt?'bg-white':'bg-surface'?> p-7">
        <p class="font-display text-base italic leading-relaxed text-brand mb-5 font-light">"<?=$q?>"</p>
        <div class="font-semibold text-sm text-brand"><?=$name?></div>
        <div class="text-xs text-muted mt-0.5"><?=$role?></div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- FAQ -->
<section class="border-b border-border">
  <div class="max-w-5xl mx-auto px-5 md:px-10 py-14">
    <p class="font-mono text-xs text-muted uppercase tracking-widest mb-3">07 — Questions</p>
    <h2 class="font-display text-[clamp(2rem,4vw,3rem)] font-semibold text-brand mb-10">Common questions.<br/><em class="font-light text-muted">Honest answers.</em></h2>
    <div class="max-w-2xl">
      <?php foreach([
        ['Do I need to know how to code?','Zero. The most technical step is importing a JSON file — one click in n8n. Everything else uses free tools and AI.'],
        ['Is selling packaged workflows legal?','Yes. You\'re selling documentation and your time — not the software. MIT-licensed workflows explicitly allow commercial use. The guide covers license verification.'],
        ['What does it actually cost to start?','$0 beyond this guide. n8n free tier, Canva free, ChatGPT free tier. Platforms take a % of sales only.'],
        ['How realistic is $500/month?','15–20 products at avg $37 = ~14 sales/month. Month 2 buyers typically see 5–20 sales. By Month 3–6, Etsy SEO compounds without ongoing promotion.'],
        ['What if I have no existing audience?','Designed for zero-audience starts. First sales come from Reddit communities, Etsy organic search, and LinkedIn outreach — all in the 28-day roadmap.'],
        ['Why is the price so low?','Starting at '.PRODUCT_PRICE.' to build real social proof. Content took months to build. The workflow library alone represents hundreds of hours of research.'],
      ] as [$q,$a]): ?>
      <div class="border-b border-border py-5">
        <div class="font-semibold text-sm text-brand mb-2"><?=$q?></div>
        <p class="text-muted text-sm leading-relaxed"><?=$a?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- FINAL CTA -->
<section class="bg-brand text-white">
  <div class="max-w-5xl mx-auto px-5 md:px-10 py-20 flex flex-col md:flex-row justify-between items-center gap-12">
    <h2 class="font-display text-[clamp(2.5rem,6vw,4.5rem)] font-semibold leading-[.9]">
      The people doing this<br/>started <em class="text-accent font-light">exactly</em><br/>where you are.
    </h2>
    <div class="w-full max-w-xs">
      <p class="text-white/50 text-sm leading-relaxed mb-6">Not more technical. Not smarter. Just earlier. The system is ready.</p>
      <a href="<?=GUMROAD_URL?>" target="_blank" class="block bg-accent text-brand font-bold text-center text-sm px-6 py-4 hover:opacity-90">
        Get AutoFlow Blueprint — <?=PRODUCT_PRICE?>
      </a>
      <p class="text-white/30 text-xs mt-3 text-center uppercase tracking-wider">Instant download · 14-day guarantee</p>
    </div>
  </div>
</section>

</div>
<?php htmlFoot(); ?>
