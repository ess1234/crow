<?php
	$IMG_ROOT = "http://".$_SERVER["HTTP_HOST"]."/";
	include_once $_SERVER["DOCUMENT_ROOT"]."/lib/config_db.php"; //db 정보
	include_once $_SERVER["DOCUMENT_ROOT"]."/lib/class_mysql.php"; //db class
	
	$data =array();
	array_push($data, array($_POST["reason_1"], $_POST["vote_1"], $_POST["memberName"]));
	array_push($data, array($_POST["reason_2"], $_POST["vote_2"], $_POST["memberName"]));
	array_push($data, array($_POST["reason_3"], $_POST["vote_3"], $_POST["memberName"]));
	array_push($data, array($_POST["reason_4"], $_POST["vote_4"], $_POST["memberName"]));
	array_push($data, array($_POST["reason_5"], $_POST["vote_5"], $_POST["memberName"]));
	array_push($data, array($_POST["reason_6"], $_POST["vote_6"], $_POST["memberName"]));
	array_push($data, array($_POST["reason_7"], $_POST["vote_7"], $_POST["memberName"]));
	array_push($data, array($_POST["reason_8"], $_POST["vote_8"], $_POST["memberName"]));
	array_push($data, array($_POST["reason_9"], $_POST["vote_9"], $_POST["memberName"]));
	array_push($data, array($_POST["reason_10"], $_POST["vote_10"], $_POST["memberName"]));
	array_push($data, array($_POST["reason_11"], $_POST["vote_11"], $_POST["memberName"]));
	array_push($data, array($_POST["reason_12"], $_POST["vote_12"], $_POST["memberName"]));
	array_push($data, array($_POST["reason_13"], $_POST["vote_13"], $_POST["memberName"]));
	array_push($data, array($_POST["reason_14"], $_POST["vote_14"], $_POST["memberName"]));
	array_push($data, array($_POST["reason_15"], $_POST["vote_15"], $_POST["memberName"]));
	array_push($data, array($_POST["reason_16"], $_POST["vote_16"], $_POST["memberName"]));
	array_push($data, array($_POST["reason_17"], $_POST["vote_17"], $_POST["memberName"]));
	array_push($data, array($_POST["reason_18"], $_POST["vote_18"], $_POST["memberName"]));
	array_push($data, array($_POST["reason_19"], $_POST["vote_19"], $_POST["memberName"]));
	array_push($data, array($_POST["reason_20"], $_POST["vote_20"], $_POST["memberName"]));
	array_push($data, array($_POST["reason_21"], $_POST["vote_21"], $_POST["memberName"]));
	array_push($data, array($_POST["reason_22"], $_POST["vote_22"], $_POST["memberName"]));
	array_push($data, array($_POST["reason_23"], $_POST["vote_23"], $_POST["memberName"]));
	
	$SqlExecute = new dbMySql($connect);
	
	// trainee 입력 혹은 수정
	$TBL_SETTING = 'thanksTo_2014';
	
	for($i=0; $i<24; $i++){
		if($data[$i][0] != "") {
			// insert
			$dbvalue['REASON'] = $data[$i][0];
			$dbvalue['THANKS_TO'] = $data[$i][1];
			$dbvalue['THANKS_FROM'] = $data[$i][2];
			$result = $SqlExecute->dbSetSql($TBL_SETTING,$dbvalue,'insert');
		} 
	}
	
	$SqlExecute->dbClose($connect);
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>접근성 개발실 칭찬합시다: 투표완료</title>

<style type="text/css">
body{margin:0;padding:0;background:#fefcef;border-top:5px solid #c61d00;font-family: '나눔고딕',NanumGothic,Malgun Gothic,'돋움',Dotum,helvetica,arial,sans-serif;}
.wrap{width:80%;;margin:0 auto;}
p{font-size:14px;line-height:1.5;}
header{margin:80%;height:275px;margin:50px auto;background-position:right top;background-repeat:no-repeat;background-image: url('data:image/png;base64,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');}
header .small{display:block;width:90px;margin-bottom:10px;padding:5px 0;font-size:12px;font-weight: normal;background:#c61d00;color:#fefcef;text-align: center;}
header h1{font-size:32px;}
header p{font-size:14px;}

</style>

</head>

<body>
<div class="wrap">
	<header>
		<h1><span class="small">2014연말행사</span>접근성개발실 칭찬합시다!</h1>
		<p class="top">투표가 완료되었습니다. 즐거운 하루 되세요 ♪<br/><br/>(투표 관련 문의: 엄지연)</p>
	</header>
</div>
</body>
</html>


