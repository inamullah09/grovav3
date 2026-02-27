<?php
require_once __DIR__.'/config.php';
session_start();
$msg='';$msgType='green';

if(isset($_POST['password'])){
  if($_POST['password']===ADMIN_PASSWORD){$_SESSION['admin']=true;}
  else{$msg='Wrong password.';$msgType='red';}
}
if(isset($_GET['logout'])){session_destroy();header('Location:/admin.php');exit;}

if(!empty($_SESSION['admin'])){
  if(isset($_GET['delete'])&&(int)$_GET['delete']>0){
    deletePost((int)$_GET['delete']);$msg='Post deleted.';
  }
  if(isset($_GET['toggle'])&&(int)$_GET['toggle']>0){
    $r=db()->prepare('UPDATE posts SET published=1-published WHERE id=?');$r->execute([(int)$_GET['toggle']]);$msg='Post updated.';
  }
  if($_SERVER['REQUEST_METHOD']==='POST'&&isset($_POST['title'])){
    if(trim($_POST['title'])){
      savePost($_POST);$msg='Post saved.';$msgType='green';
    } else {$msg='Title required.';$msgType='red';}
  }
}

$editing=null;
if(!empty($_SESSION['admin'])&&isset($_GET['edit'])&&(int)$_GET['edit']>0){
  $s=db()->prepare('SELECT * FROM posts WHERE id=?');$s->execute([(int)$_GET['edit']]);$editing=$s->fetch();
  if($editing){$editing['tags']=implode(', ',json_decode($editing['tags']??'[]',true));}
}
$posts=!empty($_SESSION['admin'])?getAllPosts(false):[];
$stats=[];
if(!empty($_SESSION['admin'])){
  $stats=db()->query('SELECT COUNT(*) total, SUM(published) live, SUM(views) views FROM posts')->fetch();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/><meta name="viewport" content="width=device-width,initial-scale=1"/>
<title>Admin · Grova</title>
<script src="https://cdn.tailwindcss.com"></script>
<script>tailwind.config={theme:{extend:{fontFamily:{body:['"Plus Jakarta Sans"','sans-serif'],mono:['"JetBrains Mono"','monospace']}}}}</script>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400&display=swap" rel="stylesheet"/>
<style>body{-webkit-font-smoothing:antialiased;}input,textarea,select{outline:none!important;}input:focus,textarea:focus,select:focus{border-color:#f5c842!important;}</style>
</head>
<body class="bg-zinc-950 text-zinc-100 font-body min-h-screen text-sm">

<?php if(empty($_SESSION['admin'])): ?>
<div class="min-h-screen flex items-center justify-center px-4">
  <div class="w-full max-w-sm">
    <div class="font-mono text-xs text-zinc-500 uppercase tracking-widest mb-8">Grova · Admin</div>
    <form method="POST" class="space-y-3">
      <input name="password" type="password" placeholder="Password" autofocus class="w-full bg-zinc-900 border border-zinc-800 px-4 py-3 text-sm placeholder-zinc-600"/>
      <button type="submit" class="w-full bg-[#f5c842] text-zinc-950 font-bold py-3 hover:opacity-90">Sign In</button>
    </form>
    <?php if($msg): ?><p class="text-red-400 text-xs mt-3"><?=e($msg)?></p><?php endif; ?>
  </div>
</div>

<?php else: ?>

<!-- sidebar layout -->
<div class="flex min-h-screen">
  <!-- sidebar -->
  <aside class="hidden md:flex flex-col w-52 bg-zinc-900 border-r border-zinc-800 p-5 gap-1 fixed h-full">
    <a href="/index.php" class="font-bold text-base mb-6 block">Gro<span class="bg-[#f5c842] text-zinc-950 px-0.5">v</span>a</a>
    <a href="/admin.php" class="flex items-center gap-2 px-3 py-2 rounded text-zinc-300 hover:text-white hover:bg-zinc-800 <?=!isset($_GET['edit'])&&!isset($_GET['new'])?'bg-zinc-800 text-white':''?>">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg> Posts
    </a>
    <a href="/admin.php?new=1" class="flex items-center gap-2 px-3 py-2 rounded text-zinc-300 hover:text-white hover:bg-zinc-800 <?=isset($_GET['new'])||isset($_GET['edit'])?'bg-zinc-800 text-white':''?>">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4v16M4 12h16"/></svg> New Post
    </a>
    <div class="mt-auto">
      <a href="/index.php" target="_blank" class="flex items-center gap-2 px-3 py-2 text-zinc-500 hover:text-zinc-300 text-xs">↗ View Site</a>
      <a href="?logout=1" class="flex items-center gap-2 px-3 py-2 text-zinc-500 hover:text-zinc-300 text-xs">→ Logout</a>
    </div>
  </aside>

  <!-- main -->
  <main class="md:ml-52 flex-1 p-5 md:p-8">
    <!-- mobile top bar -->
    <div class="md:hidden flex justify-between items-center mb-6">
      <span class="font-bold">Admin</span>
      <div class="flex gap-3">
        <a href="?new=1" class="bg-[#f5c842] text-zinc-950 text-xs font-bold px-3 py-1.5">+ New</a>
        <a href="?logout=1" class="text-xs text-zinc-500 border border-zinc-800 px-3 py-1.5">Logout</a>
      </div>
    </div>

    <?php if($msg): ?>
    <div class="bg-<?=$msgType==='red'?'red':'green'?>-900/30 border border-<?=$msgType==='red'?'red':'green'?>-800 text-<?=$msgType==='red'?'red':'green'?>-300 px-4 py-3 mb-6 text-sm"><?=e($msg)?></div>
    <?php endif; ?>

    <?php if(!isset($_GET['new'])&&!isset($_GET['edit'])): ?>
    <!-- DASHBOARD -->
    <div class="mb-8">
      <h1 class="text-xl font-bold mb-1">Dashboard</h1>
      <p class="text-zinc-500 text-xs">Manage your blog posts</p>
    </div>

    <!-- stats -->
    <div class="grid grid-cols-3 gap-4 mb-8">
      <?php foreach([
        ['Total Posts',$stats['total']??0],
        ['Live',$stats['live']??0],
        ['Total Views',$stats['views']??0],
      ] as [$l,$v]): ?>
      <div class="bg-zinc-900 border border-zinc-800 p-5">
        <div class="text-2xl font-bold text-white"><?=$v?></div>
        <div class="text-xs text-zinc-500 mt-1 font-mono uppercase tracking-widest"><?=$l?></div>
      </div>
      <?php endforeach; ?>
    </div>

    <!-- API box -->
    <div class="bg-zinc-900 border border-zinc-800 p-4 mb-6 text-xs">
      <p class="text-zinc-400 font-mono mb-1">n8n / Python API:</p>
      <code class="text-[#f5c842]">POST /api/create-post.php</code>
      <span class="text-zinc-600 ml-3 hidden md:inline">Authorization: Bearer <?=API_SECRET?></span>
    </div>

    <!-- posts table -->
    <div class="border border-zinc-800 overflow-x-auto">
      <?php if(!$posts): ?>
      <div class="p-8 text-center text-zinc-500">No posts yet. <a href="?new=1" class="text-[#f5c842]">Create one →</a></div>
      <?php else: ?>
      <table class="w-full">
        <thead class="border-b border-zinc-800">
          <tr>
            <th class="text-left px-4 py-3 font-mono text-xs text-zinc-500 uppercase tracking-widest">Title</th>
            <th class="text-left px-4 py-3 font-mono text-xs text-zinc-500 uppercase tracking-widest hidden md:table-cell">Category</th>
            <th class="text-left px-4 py-3 font-mono text-xs text-zinc-500 uppercase tracking-widest hidden md:table-cell">Views</th>
            <th class="text-left px-4 py-3 font-mono text-xs text-zinc-500 uppercase tracking-widest">Status</th>
            <th class="px-4 py-3"></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($posts as $p): ?>
          <tr class="border-b border-zinc-800/60 hover:bg-zinc-900/50">
            <td class="px-4 py-3">
              <a href="/post.php?slug=<?=$p['slug']?>" target="_blank" class="hover:text-[#f5c842] font-medium transition-colors"><?=e($p['title'])?></a>
              <div class="text-xs text-zinc-600 font-mono mt-0.5">/post.php?slug=<?=$p['slug']?></div>
            </td>
            <td class="px-4 py-3 text-zinc-500 hidden md:table-cell"><?=e($p['category']??'')?></td>
            <td class="px-4 py-3 text-zinc-500 hidden md:table-cell font-mono"><?=$p['views']?></td>
            <td class="px-4 py-3">
              <a href="?toggle=<?=$p['id']?>" class="font-mono text-xs px-2 py-1 border <?=$p['published']?'border-green-800 text-green-400':'border-zinc-700 text-zinc-500'?> hover:opacity-70">
                <?=$p['published']?'Live':'Draft'?>
              </a>
            </td>
            <td class="px-4 py-3 text-right whitespace-nowrap">
              <a href="?edit=<?=$p['id']?>" class="text-xs text-zinc-400 hover:text-white mr-3">Edit</a>
              <a href="?delete=<?=$p['id']?>" onclick="return confirm('Delete this post?')" class="text-xs text-red-500 hover:text-red-400">Delete</a>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <?php endif; ?>
    </div>

    <?php else: ?>
    <!-- POST FORM -->
    <div class="mb-6 flex justify-between items-center">
      <h1 class="text-xl font-bold"><?=$editing?'Edit Post':'New Post'?></h1>
      <a href="/admin.php" class="text-xs text-zinc-500 border border-zinc-800 px-3 py-1.5 hover:text-white">← Back</a>
    </div>
    <form method="POST" class="space-y-5">
      <div class="grid md:grid-cols-2 gap-4">
        <div>
          <label class="block font-mono text-xs text-zinc-400 uppercase tracking-widest mb-1.5">Title *</label>
          <input name="title" type="text" value="<?=e($editing['title']??'')?>" required class="w-full bg-zinc-900 border border-zinc-700 px-3 py-2.5"/>
        </div>
        <div>
          <label class="block font-mono text-xs text-zinc-400 uppercase tracking-widest mb-1.5">Slug (auto if blank)</label>
          <input name="slug" type="text" value="<?=e($editing['slug']??'')?>" class="w-full bg-zinc-900 border border-zinc-700 px-3 py-2.5"/>
        </div>
      </div>
      <div>
        <label class="block font-mono text-xs text-zinc-400 uppercase tracking-widest mb-1.5">Excerpt</label>
        <input name="excerpt" type="text" value="<?=e($editing['excerpt']??'')?>" class="w-full bg-zinc-900 border border-zinc-700 px-3 py-2.5"/>
      </div>
      <div class="grid md:grid-cols-3 gap-4">
        <div>
          <label class="block font-mono text-xs text-zinc-400 uppercase tracking-widest mb-1.5">Category</label>
          <select name="category" class="w-full bg-zinc-900 border border-zinc-700 px-3 py-2.5">
            <?php foreach(['Guide','Tutorial','Analysis','Case Study','Article'] as $c): ?>
            <option value="<?=$c?>" <?=($editing['category']??'Guide')===$c?'selected':''?>><?=$c?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div>
          <label class="block font-mono text-xs text-zinc-400 uppercase tracking-widest mb-1.5">Read Time (min)</label>
          <input name="read_time" type="number" value="<?=$editing['read_time']??5?>" min="1" max="60" class="w-full bg-zinc-900 border border-zinc-700 px-3 py-2.5"/>
        </div>
        <div>
          <label class="block font-mono text-xs text-zinc-400 uppercase tracking-widest mb-1.5">Tags (comma separated)</label>
          <input name="tags" type="text" value="<?=e($editing['tags']??'')?>" placeholder="n8n, automation" class="w-full bg-zinc-900 border border-zinc-700 px-3 py-2.5"/>
        </div>
      </div>
      <div>
        <label class="block font-mono text-xs text-zinc-400 uppercase tracking-widest mb-1.5">Content (HTML)</label>
        <textarea name="content" rows="18" class="w-full bg-zinc-900 border border-zinc-700 px-3 py-2.5 font-mono text-xs resize-y"><?=e($editing['content']??'')?></textarea>
      </div>
      <div class="flex justify-between items-center pt-2">
        <label class="flex items-center gap-2 cursor-pointer">
          <input type="checkbox" name="published" <?=($editing['published']??0)?'checked':''?> class="accent-[#f5c842] w-4 h-4"/>
          <span class="text-sm">Published (visible on site)</span>
        </label>
        <button type="submit" class="bg-[#f5c842] text-zinc-950 font-bold text-sm px-8 py-2.5 hover:opacity-90">Save Post</button>
      </div>
    </form>
    <?php endif; ?>
  </main>
</div>

<?php endif; ?>
</body></html>
