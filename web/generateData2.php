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
	$table = " crawling.url_atag ";
	$where = " where domain_seq = ".$domainSeq;
	
	$limit = " LIMIT ".(($currentPage-1)*$viewCnt).", ".($currentPage*$viewCnt);
	
	// $strQuery = "select CRAWLING_SEQ, DOMAIN_SEQ, PARENT_SEQ, URL, (SELECT COUNT(*) cnt FROM url_dir a WHERE a.PARENT_SEQ = b.CRAWLING_SEQ AND a.DOMAIN_SEQ = b.DOMAIN_SEQ GROUP BY PARENT_SEQ) LINK_CNT, REG_DTS from ".$table." b ".$where." order by CRAWLING_SEQ ";
	
	$strQuery = "select * from ( "
				."	select URL_SEQ, DOMAIN_SEQ, PARENT_SEQ, URL, " 
				."		(SELECT COUNT(*) cnt FROM url_atag a WHERE a.PARENT_SEQ = b.URL_SEQ AND a.DOMAIN_SEQ = b.DOMAIN_SEQ GROUP BY PARENT_SEQ) LINK_CNT, " 
				."			ifnull(STATUS, 'none') STATUS,  "
				."			REG_DTS  "
				."			from "
				.$table." b "
				.$where
				."		) a  "
				."	order by a.LINK_CNT desc ";

	
	// $total = $SqlExecute->dbCnt($table," DOMAIN_SEQ ",$where); //총 게시물 수
	
	// $cnt = $total-(($currentPage-1)*$viewCnt); // 넘버링
	$result = $SqlExecute->dbQuery($strQuery);
	
	// echo $strQuery;
	
	echo "id,parent,url,link,count,status,desc"."\n";
	
	while($row=mysql_fetch_array($result)){
		echo $row["URL_SEQ"].",".$row["PARENT_SEQ"].",".str_replace("http://", "", $row["URL"]).",".$row["URL"].",".$row["LINK_CNT"].",".$row["STATUS"].","."desc"."\r\n";
	}
	
	$SqlExecute->dbClose($connect);
?>