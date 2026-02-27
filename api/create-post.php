<?php
/**
 * GROVA BLOG API — Create / Update Posts
 * ──────────────────────────────────────
 * POST /api/create-post.php
 * Authorization: Bearer YOUR_API_SECRET
 * Content-Type: application/json
 *
 * PAYLOAD STRUCTURE (recommended API post object):
 * {
 *   "title":          "string — required",
 *   "content":        "HTML string — required",
 *   "slug":           "string — optional, auto-generated from title",
 *   "excerpt":        "string — shown in cards and SEO meta",
 *   "meta_title":     "string — overrides <title> tag (defaults to title)",
 *   "meta_desc":      "string — overrides meta description (defaults to excerpt)",
 *   "category":       "Guide|Tutorial|Analysis|Case Study|Article",
 *   "tags":           ["array", "of", "strings"],
 *   "featured_image": "https://... — 1200×630px for OG image",
 *   "read_time":      5,
 *   "published":      true,
 *   "schema_type":    "Article|HowTo|NewsArticle|BlogPosting|TechArticle"
 * }
 *
 * RESPONSES:
 *   200 { "ok": true, "slug": "post-slug", "url": "/post.php?slug=post-slug" }
 *   401 { "ok": false, "error": "Unauthorized" }
 *   422 { "ok": false, "error": "title and content required" }
 *   500 { "ok": false, "error": "Database error message" }
 */

require_once __DIR__.'/../config.php';

header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');

// Method check
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok'=>false,'error'=>'Method not allowed']);
    exit;
}

// Auth
$auth = trim(
    str_replace('Bearer ','',
        $_SERVER['HTTP_AUTHORIZATION']
        ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION']
        ?? getallheaders()['Authorization']
        ?? ''
    )
);
if ($auth !== API_SECRET) {
    http_response_code(401);
    echo json_encode(['ok'=>false,'error'=>'Unauthorized']);
    exit;
}

// Parse body
$raw  = file_get_contents('php://input');
$body = json_decode($raw, true);
if (!$body || json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    echo json_encode(['ok'=>false,'error'=>'Invalid JSON: '.json_last_error_msg()]);
    exit;
}

$title   = trim($body['title']   ?? '');
$content = trim($body['content'] ?? '');

if (!$title || !$content) {
    http_response_code(422);
    echo json_encode(['ok'=>false,'error'=>'title and content are required']);
    exit;
}

// Build post data
$data = [
    'title'          => $title,
    'slug'           => trim($body['slug'] ?? ''),
    'excerpt'        => trim($body['excerpt'] ?? ''),
    'meta_title'     => trim($body['meta_title'] ?? ''),
    'meta_desc'      => trim($body['meta_desc'] ?? ''),
    'category'       => trim($body['category'] ?? 'Article'),
    'content'        => $content,
    'tags'           => is_array($body['tags'] ?? null)
                        ? implode(',', $body['tags'])
                        : (string)($body['tags'] ?? ''),
    'featured_image' => trim($body['featured_image'] ?? ''),
    'read_time'      => max(1, (int)($body['read_time'] ?? 5)),
    'published'      => (int)(bool)($body['published'] ?? false),
    'schema_type'    => in_array($body['schema_type']??'',['Article','HowTo','NewsArticle','BlogPosting','TechArticle'])
                        ? $body['schema_type'] : 'Article',
];

try {
    $slug = savePost($data);
    $url  = '/post.php?slug='.$slug;
    echo json_encode([
        'ok'   => true,
        'slug' => $slug,
        'url'  => $url,
        'full_url' => SITE_URL.$url,
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['ok'=>false,'error'=>$e->getMessage()]);
}
