<?php include_once $_SERVER["DOCUMENT_ROOT"]."/asbesto/layout/header_popup.html"; ?>
<?php
	
	if(empty($_SESSION['ID']) || $_SESSION['ID']=="") { // ��������
		if(empty($_COOKIE['ID']) || $_COOKIE['ID']=="") { // ��Ű����
			$reg_id = "ADMIN";
		} else {
			$reg_id=$_SESSION['ID'];
		} 
	}else {
			$reg_id=$_COOKIE['ID'];
	} 
	$class_seq = $_POST['class_seq'];
	
	if(empty($class_seq) || $class_seq ==""){	echo "���� �ڵ忡 ������ �ֽ��ϴ�. ���ε带 �����մϴ�.";	exit;	}
	
	$allow_url_override = 1; // Set to 0 to not allow changed VIA POST or GET
	if(!$allow_url_override || !isset($file_to_include))
	{
		$file_to_include = $_FILES[xls][tmp_name];
	}
	if(!$allow_url_override || !isset($max_rows))
	{
		$max_rows = 0; //USE 0 for no max
	}
	if(!$allow_url_override || !isset($max_cols))
	{
		$max_cols = 0; //USE 0 for no max
	}
	if(!$allow_url_override || !isset($debug))
	{
		$debug = 0;  //1 for on 0 for off
	}
	if(!$allow_url_override || !isset($force_nobr))
	{
		$force_nobr = ($_POST[force_nobr]) ? 1 : 0;  //Force the info in cells not to wrap unless stated explicitly (newline)
	}
	
	if((!$allow_url_override || !isset($force_size)) && $force_nobr)
	{
		$force_size = 1;  //
	}
	//echo "$file_to_include";
	require_once './excel_reader.php';
	
	$data = new Spreadsheet_Excel_Reader();
	
	$data->setUTFEncoder('mb');
	$data->setOutputEncoding('euc-kr');
	$data->read($file_to_include);
	
	error_reporting(E_ALL ^ E_NOTICE);
	
	// ����Ÿ üũ
	for ($i = 1; $i <= $data->sheets[0]['numRows']; $i++) { 
		$trainee_seq = $data->sheets[0]['cells'][$i][1];
		$name = $data->sheets[0]['cells'][$i][2];
		$mobile = $data->sheets[0]['cells'][$i][4];
		$pay = $data->sheets[0]['cells'][$i][5];
		$payment = $data->sheets[0]['cells'][$i][6];
		$paydate = $data->sheets[0]['cells'][$i][7];
		if(empty($trainee_seq)||$trainee_seq==""){echo "������ �ڵ忡 ������ �ֽ��ϴ�. ���ε带 �����մϴ�.";	exit;	}
		if(empty($name)||$name==""){echo "�̸��� ������ �ֽ��ϴ�. ���ε带 �����մϴ�.";	exit;	}
		if(empty($mobile)||$mobile==""){echo "�ڵ����� ������ �ֽ��ϴ�. ���ε带 �����մϴ�.";	exit;	}
		if(empty($pay)||$pay==""){echo "���Կ� ������ �ֽ��ϴ�. ���ε带 �����մϴ�.";	exit;	}
		if(empty($payment)||$payment==""){echo "���Աݿ� ������ �ֽ��ϴ�. ���ε带 �����մϴ�.";	exit;	}
		if(empty($paydate)||$paydate==""){echo "�����Ͽ� ������ �ֽ��ϴ�. ���ε带 �����մϴ�.";	exit;	}			
	}	
	
	// db insert 
	echo "���������� ���ε� ���Դϴ�.<br>";
	
	$SqlExecute = new dbMySql($connect);
	$TBL_SETTING = 'LECTURE';
	
	// �⼮ update ��� ����
	// �Ա��� ������ �ϰ����� �ȵ�.
	
	// update �Աݳ��� �ʱ�ȭ
	$dbvalue['UP_ID'] = $reg_id;	
	$dbvalue['PAY'] = "0";
	$dbvalue['PAYMENT'] = "0";
	$dbvalue['PAYDATE'] = ""; 
	$where = " , UP_DTS = NOW() ";
	$where .= " WHERE CLASS_SEQ = '".$class_seq."' ";
	$result = $SqlExecute->dbSetSql($TBL_SETTING,$dbvalue,'update',$where);	
	
	for ($i = 1; $i <= $data->sheets[0]['numRows']; $i++) { 
		
		$dbvalue['PAY'] = $data->sheets[0]['cells'][$i][5];
		$dbvalue['PAYMENT'] = $data->sheets[0]['cells'][$i][6];
		$dbvalue['PAYDATE'] = $data->sheets[0]['cells'][$i][7];
		
		// update
		$dbvalue['UP_ID'] = $reg_id;	
		$where = " , UP_DTS = NOW() ";
		$where .= " WHERE CLASS_SEQ = '".$class_seq."' ";
		$where .= " AND TRAINEE_SEQ = '".($data->sheets[0]['cells'][$i][1])."' ";
		$result = $SqlExecute->dbSetSql($TBL_SETTING,$dbvalue,'update',$where);		
		
		// sms �߼�
		$SENDVALUE['tran_phone'] = $data->sheets[0]['cells'][$i][4];
		$SENDVALUE['tran_callback'] = "027935015";		
		$SENDVALUE['tran_msg'] = ($data->sheets[0]['cells'][$i][2])."���� �б�������������� ������ ������ �Ϸ�Ǿ����ϴ�. �б����������߾�ȸ";
		$SENDVALUE['tran_date'] = "";
		$sendresult = smsSend($SENDVALUE);
		echo "sms====>>>".$sendresult;
	}
		
	unset($TBL_SETTING);
	unset($dbvalue);
	unset($where);

	if($SqlExecute->dbError()){
		echo "<script language=\"javascript\">\n";
		echo "alert('DB �۾� �� ������ �ֽ��ϴ�.'); ";
		echo "</script>";
	}
	
	$SqlExecute->dbClose($connect);
	
	// echo "<script>alert('�������� ���ε带 �Ϸ��Ͽ����ϴ�.');opener.document.location.reload();self.close();</script>";exit;

?>
<br><br><br>
<p><strong>���������� ���ε� �Ǽ̽��ϴ�.</strong></p>
<a class="btn_big" href="javascript:self.close();"><strong>Ȯ��</strong></a>
<br><br><br>
<?php include_once $_SERVER["DOCUMENT_ROOT"]."/asbesto/layout/footer.html"; ?> 