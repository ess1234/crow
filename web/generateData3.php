<?php
	$IMG_ROOT = "http://".$_SERVER["HTTP_HOST"]."/";
	include_once $_SERVER["DOCUMENT_ROOT"]."/lib/config_db.php"; //db 정보
	include_once $_SERVER["DOCUMENT_ROOT"]."/lib/class_mysql.php"; //db class
	include_once $_SERVER["DOCUMENT_ROOT"]."/lib/fnc_common.php"; //기본 기능
	
	$domainSeq = $_GET['domainSeq'];	

	$currentPage = $_GET['currentPage'];
	$viewCnt = $_GET['viewCnt'];
	if(empty($currentPage)||$currentPage=="") $currentPage=1; //현재페이지
	if(empty($viewCnt)||$viewCnt=="") $viewCnt = 10; //한 페이지당 게시물 수
	
	$SqlExecute = new dbMySql($connect);
	$table = " crawling.url_atag p, crawling.url_atag c  ";
	$where = " where c.domain_seq = ".$domainSeq;
	
	$limit = " LIMIT ".(($currentPage-1)*$viewCnt).", ".($currentPage*$viewCnt);
	
	// $strQuery = "select CRAWLING_SEQ, DOMAIN_SEQ, PARENT_SEQ, URL, (SELECT COUNT(*) cnt FROM url_dir a WHERE a.PARENT_SEQ = b.CRAWLING_SEQ AND a.DOMAIN_SEQ = b.DOMAIN_SEQ GROUP BY PARENT_SEQ) LINK_CNT, REG_DTS from ".$table." b ".$where." order by CRAWLING_SEQ ";
	
	$strQuery = " select distinct p.*  , "
						." (SELECT COUNT(*) cnt FROM url_atag a WHERE a.PARENT_SEQ = p.URL_SEQ AND a.DOMAIN_SEQ = p.DOMAIN_SEQ and status != 200 GROUP BY PARENT_SEQ) ERROR_CNT "
						."			from "
						.$table
						.$where
						." and (p.URL_SEQ = c.PARENT_SEQ or p.URL_SEQ = c.URL_SEQ ) "
						." and c.status != 200 "
						." order by c.status, c.PARENT_SEQ, p.URL_SEQ ";

	
	// $total = $SqlExecute->dbCnt($table," DOMAIN_SEQ ",$where); //총 게시물 수
	
	// $cnt = $total-(($currentPage-1)*$viewCnt); // 넘버링
	$result = $SqlExecute->dbQuery($strQuery);
	
	// echo $strQuery;
	
	echo "id,parent,url,link,count,status,desc"."\n";
	
	while($row=mysql_fetch_array($result)){
		
		$linkCnt = 0;
		if($row["LINK_CNT"] > 0)  $linkCnt =$row["ERROR_CNT"]."/". $row["LINK_CNT"];
	
		echo $row["URL_SEQ"].",".$row["PARENT_SEQ"].",".str_replace("http://", "", $row["URL"]).",".$row["URL"].",".$linkCnt.",".$row["STATUS"].","."desc"."\r\n";
	}
	
	$SqlExecute->dbClose($connect);
?>