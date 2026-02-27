<?php
require_once __DIR__.'/config.php';
session_start();
$msg=''; $msgType='success';

// Auth
if (isset($_POST['password'])) {
    if ($_POST['password'] === ADMIN_PASSWORD) { $_SESSION['admin'] = true; }
    else { $msg = 'Incorrect password.'; $msgType = 'error'; }
}
if (isset($_GET['logout'])) { session_destroy(); header('Location:/admin.php'); exit; }

// Actions (authenticated)
if (!empty($_SESSION['admin'])) {
    if (isset($_GET['delete']) && ($id=(int)$_GET['delete'])>0) {
        deletePost($id); $msg='Post deleted.';
    }
    if (isset($_GET['toggle']) && ($id=(int)$_GET['toggle'])>0) {
        db()->prepare('UPDATE posts SET published=1-published WHERE id=?')->execute([$id]);
        $msg='Status updated.';
    }
    if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['title'])) {
        if (trim($_POST['title'])) {
            $savedSlug = savePost($_POST); $msg = 'Post saved.';
        } else { $msg='Title is required.'; $msgType='error'; }
    }
}

$editing = null;
if (!empty($_SESSION['admin']) && isset($_GET['edit']) && ($eid=(int)$_GET['edit'])>0) {
    $s = db()->prepare('SELECT * FROM posts WHERE id=?'); $s->execute([$eid]);
    $editing = $s->fetch();
    if ($editing) $editing['tags'] = implode(', ', json_decode($editing['tags']??'[]',true));
}

$posts = $stats = [];
if (!empty($_SESSION['admin'])) {
    // Search/filter
    $search = trim($_GET['q']??'');
    $filterCat = trim($_GET['fcat']??'');
    $where = []; $params = [];
    if ($search) { $where[] = '(title LIKE ? OR excerpt LIKE ?)'; $params[]="%$search%"; $params[]="%$search%"; }
    if ($filterCat) { $where[] = 'category=?'; $params[]=$filterCat; }
    $sql = 'SELECT * FROM posts'.($where?' WHERE '.implode(' AND ',$where):'').' ORDER BY created_at DESC';
    $s = db()->prepare($sql); $s->execute($params);
    $posts = $s->fetchAll();
    $stats = db()->query('SELECT COUNT(*) total, SUM(published) live, SUM(1-published) drafts, SUM(views) views FROM posts')->fetch();
    $allCats = db()->query('SELECT DISTINCT category FROM posts ORDER BY category')->fetchAll(PDO::FETCH_COLUMN);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/><meta name="viewport" content="width=device-width,initial-scale=1"/>
<title>Admin · Grova</title>
<script src="https://cdn.tailwindcss.com"></script>
<script>tailwind.config={theme:{extend:{fontFamily:{ui:['"Instrument Sans"','"system-ui"'],mono:['"JetBrains Mono"','monospace']}}}}</script>
<link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600&family=JetBrains+Mono:wght@400&display=swap" rel="stylesheet"/>
<style>
*{box-sizing:border-box;margin:0;padding:0;}
body{-webkit-font-smoothing:antialiased;font-family:'Instrument Sans',system-ui,sans-serif;}
input,textarea,select{outline:none!important;transition:border-color .12s;}
input:focus,textarea:focus,select:focus{border-color:#2C42FF!important;}
.sidebar-link{display:flex;align-items:center;gap:.6rem;padding:.5rem .75rem;border-radius:4px;font-size:.82rem;font-weight:500;color:#a1a1aa;transition:background .12s,color .12s;}
.sidebar-link:hover,.sidebar-link.active{background:#1f1f27;color:#fff;}
</style>
</head>
<body class="bg-zinc-950 text-zinc-100 min-h-screen">

<?php if (empty($_SESSION['admin'])): ?>
<!-- ── Login ─────────────────────────────────────────────────────────────── -->
<div class="min-h-screen flex items-center justify-center px-5">
  <div class="w-full max-w-sm">
    <p class="font-mono text-xs text-zinc-500 uppercase tracking-widest mb-8">Grova · Admin</p>
    <?php if($msg): ?><p class="text-red-400 text-sm mb-4"><?=e($msg)?></p><?php endif; ?>
    <form method="POST" class="space-y-3">
      <input name="password" type="password" placeholder="Password" autofocus
        class="w-full bg-zinc-900 border border-zinc-800 px-4 py-3 text-sm text-white placeholder-zinc-600"/>
      <button type="submit" class="w-full bg-[#2C42FF] text-white font-semibold text-sm py-3 hover:opacity-90 transition-opacity">Sign In</button>
    </form>
  </div>
</div>

<?php else: ?>
<!-- ── Dashboard ─────────────────────────────────────────────────────────── -->
<div class="flex">

  <!-- Sidebar -->
  <aside class="hidden lg:flex flex-col w-56 bg-zinc-900 border-r border-zinc-800 fixed h-screen overflow-y-auto shrink-0">
    <div class="p-5 border-b border-zinc-800">
      <a href="/index.php" target="_blank" class="font-semibold text-base text-white">Grova<span class="text-[#2C42FF]">.</span></a>
      <p class="text-xs text-zinc-500 mt-0.5 font-mono">Admin Panel</p>
    </div>
    <nav class="p-3 flex-1">
      <p class="font-mono text-[.6rem] text-zinc-600 uppercase tracking-widest px-3 mb-2 mt-2">Content</p>
      <a href="/admin.php" class="sidebar-link <?=!isset($_GET['edit'])&&!isset($_GET['new'])?'active':''?>">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
        All Posts
      </a>
      <a href="/admin.php?new=1" class="sidebar-link <?=isset($_GET['new'])?'active':''?>">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M12 4v16M4 12h16"/></svg>
        New Post
      </a>
      <p class="font-mono text-[.6rem] text-zinc-600 uppercase tracking-widest px-3 mb-2 mt-4">Site</p>
      <a href="/index.php" target="_blank" class="sidebar-link">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
        View Site
      </a>
      <a href="/blog.php" target="_blank" class="sidebar-link">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l6 6v10a2 2 0 01-2 2z"/></svg>
        View Blog
      </a>
    </nav>
    <div class="p-3 border-t border-zinc-800">
      <a href="?logout=1" class="sidebar-link text-red-400 hover:text-red-300">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
        Logout
      </a>
    </div>
  </aside>

  <!-- Main -->
  <main class="lg:ml-56 flex-1 p-5 lg:p-8 min-h-screen">

    <!-- Mobile top bar -->
    <div class="lg:hidden flex justify-between items-center mb-6 pb-4 border-b border-zinc-800">
      <span class="font-semibold text-white">Grova Admin</span>
      <div class="flex gap-2">
        <a href="?new=1" class="bg-[#2C42FF] text-white text-xs font-semibold px-3 py-1.5">+ New</a>
        <a href="?logout=1" class="text-xs text-zinc-500 border border-zinc-800 px-3 py-1.5">Logout</a>
      </div>
    </div>

    <!-- Message -->
    <?php if($msg): ?>
    <div class="mb-5 px-4 py-3 border text-sm <?=$msgType==='error'?'border-red-800 bg-red-900/20 text-red-300':'border-green-800 bg-green-900/20 text-green-300'?>"><?=e($msg)?></div>
    <?php endif; ?>

    <?php if (!isset($_GET['new']) && !isset($_GET['edit'])): ?>
    <!-- ── ALL POSTS VIEW ──────────────────────────────────────────────── -->
    <div class="mb-7">
      <h1 class="text-xl font-semibold text-white mb-1">Blog Posts</h1>
      <p class="text-xs text-zinc-500 font-mono">Manage and publish your content</p>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-7">
      <?php foreach([
        ['Total Posts',$stats['total']??0,'#a1a1aa'],
        ['Published',$stats['live']??0,'#4ade80'],
        ['Drafts',$stats['drafts']??0,'#fbbf24'],
        ['Total Views',number_format($stats['views']??0),'#60a5fa'],
      ] as [$l,$v,$c]): ?>
      <div class="bg-zinc-900 border border-zinc-800 p-4">
        <div class="text-2xl font-semibold text-white"><?=$v?></div>
        <div class="text-xs mt-1 font-mono" style="color:<?=$c?>"><?=$l?></div>
      </div>
      <?php endforeach; ?>
    </div>

    <!-- API info -->
    <div class="bg-zinc-900 border border-zinc-800 p-4 mb-6">
      <p class="font-mono text-xs text-zinc-500 mb-1">n8n / Python automation endpoint:</p>
      <div class="flex flex-wrap gap-3 items-center">
        <code class="text-[#2C42FF] text-sm">POST <?=SITE_URL?>/api/create-post.php</code>
        <span class="font-mono text-xs text-zinc-600">Authorization: Bearer <?=API_SECRET?></span>
      </div>
    </div>

    <!-- Search + filter -->
    <form method="GET" class="flex flex-wrap gap-3 mb-5">
      <input name="q" type="search" value="<?=e($search)?>" placeholder="Search posts…"
        class="bg-zinc-900 border border-zinc-800 text-white placeholder-zinc-600 px-3 py-2 text-sm flex-1 min-w-[180px]"/>
      <select name="fcat" class="bg-zinc-900 border border-zinc-800 text-white px-3 py-2 text-sm">
        <option value="">All categories</option>
        <?php foreach($allCats as $c): ?>
        <option value="<?=e($c)?>" <?=$filterCat===$c?'selected':''?>><?=e($c)?></option>
        <?php endforeach; ?>
      </select>
      <button type="submit" class="bg-zinc-800 text-white text-sm px-4 py-2 hover:bg-zinc-700 transition-colors">Filter</button>
      <?php if($search||$filterCat): ?><a href="/admin.php" class="text-zinc-500 text-sm px-4 py-2 hover:text-white transition-colors">Clear</a><?php endif; ?>
    </form>

    <!-- Posts table -->
    <div class="border border-zinc-800 overflow-x-auto">
      <?php if(!$posts): ?>
      <div class="p-10 text-center text-zinc-500 text-sm">
        No posts found. <a href="?new=1" class="text-[#2C42FF] hover:underline">Create your first post →</a>
      </div>
      <?php else: ?>
      <table class="w-full text-sm">
        <thead class="border-b border-zinc-800">
          <tr>
            <th class="text-left px-4 py-3 font-mono text-[.6rem] text-zinc-500 uppercase tracking-widest">Title</th>
            <th class="text-left px-4 py-3 font-mono text-[.6rem] text-zinc-500 uppercase tracking-widest hidden md:table-cell">Category</th>
            <th class="text-left px-4 py-3 font-mono text-[.6rem] text-zinc-500 uppercase tracking-widest hidden lg:table-cell">Date</th>
            <th class="text-left px-4 py-3 font-mono text-[.6rem] text-zinc-500 uppercase tracking-widest hidden lg:table-cell">Views</th>
            <th class="text-left px-4 py-3 font-mono text-[.6rem] text-zinc-500 uppercase tracking-widest">Status</th>
            <th class="px-4 py-3"></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($posts as $p): ?>
          <tr class="border-b border-zinc-800/60 hover:bg-zinc-900/40 transition-colors">
            <td class="px-4 py-3">
              <a href="/post.php?slug=<?=e($p['slug'])?>" target="_blank" class="font-medium text-white hover:text-[#2C42FF] transition-colors">
                <?=e($p['title'])?>
              </a>
              <div class="font-mono text-[.6rem] text-zinc-600 mt-0.5">slug: <?=e($p['slug'])?></div>
            </td>
            <td class="px-4 py-3 text-zinc-500 hidden md:table-cell"><?=e($p['category']??'')?></td>
            <td class="px-4 py-3 text-zinc-500 font-mono text-xs hidden lg:table-cell"><?=date('M j, Y',strtotime($p['created_at']))?></td>
            <td class="px-4 py-3 text-zinc-500 font-mono text-xs hidden lg:table-cell"><?=number_format($p['views']??0)?></td>
            <td class="px-4 py-3">
              <a href="?toggle=<?=$p['id']?>" class="inline-block font-mono text-[.6rem] uppercase tracking-widest px-2 py-0.5 border transition-colors <?=$p['published']?'border-green-700 text-green-400 hover:bg-green-900/20':'border-zinc-700 text-zinc-500 hover:bg-zinc-800'?>">
                <?=$p['published']?'Live':'Draft'?>
              </a>
            </td>
            <td class="px-4 py-3 text-right whitespace-nowrap">
              <a href="?edit=<?=$p['id']?>" class="text-xs text-zinc-400 hover:text-white transition-colors mr-3">Edit</a>
              <a href="?delete=<?=$p['id']?>" onclick="return confirm('Delete \'<?=e(addslashes($p['title']))?>\'?')"
                 class="text-xs text-red-500 hover:text-red-400 transition-colors">Delete</a>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <?php endif; ?>
    </div>

    <?php else: ?>
    <!-- ── POST FORM ──────────────────────────────────────────────────── -->
    <div class="flex justify-between items-center mb-6 flex-wrap gap-3">
      <div>
        <h1 class="text-xl font-semibold text-white"><?=$editing?'Edit Post':'New Post'?></h1>
        <?php if($editing): ?><p class="text-xs text-zinc-500 font-mono mt-0.5">slug: <?=e($editing['slug']??'')?></p><?php endif; ?>
      </div>
      <a href="/admin.php" class="text-xs text-zinc-500 border border-zinc-800 px-3 py-1.5 hover:text-white transition-colors">← Back to Posts</a>
    </div>

    <form method="POST" class="space-y-5 max-w-4xl">
      <!-- Row 1: Title + Slug -->
      <div class="grid md:grid-cols-2 gap-4">
        <div>
          <label class="block font-mono text-[.65rem] text-zinc-400 uppercase tracking-widest mb-1.5">Title *</label>
          <input name="title" type="text" required value="<?=e($editing['title']??'')?>"
            class="w-full bg-zinc-900 border border-zinc-700 text-white px-3 py-2.5 text-sm"/>
        </div>
        <div>
          <label class="block font-mono text-[.65rem] text-zinc-400 uppercase tracking-widest mb-1.5">Slug <span class="text-zinc-600">(auto from title)</span></label>
          <input name="slug" type="text" value="<?=e($editing['slug']??'')?>"
            class="w-full bg-zinc-900 border border-zinc-700 text-white px-3 py-2.5 text-sm font-mono"/>
        </div>
      </div>

      <!-- Row 2: Excerpt -->
      <div>
        <label class="block font-mono text-[.65rem] text-zinc-400 uppercase tracking-widest mb-1.5">Excerpt <span class="text-zinc-600">(shown in cards & SEO description)</span></label>
        <input name="excerpt" type="text" value="<?=e($editing['excerpt']??'')?>"
          class="w-full bg-zinc-900 border border-zinc-700 text-white px-3 py-2.5 text-sm"/>
      </div>

      <!-- Row 3: SEO -->
      <div class="grid md:grid-cols-2 gap-4 border border-zinc-800 p-4">
        <div>
          <label class="block font-mono text-[.65rem] text-zinc-400 uppercase tracking-widest mb-1.5">SEO Title <span class="text-zinc-600">(overrides title)</span></label>
          <input name="meta_title" type="text" value="<?=e($editing['meta_title']??'')?>"
            class="w-full bg-zinc-900 border border-zinc-700 text-white px-3 py-2.5 text-sm"/>
        </div>
        <div>
          <label class="block font-mono text-[.65rem] text-zinc-400 uppercase tracking-widest mb-1.5">SEO Description <span class="text-zinc-600">(overrides excerpt)</span></label>
          <input name="meta_desc" type="text" value="<?=e($editing['meta_desc']??'')?>"
            class="w-full bg-zinc-900 border border-zinc-700 text-white px-3 py-2.5 text-sm"/>
        </div>
      </div>

      <!-- Row 4: Meta fields -->
      <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div>
          <label class="block font-mono text-[.65rem] text-zinc-400 uppercase tracking-widest mb-1.5">Category</label>
          <select name="category" class="w-full bg-zinc-900 border border-zinc-700 text-white px-3 py-2.5 text-sm">
            <?php foreach(['Guide','Tutorial','Analysis','Case Study','News','Article'] as $c): ?>
            <option value="<?=$c?>" <?=($editing['category']??'Guide')===$c?'selected':''?>><?=$c?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div>
          <label class="block font-mono text-[.65rem] text-zinc-400 uppercase tracking-widest mb-1.5">Read Time (min)</label>
          <input name="read_time" type="number" min="1" max="120" value="<?=$editing['read_time']??5?>"
            class="w-full bg-zinc-900 border border-zinc-700 text-white px-3 py-2.5 text-sm"/>
        </div>
        <div>
          <label class="block font-mono text-[.65rem] text-zinc-400 uppercase tracking-widest mb-1.5">Schema Type</label>
          <select name="schema_type" class="w-full bg-zinc-900 border border-zinc-700 text-white px-3 py-2.5 text-sm">
            <?php foreach(['Article','HowTo','NewsArticle','BlogPosting','TechArticle'] as $st): ?>
            <option value="<?=$st?>" <?=($editing['schema_type']??'Article')===$st?'selected':''?>><?=$st?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div>
          <label class="block font-mono text-[.65rem] text-zinc-400 uppercase tracking-widest mb-1.5">Tags <span class="text-zinc-600">(comma sep.)</span></label>
          <input name="tags" type="text" value="<?=e($editing['tags']??'')?>" placeholder="n8n, automation"
            class="w-full bg-zinc-900 border border-zinc-700 text-white px-3 py-2.5 text-sm"/>
        </div>
      </div>

      <!-- Featured image -->
      <div>
        <label class="block font-mono text-[.65rem] text-zinc-400 uppercase tracking-widest mb-1.5">Featured Image URL <span class="text-zinc-600">(1200×630 for OG)</span></label>
        <input name="featured_image" type="url" value="<?=e($editing['featured_image']??'')?>" placeholder="https://..."
          class="w-full bg-zinc-900 border border-zinc-700 text-white px-3 py-2.5 text-sm"/>
      </div>

      <!-- Content -->
      <div>
        <label class="block font-mono text-[.65rem] text-zinc-400 uppercase tracking-widest mb-1.5">Content (HTML) *</label>
        <textarea name="content" rows="22" required
          class="w-full bg-zinc-900 border border-zinc-700 text-white px-3 py-2.5 text-sm font-mono resize-y leading-relaxed"><?=e($editing['content']??'')?></textarea>
        <p class="text-[.65rem] text-zinc-600 mt-1.5 font-mono">Supports full HTML. Use &lt;h2&gt;, &lt;h3&gt;, &lt;p&gt;, &lt;ul&gt;, &lt;strong&gt;, &lt;blockquote&gt;, &lt;a&gt;, &lt;code&gt;</p>
      </div>

      <!-- Publish row -->
      <div class="flex justify-between items-center pt-2 border-t border-zinc-800">
        <label class="flex items-center gap-2.5 cursor-pointer">
          <input type="checkbox" name="published" value="1" <?=($editing['published']??0)?'checked':''?> class="w-4 h-4 accent-[#2C42FF]"/>
          <span class="text-sm text-zinc-300">Published — visible on site</span>
        </label>
        <button type="submit" class="bg-[#2C42FF] text-white font-semibold text-sm px-8 py-2.5 hover:opacity-90 transition-opacity">
          Save Post
        </button>
      </div>
    </form>
    <?php endif; ?>

  </main>
</div>
<?php endif; ?>
</body></html>
