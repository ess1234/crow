<?php
	$IMG_ROOT = "http://".$_SERVER["HTTP_HOST"]."/";
	include_once $_SERVER["DOCUMENT_ROOT"]."/lib/config_db.php"; //db 정보
	include_once $_SERVER["DOCUMENT_ROOT"]."/lib/class_mysql.php"; //db class
	$url = $_POST["url"];
?>
<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=EDGE">
<title>crawling</title>
<link rel="stylesheet" type="text/css" href="./css/ystree.css"/>
<link rel="stylesheet" type="text/css" href="./css/crawler.css">
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="./js/common.js"></script>
<script src="./js/ystree.js"></script>
<!--[if lt IE 9]>
<script type="text/javascript" src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
</head>

<body>
<div class="wrapper">

	<!-- Header -->
	<header class="top_header">
		<h1><a href="/" class="crawl-img img-logo"><span class="blind">crawling 메인으로 바로가기</span></a></h1>
		<div class="top_search">
			<form name="crawlForm" action="/crawl_check.php" method="POST">
				<input id="crawlURL" type="text" class="crawl_input" name="url" placeholder="Type URL to Crawl" value="<?=$url ?>">
				<input id="crawlSubmit" type="submit" class="crawl_submit" value="CRAWL">
			</form>
		</div>
		<div class="top_list">
			<a href="/crawl_list.php" class="crawl-img img-list"><span class="blind">전체리스트보기</span></a>
		</div>
		
	</header>
	