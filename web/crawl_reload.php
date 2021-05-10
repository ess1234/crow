<?php
	include_once $_SERVER["DOCUMENT_ROOT"]."/lib/config_db.php"; //db 정보
	include_once $_SERVER["DOCUMENT_ROOT"]."/lib/class_mysql.php"; //db class
	include_once $_SERVER["DOCUMENT_ROOT"]."/lib/fnc_common.php"; //기본 기능
	$SqlExecute = new dbMySql($connect);
	
	$updateNum = $_POST['updateNum'];
	$currentPage = $_POST['currentPage'];
	$seqNum = $_POST["seqNum"];
	$listStat = $_POST["listStat"];
	$listArrange = $_POST["listArrange"];
	
	$table = " crawling.domain ";
	$where = " WHERE DOMAIN_SEQ = ".$updateNum." ";
	$dbvalue['CRAWLING_STATE'] = "WAIT";
	$dbvalue['START_DTS'] = null;
	$dbvalue['END_DTS'] = null;
	$test = $SqlExecute->dbSetSql($table,$dbvalue,"update",$where);
	
	$table2 = " crawling.url_dir ";
	$where2 = " WHERE DOMAIN_SEQ = ".$updateNum." ";
	$test2 = $SqlExecute->dbDelSql($table2,$where2);
	
	$table3 = " crawling.url_atag ";
	$where3 = " WHERE DOMAIN_SEQ = ".$updateNum." ";
	$test3 = $SqlExecute->dbDelSql($table3,$where3);
	
	//echo "asdasdas=>".$test;
	$SqlExecute->dbClose($connect);
	header("/crawl_list.php")
?>

<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=EDGE">
<title>crawling</title>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	$("form[name='crawlForm']").submit();
});
</script>
</head>
<body>
<form action="crawl_list.php" name="crawlForm" method="POST">
	<input name="currentPage" type="text" value="<?=$currentPage ?>">
	<input name="seqNum" type="text" value="<?=$seqNum ?>">
</form>
</body>
</html>