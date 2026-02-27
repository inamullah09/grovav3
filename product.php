<?php
require_once __DIR__.'/config.php';

$productSchema = json_encode([
    '@context' => 'https://schema.org',
    '@type'    => 'Product',
    'name'     => PRODUCT_NAME,
    'url'      => SITE_URL.'/product.php',
    'description' => 'The complete system to find free n8n automation workflows, package them with AI-written documentation, and sell them as digital products.',
    'brand'    => ['@type'=>'Brand','name'=>SITE_NAME],
    'offers'   => ['@type'=>'Offer','price'=>'29','priceCurrency'=>'USD',
                   'availability'=>'https://schema.org/InStock',
                   'url'=>GUMROAD_URL],
], JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);

pageOpen(PRODUCT_NAME, [
    'title'   => PRODUCT_NAME.' — Turn Free Workflows Into '.'$500/Month',
    'desc'    => 'Get 1,000+ n8n automation workflows, a 28-day launch roadmap, and 50+ AI prompts. Build a digital product business with zero coding and zero upfront cost.',
    'og_type' => 'product',
    'schema'  => $productSchema,
], 'Blueprint');
?>

<main id="main-content" class="pt-14">

<!-- ══ DARK HERO ════════════════════════════════════════════════════════════ -->
<div class="bg-ink text-white">
  <!-- Ticker -->
  <div class="ticker-track border-b border-white/10 py-2">
    <div class="ticker-reel">
      <?php $ti=['1,000+ real workflows','zero coding required','first sale in 7 days','$0 startup cost','50+ AI prompts','14-day guarantee'];
      foreach(array_merge($ti,$ti,$ti) as $t): ?>
      <span class="font-mono text-[.6rem] text-white/25 uppercase tracking-widest px-8"><?=$t?><span class="text-accent mx-3">–</span></span>
      <?php endforeach; ?>
    </div>
  </div>
  <!-- Hero content -->
  <div class="max-w-site mx-auto px-5 lg:px-8 py-16 grid lg:grid-cols-[1fr_380px] gap-10 lg:gap-16 items-start">
    <div>
      <p class="font-mono text-[.65rem] text-white/40 uppercase tracking-widest mb-6">Grova · <?=PRODUCT_NAME?> · 2026</p>
      <h1 class="font-display font-bold text-[clamp(2.5rem,6vw,5rem)] leading-[.9] tracking-tight mb-6">
        Turn free workflows<br/>into <em class="not-italic text-accent">monthly income.</em>
      </h1>
      <p class="font-ui text-white/60 text-base leading-relaxed max-w-md mb-8">
        Find free n8n automation workflows. Package them with AI-written documentation. 
        Sell them for $19–$97 each on Gumroad, Etsy, and Lemon Squeezy. 
        No coding. No experience. No upfront cost.
      </p>
      <a href="<?=GUMROAD_URL?>" target="_blank" rel="noopener" class="inline-flex items-center gap-2 bg-accent text-white font-ui font-bold text-base px-8 py-4 hover:opacity-90 transition-opacity">
        Get Instant Access — <?=PRODUCT_PRICE?> →
      </a>
      <div class="flex flex-wrap gap-5 mt-5">
        <?php foreach(['14-day money-back guarantee','Instant PDF download','Zero coding required'] as $t): ?>
        <span class="font-ui text-xs text-white/50 flex items-center gap-1.5"><span class="text-accent font-bold">✓</span><?=$t?></span>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Price card -->
    <aside aria-label="Purchase details" class="bg-white/5 border border-white/10 p-6 self-start">
      <p class="font-mono text-[.6rem] text-white/40 uppercase tracking-widest mb-2">One-time purchase</p>
      <div class="font-display font-bold text-6xl text-white"><?=PRODUCT_PRICE?></div>
      <p class="font-ui text-white/30 text-xs line-through mt-1 mb-5">Estimated value: $400+</p>
      <a href="<?=GUMROAD_URL?>" target="_blank" rel="noopener"
         class="block bg-accent text-white font-ui font-bold text-center py-3.5 text-sm hover:opacity-90 transition-opacity mb-4">
        Buy Now — Instant Download →
      </a>
      <ul class="space-y-2.5">
        <?php foreach([
          '158-page PDF blueprint','1,000+ curated workflow library',
          '50+ copy-paste ChatGPT prompts','28-day pre-decided roadmap',
          'Platform guides: Gumroad, Etsy, Lemon Squeezy',
        ] as $item): ?>
        <li class="flex items-start gap-2.5 text-sm text-white/60 font-ui">
          <span class="text-accent font-bold mt-0.5 shrink-0">→</span><?=$item?>
        </li>
        <?php endforeach; ?>
      </ul>
      <div class="mt-5 bg-white/5 border border-white/10 p-4">
        <p class="text-xs text-white/50 font-ui leading-relaxed">
          <strong class="text-accent">14-Day Guarantee.</strong> Follow Days 1–7. 
          No product listed? Full refund. No questions.
        </p>
      </div>
    </aside>
  </div>
</div>

<!-- ══ THE PROBLEM ══════════════════════════════════════════════════════════ -->
<section aria-labelledby="problem-h" class="border-b border-border">
  <div class="max-w-site mx-auto px-5 lg:px-8 py-16">
    <p class="font-mono text-[.65rem] text-muted uppercase tracking-widest mb-3">01 — The Opportunity</p>
    <h2 id="problem-h" class="font-display font-bold text-[clamp(1.75rem,3.5vw,2.75rem)] text-ink mb-10">
      2,400+ free workflows exist.<br/><span class="text-muted font-medium">Nobody's packaging them.</span>
    </h2>
    <div class="grid md:grid-cols-3 gap-px bg-border mb-10">
      <?php foreach([
        ['The gap','Developers share working automations on GitHub, Reddit, n8n.io constantly. Most businesses <strong class="text-ink">want them but can\'t find or deploy them.</strong> That gap is worth money.'],
        ['The leverage','Businesses pay $19–$97 for a packaged workflow with a clear setup guide. <strong class="text-ink">The value is the documentation</strong> — not the underlying code.'],
        ['The model','Find a workflow (free). Package it with AI docs. Sell it. Fulfill automatically. <strong class="text-ink">Repeat 15 times. Make $500/month.</strong>'],
      ] as [$t,$d]): ?>
      <div class="bg-white p-7">
        <p class="font-mono text-[.6rem] text-muted uppercase tracking-widest mb-4"><?=$t?></p>
        <p class="font-ui text-sm text-muted leading-relaxed"><?=$d?></p>
      </div>
      <?php endforeach; ?>
    </div>
    <blockquote class="bg-ink text-white p-8 max-w-2xl border-l-2 border-accent">
      <p class="font-body italic text-lg leading-snug text-white/90">
        "The automation market is worth $26B. You don't need to build the product. 
        You need to be the <em class="not-italic text-accent font-medium">bridge</em> between the builders and the buyers."
      </p>
    </blockquote>
  </div>
</section>

<!-- ══ WHAT'S INSIDE ════════════════════════════════════════════════════════ -->
<section aria-labelledby="inside-h" class="border-b border-border bg-cream">
  <div class="max-w-site mx-auto px-5 lg:px-8 py-16">
    <p class="font-mono text-[.65rem] text-muted uppercase tracking-widest mb-3">02 — What's Included</p>
    <h2 id="inside-h" class="font-display font-bold text-[clamp(1.75rem,3.5vw,2.75rem)] text-ink mb-10">
      Everything you need. Nothing you don't.
    </h2>
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-px bg-border">
      <?php foreach([
        ['01','28-Day Launch Roadmap','Day-by-day task cards. Zero decision fatigue. Products listed by Day 7. First sale target: Day 14.',false,false],
        ['02','1,000+ Workflow Library','20 real categories. Every workflow scored, priced, matched to its ideal buyer persona.',true,false],
        ['03','50+ ChatGPT Prompts','Copy → paste → done. Setup guides, listings, social posts, landing copy — all generated.',false,false],
        ['04','Workflow Sourcing System','Exact URLs, search strings, step-by-step sourcing. Find high-scoring workflows in 2 hours.',true,false],
        ['05','Packaging System','ZIP structure, naming conventions, listing templates. $47–$97 sellable product in 80 minutes.',false,false],
        ['06','Platform Playbook','Gumroad, Etsy, Lemon Squeezy setup guides. Scaling roadmap to $5K/month.',false,true],
      ] as [$n,$title,$desc,$alt,$highlight]): ?>
      <div class="p-7 <?=$highlight?'bg-accent text-white':($alt?'bg-stone':'bg-white')?>">
        <div class="font-display font-bold text-4xl leading-none mb-5 <?=$highlight?'text-white/20':'text-border'?>"><?=$n?></div>
        <h3 class="font-display font-semibold text-sm mb-2 <?=$highlight?'text-white':'text-ink'?>"><?=$title?></h3>
        <p class="font-ui text-sm leading-relaxed <?=$highlight?'text-white/70':'text-muted'?>"><?=$desc?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ══ WORKFLOW SAMPLES ══════════════════════════════════════════════════════ -->
<section aria-labelledby="workflows-h" class="border-b border-border">
  <div class="max-w-site mx-auto px-5 lg:px-8 py-16">
    <p class="font-mono text-[.65rem] text-muted uppercase tracking-widest mb-3">03 — Sample Workflows</p>
    <h2 id="workflows-h" class="font-display font-bold text-[clamp(1.75rem,3.5vw,2.75rem)] text-ink mb-3">
      1,000+ templates across 20 categories.
    </h2>
    <p class="font-ui text-muted mb-8 max-w-lg">From GitHub repos, n8n.io, and Reddit. Every workflow tested, scored, and ready to sell.</p>
    <div class="overflow-x-auto -mx-5 lg:mx-0 px-5 lg:px-0">
      <table class="w-full min-w-[600px] text-sm border border-border">
        <thead>
          <tr class="bg-ink">
            <th class="text-left font-mono text-[.6rem] tracking-widest uppercase text-white/60 px-5 py-3.5">Workflow</th>
            <th class="text-left font-mono text-[.6rem] tracking-widest uppercase text-white/60 px-5 py-3.5 hidden md:table-cell">Category</th>
            <th class="text-left font-mono text-[.6rem] tracking-widest uppercase text-white/60 px-5 py-3.5">Sell Price</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach([
            ['Agentic Telegram Bot + LangChain Memory','Context-aware AI with Supabase long-term memory','Support / Bots','$67'],
            ['Auto-Label Gmail with AI (OpenAI + Gmail)','Auto-assign smart labels, zero manual sorting','Ops / Email','$47'],
            ['Invoice Extraction: LlamaParse → CSV','Structured data extracted automatically','Finance / Admin','$77'],
            ['WhatsApp AI RAG Chatbot','Full business chatbot with memory','Customer Service','$97'],
            ['Slack + Linear Support Ticketing','Auto-create tickets from Slack emoji reactions','Support / IT','$77'],
            ['Lead Qualifier in Google Sheets (GPT-4)','Score and prioritize leads automatically','Sales / CRM','$57'],
            ['AI Resume Screener → Sheets','Vision AI parses resumes, scores fit','HR / Recruiting','$87'],
            ['Notion AI Knowledge Base','AI Q&amp;A from any Notion database','Productivity','$67'],
          ] as $i=>[$name,$desc,$cat,$price]): ?>
          <tr class="border-t border-border <?=$i%2?'bg-stone/40':'bg-white'?> hover:bg-accent-dim transition-colors group">
            <td class="px-5 py-4">
              <div class="font-ui font-semibold text-ink group-hover:text-accent transition-colors"><?=$name?></div>
              <div class="font-ui text-xs text-muted mt-0.5"><?=$desc?></div>
            </td>
            <td class="px-5 py-4 hidden md:table-cell">
              <span class="font-mono text-xs text-muted"><?=$cat?></span>
            </td>
            <td class="px-5 py-4 font-display font-bold text-lg text-ink"><?=$price?></td>
          </tr>
          <?php endforeach; ?>
          <tr class="border-t border-border bg-stone/40">
            <td colspan="2" class="px-5 py-4 text-muted font-ui text-sm italic">+ 992 more: Gmail, Discord, Airtable, DevOps, Social Media, RAG, HR, Finance…</td>
            <td class="px-5 py-4 font-display font-semibold text-muted">$17–$297</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</section>

<!-- ══ THE PROCESS ══════════════════════════════════════════════════════════ -->
<section aria-labelledby="process-h" class="border-b border-border bg-cream">
  <div class="max-w-site mx-auto px-5 lg:px-8 py-16">
    <p class="font-mono text-[.65rem] text-muted uppercase tracking-widest mb-3">04 — The Process</p>
    <h2 id="process-h" class="font-display font-bold text-[clamp(1.75rem,3.5vw,2.75rem)] text-ink mb-10">
      Zero to first sale in seven steps.
    </h2>
    <?php foreach([
      ['01','Find a free workflow','Browse n8n.io, GitHub, or Reddit using our exact search queries. Log it in your tracking sheet. 15 minutes.',false],
      ['02','Score it','8 criteria, 5 minutes. Score 12+? Sellable. Below 8? Move on. No guesswork.',false],
      ['03','Import and test in n8n','One-click import. Run it once. Export the .json file. Under 30 minutes.',false],
      ['04','Generate documentation','5 ChatGPT prompts → setup guide, credentials checklist, troubleshooting doc. 60 minutes.',false],
      ['05','Design PDFs in Canva','Template structure included. Professional output in 60–90 min. Total: ~3 hours per product.',false],
      ['06','Publish on 3 platforms','Gumroad, Etsy, Lemon Squeezy. Three revenue streams, one upload. Guides included.',false],
      ['07','Collect revenue. Repeat.','Products fulfill automatically. No calls, no shipping. Build the next while the first earns.',true],
    ] as [$n,$title,$desc,$last]): ?>
    <div class="flex gap-6 py-5 border-b <?=$last?'border-accent bg-accent-dim -mx-5 lg:-mx-8 px-5 lg:px-8':'border-border'?> items-start">
      <div class="font-display font-bold text-4xl leading-none <?=$last?'text-accent':'text-border'?> w-12 shrink-0 mt-0.5"><?=$n?></div>
      <div>
        <h3 class="font-display font-semibold text-sm text-ink mb-1"><?=$title?></h3>
        <p class="font-ui text-sm text-muted leading-relaxed"><?=$desc?></p>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- ══ COMPARISON ════════════════════════════════════════════════════════════ -->
<section aria-labelledby="compare-h" class="border-b border-border">
  <div class="max-w-site mx-auto px-5 lg:px-8 py-16">
    <p class="font-mono text-[.65rem] text-muted uppercase tracking-widest mb-3">05 — Why This Model</p>
    <h2 id="compare-h" class="font-display font-bold text-[clamp(1.75rem,3.5vw,2.75rem)] text-ink mb-8">
      vs. every other digital product model.
    </h2>
    <div class="overflow-x-auto -mx-5 lg:mx-0 px-5 lg:px-0">
      <table class="w-full min-w-[500px] text-sm">
        <thead>
          <tr class="border-b border-border">
            <th class="text-left font-mono text-[.6rem] tracking-widest uppercase text-muted px-5 py-3 bg-stone/50">Requirement</th>
            <th class="text-left font-mono text-[.6rem] tracking-widest uppercase text-white px-5 py-3 bg-ink"><?=PRODUCT_NAME?></th>
            <th class="text-left font-mono text-[.6rem] tracking-widest uppercase text-muted px-5 py-3 bg-stone/50">Other Models</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach([
            ['Coding or tech skills','✓ Zero required','Usually essential'],
            ['Upfront investment','✓ $0 to launch','$200–$2,000+'],
            ['Content creation','✓ AI generates docs','Weeks of writing'],
            ['Time per product','✓ ~80 minutes','Days to weeks'],
            ['Source material','✓ Free (open source)','Paid licenses'],
            ['Order fulfillment','✓ 100% automated','Manual delivery'],
            ['Time to first sale','✓ 7–14 days target','2–6 months'],
          ] as [$need,$ours,$theirs]): ?>
          <tr class="border-b border-border">
            <td class="px-5 py-3.5 font-ui text-muted"><?=$need?></td>
            <td class="px-5 py-3.5 font-ui font-semibold text-success bg-green-50"><?=$ours?></td>
            <td class="px-5 py-3.5 font-ui text-muted line-through opacity-50"><?=$theirs?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</section>

<!-- ══ SOCIAL PROOF ══════════════════════════════════════════════════════════ -->
<section aria-labelledby="proof-h" class="border-b border-border bg-cream">
  <div class="max-w-site mx-auto px-5 lg:px-8 py-16">
    <p class="font-mono text-[.65rem] text-muted uppercase tracking-widest mb-3">06 — Early Results</p>
    <h2 id="proof-h" class="font-display font-bold text-[clamp(1.75rem,3.5vw,2.75rem)] text-ink mb-10">
      Real buyers. Real outcomes.
    </h2>
    <div class="grid md:grid-cols-3 gap-px bg-border">
      <?php foreach([
        ['Had my first Gumroad sale on Day 9. Posted in r/n8n, one buyer. Made $47 while at the gym. Nothing I\'ve tried worked this fast.','Marcus T.','Freelance Designer → Workflow Seller'],
        ['Was a VA for 6 years. First product took 4 hours total. Sold 23 times at $47. The math is undeniable.','Samira K.','Virtual Assistant → Digital Creator'],
        ['34 products live. $1,200–$2,800/month. The workflow library alone is worth 10× what I paid.','Ryan O.','Software Tester → Full-Time Creator'],
      ] as [$q,$name,$role]): ?>
      <figure class="bg-white p-7">
        <blockquote class="font-body italic text-base leading-relaxed text-ink mb-5">"<?=$q?>"</blockquote>
        <figcaption>
          <div class="font-ui font-semibold text-sm text-ink"><?=$name?></div>
          <div class="font-ui text-xs text-muted mt-0.5"><?=$role?></div>
        </figcaption>
      </figure>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ══ FAQ ═══════════════════════════════════════════════════════════════════ -->
<section aria-labelledby="faq-h" class="border-b border-border">
  <div class="max-w-site mx-auto px-5 lg:px-8 py-16">
    <p class="font-mono text-[.65rem] text-muted uppercase tracking-widest mb-3">07 — FAQ</p>
    <h2 id="faq-h" class="font-display font-bold text-[clamp(1.75rem,3.5vw,2.75rem)] text-ink mb-10">
      Honest answers to common questions.
    </h2>
    <div class="max-w-2xl">
      <?php foreach([
        ['Do I need to know how to code?','Zero. The most technical step is importing a JSON file — one click in n8n. Everything else uses free tools and AI prompts.'],
        ['Is selling packaged workflows legal?','Yes. You\'re selling documentation, setup guides, and your curation time — not the underlying software. MIT-licensed workflows explicitly allow commercial use. The guide covers license verification.'],
        ['What does it actually cost to start?','$0 beyond this guide. n8n free tier, Canva free, ChatGPT free tier. Gumroad, Etsy, and Lemon Squeezy take a percentage of sales only.'],
        ['How realistic is $500/month?','15–20 products at avg $37 needs ~14 sales/month. Month 2 buyers typically see 5–20 sales. Month 3–6, Etsy SEO compounds on autopilot.'],
        ['What if I have no existing audience?','Designed for zero-audience starts. First sales come from Reddit communities, Etsy organic search, and LinkedIn — all covered in the 28-day roadmap.'],
        ['Why is the price so low at '.PRODUCT_PRICE.'?','We\'re in early-access pricing to build real social proof. The content took months and represents hundreds of hours of workflow research.'],
      ] as [$q,$a]): ?>
      <div class="border-b border-border py-5">
        <h3 class="font-display font-semibold text-sm text-ink mb-2"><?=$q?></h3>
        <p class="font-ui text-sm text-muted leading-relaxed"><?=$a?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ══ FINAL CTA ═════════════════════════════════════════════════════════════ -->
<section aria-label="Final purchase CTA" class="bg-ink text-white">
  <div class="max-w-site mx-auto px-5 lg:px-8 py-20 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-12">
    <h2 class="font-display font-bold text-[clamp(2rem,5vw,3.75rem)] leading-[.95]">
      Everyone who does this<br/>started exactly <em class="not-italic text-accent">where you are.</em>
    </h2>
    <div class="shrink-0 w-full lg:w-72">
      <p class="font-ui text-white/50 text-sm leading-relaxed mb-6">Not more technical. Not smarter. Just earlier. The system is ready when you are.</p>
      <a href="<?=GUMROAD_URL?>" target="_blank" rel="noopener"
         class="block bg-accent text-white font-ui font-bold text-center text-sm px-6 py-4 hover:opacity-90 transition-opacity">
        Get <?=PRODUCT_NAME?> — <?=PRODUCT_PRICE?>
      </a>
      <p class="font-mono text-[.6rem] text-white/30 uppercase tracking-widest mt-3 text-center">Instant download · 14-day guarantee</p>
    </div>
  </div>
</section>

</main>
<?php pageClose(); ?>
