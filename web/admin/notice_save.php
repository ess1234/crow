<?php
	include_once $_SERVER["DOCUMENT_ROOT"]."/lib/config_db.php"; //db 정보
	include_once $_SERVER["DOCUMENT_ROOT"]."/lib/class_mysql.php"; //db class
	
	if(empty($_SESSION['ID']) || $_SESSION['ID']=="") { // 세션정보
			$reg_id=$_SESSION['ID'];
	} 
	
	$seq=$_POST['seq'];
	echo $_POST["chgNm"];
	echo "한글";
	$idx = "update";
	if(empty($seq) || $seq =="") $idx = "insert";
	
	$SqlExecute = new dbMySql($connect);
	
	// trainee 입력 혹은 수정
	$TBL_SETTING = 'notice';
	
	$dbvalue['TITLE'] = $_POST["title"];
	$dbvalue['CONTENTS'] = $_POST["contents"];
	
	// file upload
	$fileName = $_FILES['actFile']['name'];
	$filePath = "/upload/";
	
	$tmp = explode('.',$fileName);
	$sasefileName = $tmp[0].'_'.date('YmdHis').'.'.$tmp[1];
	
	if (file_exists($filePath.$sasefileName)) {
		echo $fileName." already exists. ";
	} else {
		move_uploaded_file($_FILES["actFile"]["tmp_name"],	(iconv('utf-8','euc-kr', $_SERVER["DOCUMENT_ROOT"].$filePath.$sasefileName)));
	}
	
	$dbvalue['FILE_NAME'] = $fileName;
	$dbvalue['FILE_PATH'] = $filePath.$sasefileName;
	
	
	if($idx=="insert"){
		// insert
		$dbvalue['REG_ID'] = $_POST["ID"];
		$result = $SqlExecute->dbSetSql($TBL_SETTING,$dbvalue,'insert');
	}else{
		// update
		$dbvalue['UPD_ID'] = $_POST["ID"];
		$where = " , UPD_DTS = NOW() ";
		$where .= " WHERE SEQ = '".$seq."' ";
		$result = $SqlExecute->dbSetSql($TBL_SETTING,$dbvalue,'update',$where);		
	}
	
	$SqlExecute->dbClose($connect);
	
	echo "<script language=\"javascript\">\n";
	  // echo "location.href=\"/notice_list.php\"; ";
	echo "</script>";
?>