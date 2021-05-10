<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=EDGE">
<title>crawling</title>
<link rel="stylesheet" type="text/css" href="./css/crawler.css">
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="./js/common.js"></script>
<!--[if lt IE 9]>
<script type="text/javascript" src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<script type="text/javascript">
$(document).ready(function() {
	$("#crawlInput").focus();	
});
</script>

</head>

<body>
	
<div class="crawl_main">
	<div class="crawl_top">
		<a class="crawl-img img-nwars" href="http://nwars.navercorp.com" target="_blank"><span class="blind">NWARS</span></a>
		<a class="crawl-img img-nfeas" href="http://test.nfeas.navercorp.com" target="_blank"><span class="blind">NFEAS</span></a>
	</div>
	<h1 class="crawl-img img-mlogo"><span class="blind">crawling</span></h1>
	<div class="crawl_search">
		<form name="crawlForm" action="/crawl_check.php" method="POST">
			<a href="/crawl_list.php" class="crawl-img img-list mar-r10"><span class="blind">전체리스트보기</span></a>
			<input id="crawlURL" type="text" class="crawl_input" name="url" placeholder="Type URL to Crawl">
			<input id="crawlSubmit" type="button" class="crawl_submit" value="CRAWL">
		</form>
		<p>Crawl Web Site to See the Tree Map View</p>
	</div>
	
</div>
<div class="footer main_footer"><p>COPYRIGHT ⓒ NTS CORP. ALL RIGHTS RESERVED</p></div>
