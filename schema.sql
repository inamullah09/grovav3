-- ══════════════════════════════════════════════════════════
-- GROVA — SQLite Schema (reference only)
-- NOTE: Auto-created by config.php on first run.
-- You do NOT need to run this manually.
-- DB file location: set DB_PATH in config.php
-- ══════════════════════════════════════════════════════════

CREATE TABLE IF NOT EXISTS posts (
    id             INTEGER PRIMARY KEY AUTOINCREMENT,
    title          TEXT    NOT NULL,
    slug           TEXT    NOT NULL UNIQUE,
    excerpt        TEXT    DEFAULT '',
    content        TEXT    DEFAULT '',
    meta_title     TEXT    DEFAULT '',
    meta_desc      TEXT    DEFAULT '',
    category       TEXT    NOT NULL DEFAULT 'Article',
    tags           TEXT    DEFAULT '[]',
    featured_image TEXT    DEFAULT '',
    read_time      INTEGER DEFAULT 5,
    published      INTEGER NOT NULL DEFAULT 0,
    views          INTEGER NOT NULL DEFAULT 0,
    schema_type    TEXT    NOT NULL DEFAULT 'Article',
    created_at     TEXT    NOT NULL DEFAULT (datetime('now')),
    updated_at     TEXT    NOT NULL DEFAULT (datetime('now'))
);

CREATE INDEX IF NOT EXISTS idx_published ON posts(published);
CREATE INDEX IF NOT EXISTS idx_category  ON posts(category);
CREATE INDEX IF NOT EXISTS idx_created   ON posts(created_at DESC);
CREATE INDEX IF NOT EXISTS idx_pub_cat   ON posts(published, category);
