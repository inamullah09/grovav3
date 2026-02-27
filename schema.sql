-- ══════════════════════════════════════════════════════════
-- GROVA DATABASE SCHEMA
-- Run: mysql -u root -p < schema.sql
-- ══════════════════════════════════════════════════════════

CREATE DATABASE IF NOT EXISTS grova
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE grova;

-- ── Posts ──────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS posts (
  id             INT UNSIGNED    NOT NULL AUTO_INCREMENT,
  title          VARCHAR(255)    NOT NULL,
  slug           VARCHAR(255)    NOT NULL,
  excerpt        TEXT,
  content        LONGTEXT,
  meta_title     VARCHAR(255)    DEFAULT NULL,
  meta_desc      VARCHAR(320)    DEFAULT NULL,
  category       VARCHAR(100)    NOT NULL DEFAULT 'Article',
  tags           JSON            DEFAULT NULL,
  featured_image VARCHAR(500)    DEFAULT NULL,
  read_time      TINYINT UNSIGNED DEFAULT 5,
  published      TINYINT(1)      NOT NULL DEFAULT 0,
  views          INT UNSIGNED    NOT NULL DEFAULT 0,
  schema_type    VARCHAR(50)     NOT NULL DEFAULT 'Article',
  created_at     TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at     TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  PRIMARY KEY (id),
  UNIQUE  KEY uk_slug        (slug),
  INDEX   idx_published      (published),
  INDEX   idx_category       (category),
  INDEX   idx_created        (created_at),
  INDEX   idx_pub_cat        (published, category),
  FULLTEXT idx_search        (title, excerpt)

) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

-- ── Sample post ───────────────────────────────────────────
INSERT IGNORE INTO posts
  (title, slug, excerpt, content, category, tags, read_time, published, schema_type)
VALUES (
  'The Complete Guide to Selling n8n Workflow Templates in 2026',
  'selling-n8n-workflow-templates-2026',
  'Sourcing, scoring, packaging, pricing, and listing automation templates as sellable digital products. Zero coding required.',
  '<p>There are thousands of free, working automation workflows on GitHub and n8n.io right now. Most businesses want them — most can\'t find, configure, or deploy them. <strong>That gap is worth money.</strong></p>

<h2>Why This Opportunity Exists in 2026</h2>
<p>The automation market is worth $26B and growing. Developers freely share working workflows because the value for them is in building — not packaging. Businesses will pay $19–$97 for a workflow that\'s been packaged with clear documentation, because their time is worth more than the product cost.</p>

<blockquote><p>The value is never the code. It\'s the documentation, the clarity, and the confidence that something will work.</p></blockquote>

<h2>Step 1: Finding Sellable Workflows</h2>
<p>Use three primary sources:</p>
<ul>
<li><strong>n8n.io/workflows</strong> — The official template library. Filter by category, sort by newest.</li>
<li><strong>GitHub</strong> — Search "n8n workflow" filtered to recently updated repos.</li>
<li><strong>Reddit r/n8n</strong> — Community shares real, tested workflows. Comments confirm what works.</li>
</ul>

<h2>Step 2: Score Before You Package</h2>
<p>Use the 8-point Sellability Scorecard (included in AutoFlow Blueprint) to evaluate each workflow before investing time. Target score: 12+/16. Below 8? Move on immediately.</p>

<h2>Step 3: The 80-Minute Product</h2>
<p>A sellable product needs: a Setup Guide, a Credentials Checklist, and a Troubleshooting section. Use the 5 included ChatGPT prompts — paste the workflow JSON and get all three documents in under 60 minutes. Design the PDF in Canva using the included template.</p>

<h2>Step 4: Publish on 3 Platforms Simultaneously</h2>
<p><strong>Gumroad</strong> for direct traffic and simplest setup. <strong>Etsy</strong> for long-term organic SEO that compounds month over month. <strong>Lemon Squeezy</strong> for international buyers and better VAT handling.</p>

<p>One upload. Three revenue streams. The full setup guides for each platform are inside AutoFlow Blueprint.</p>',
  'Guide',
  '["n8n","automation","digital products","passive income","workflows"]',
  12, 1, 'HowTo'
);
