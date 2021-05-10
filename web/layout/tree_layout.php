<?php
	$domainSeq = $_REQUEST['id'];
	$host = $_SERVER['REQUEST_URI'];

	include_once $_SERVER["DOCUMENT_ROOT"]."/lib/config_db.php"; //db 정보
	include_once $_SERVER["DOCUMENT_ROOT"]."/lib/class_mysql.php"; //db class
	include_once $_SERVER["DOCUMENT_ROOT"]."/lib/fnc_common.php"; //기본 기능
	$SqlExecute = new dbMySql($connect);
	
	$where = " where domain_seq = ".$domainSeq;
	$table = " crawling.domain ";


	$strQuery = "select DOMAIN_SEQ, DOMAIN from "
					.$table
					.$where ;	
	
	$result = $SqlExecute->dbQuery($strQuery);
	$row=mysql_fetch_array($result);
?>

<!-- Body Header -->
<section class="container">
	<h1><?=$row["DOMAIN"] ?></h1>
	<nav class="tree_tab">
		<ul class="tree_menu">
			<li><? if(strpos($host,'crawl_tree.php') !== false){ ?><span>Link Tree</span><? }else{ ?><a href="/crawl_tree.php?id=<?=$domainSeq ?>">Link Tree</a><? } ?></li>
			<li><? if(strpos($host,'crawl_redirect.php') !== false){ ?><span>Redirect Links</span><? }else{ ?><a href="/crawl_redirect.php?id=<?=$domainSeq ?>">Redirect Links</a><? } ?></li>
			<li><? if(strpos($host,'crawl_error.php') !== false){ ?><span>Error Links</span><? }else{ ?><a href="/crawl_error.php?id=<?=$domainSeq ?>">Error Links</a><? } ?></li>
			<li><? if(strpos($host,'crawl_menu.php') !== false){ ?><span>Menu Tree (HTML5 Only)</span><? }else{ ?><a href="/crawl_menu.php?id=<?=$domainSeq ?>">Menu Tree (HTML5 Only)</a><? } ?></li>
			<li class="img-list"><a href="/crawl_list.php"><span class="blind"><span class="blind">전체리스트보기</span></a></li>
		</ul>
	</nav>
	