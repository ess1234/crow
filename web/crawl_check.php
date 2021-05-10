<?php
	include_once $_SERVER["DOCUMENT_ROOT"]."/lib/config_db.php"; //db 정보
	include_once $_SERVER["DOCUMENT_ROOT"]."/lib/class_mysql.php"; //db class
	include_once $_SERVER["DOCUMENT_ROOT"]."/lib/fnc_common.php"; //기본 기능
	$SqlExecute = new dbMySql($connect);
	$txt = $_POST["url"];
	$url = rtrim($txt,"/");
	//echo "1111 ->  ".eregi("http://",$url);
	if(!eregi("http://",$url) && !eregi("https://",$url)){
		$url = "http://".$url;
	}
	//echo "url ->  ".$url;
	$table = " crawling.domain ";
	$where = " WHERE DOMAIN = '".$url."' ";
	$strQuery = "select DOMAIN_SEQ from ".$table.$where;
	$result = $SqlExecute->dbOneResult($strQuery);
	$dbvalue['DOMAIN'] = $url;
	$dbvalue['REQUEST_USER'] = "admin";
	if($result == ""){
		$test = $SqlExecute->dbSetSql($table,$dbvalue,"insert");
		$newQuery = "select DOMAIN_SEQ from ".$table.$where;
		$result = $SqlExecute->dbOneResult($newQuery);
	}
	// echo "asdasdas=>".$test;
	$SqlExecute->dbClose($connect);
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
	$("form[name='crawlNum']").submit();
});
</script>
</head>
<body>
<form action="crawl_list.php" name="crawlNum" method="POST">
	<input id="crawlSEQ" name="url" type="text" value="<?=$txt ?>">
	<input id="crawlSEQ" name="seqNum" type="text" value="<?=$result ?>">
</form>
</body>
</html>