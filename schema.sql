-- Run this once to set up the database
-- mysql -u root -p < schema.sql

CREATE DATABASE IF NOT EXISTS grova CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE grova;

CREATE TABLE IF NOT EXISTS posts (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  title       VARCHAR(255)  NOT NULL,
  slug        VARCHAR(255)  NOT NULL UNIQUE,
  excerpt     TEXT,
  category    VARCHAR(100)  DEFAULT 'Article',
  content     LONGTEXT,
  tags        JSON,
  read_time   TINYINT       DEFAULT 5,
  published   TINYINT(1)    DEFAULT 0,
  views       INT           DEFAULT 0,
  created_at  TIMESTAMP     DEFAULT CURRENT_TIMESTAMP,
  updated_at  TIMESTAMP     DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_published (published),
  INDEX idx_slug      (slug),
  INDEX idx_created   (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Sample post
INSERT IGNORE INTO posts (title, slug, excerpt, category, content, tags, read_time, published) VALUES (
  'The Complete Guide to Selling n8n Workflow Templates in 2026',
  'selling-n8n-workflow-templates-2026',
  'Sourcing, scoring, packaging, pricing, and listing automation templates as sellable digital products. Zero coding required.',
  'Guide',
  '<p>There are thousands of free, working automation workflows on GitHub and n8n.io right now. Most businesses want them — most can''t find, use, or package them. <strong>That gap is worth money.</strong></p>\n<h2>Why This Opportunity Exists</h2>\n<p>The automation market is growing fast, but there''s a massive divide between builders who create free workflows and buyers who need automation but lack technical skills. You can be the bridge.</p>\n<blockquote><p>The value is never the code. It''s the documentation, the clarity, and the confidence that something will work.</p></blockquote>\n<h2>Step 1: Finding Sellable Workflows</h2>\n<p>Start with three sources: <strong>n8n.io/workflows</strong> (the official template library), <strong>GitHub</strong> (search for "n8n workflow"), and <strong>Reddit r/n8n</strong> where people share real working automations daily.</p>\n<h2>Step 2: Score Before You Package</h2>\n<p>Not every workflow is worth packaging. Score each one across 8 criteria before investing time. Aim for 12+ out of 16. The full scorecard is inside AutoFlow Blueprint.</p>\n<h2>Step 3: Package in 80 Minutes</h2>\n<p>A sellable product needs three documents: a Setup Guide, a Credentials Checklist, and a Troubleshooting section. Use the included ChatGPT prompts — paste the workflow JSON and get all three documents in under 60 minutes.</p>',
  '["n8n","automation","digital products","passive income"]',
  12, 1
);
