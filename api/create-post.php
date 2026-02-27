<?php
/**
 * Grova Blog API
 * POST /api/create-post.php
 * Header: Authorization: Bearer YOUR_API_SECRET
 * Body JSON: { title, content, excerpt?, category?, tags?, read_time?, published?, slug? }
 */
require_once __DIR__.'/../config.php';
header('Content-Type: application/json');
if($_SERVER['REQUEST_METHOD']!=='POST'){http_response_code(405);echo json_encode(['ok'=>false,'error'=>'Method not allowed']);exit;}
$auth=str_replace('Bearer ','',trim($_SERVER['HTTP_AUTHORIZATION']??$_SERVER['REDIRECT_HTTP_AUTHORIZATION']??''));
if($auth!==API_SECRET){http_response_code(401);echo json_encode(['ok'=>false,'error'=>'Unauthorized']);exit;}
$b=json_decode(file_get_contents('php://input'),true);
if(!$b||!trim($b['title']??'')||!trim($b['content']??'')){http_response_code(422);echo json_encode(['ok'=>false,'error'=>'title and content required']);exit;}
try{
  $slug=savePost(['title'=>trim($b['title']),'slug'=>trim($b['slug']??''),'excerpt'=>trim($b['excerpt']??''),'category'=>trim($b['category']??'Article'),'content'=>$b['content'],'tags'=>is_array($b['tags']??null)?implode(',',$b['tags']):'','read_time'=>(int)($b['read_time']??5),'published'=>(int)($b['published']??0)]);
  echo json_encode(['ok'=>true,'slug'=>$slug,'url'=>'/post.php?slug='.$slug]);
}catch(Exception $e){http_response_code(500);echo json_encode(['ok'=>false,'error'=>$e->getMessage()]);}
