<?php
	$IMG_ROOT = "http://".$_SERVER["HTTP_HOST"]."/";
	include_once $_SERVER["DOCUMENT_ROOT"]."/lib/config_db.php"; //db 정보
	include_once $_SERVER["DOCUMENT_ROOT"]."/lib/class_mysql.php"; //db class
	
	$SqlExecute = new dbMySql($connect);
	$table = " thanksTo_2014_member fm ";
	$where = " WHERE 1=1 ";
	
	$strQuery = "select fm.MEMBER_KEY, fm.MEMBER_NAME, ifnull(( select count(*) cnt from thanksTo_2014 where THANKS_FROM = fm.MEMBER_KEY group by THANKS_FROM ), 0) CNT from ".$table.$where." ORDER BY fm.MEMBER_NAME";
	$result = $SqlExecute->dbQuery($strQuery);
	
	$result2 = $SqlExecute->dbQuery($strQuery);
	
	$SqlExecute->dbClose($connect);
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>접근성 개발실 칭찬합시다</title>

<style type="text/css">
body{margin:0;padding:0;background:#fefcef;border-top:5px solid #c61d00;font-family: '나눔고딕',NanumGothic,Malgun Gothic,'돋움',Dotum,helvetica,arial,sans-serif;}
.wrap{width:80%;;margin:0 auto;}
select,input,button{font-family: '나눔고딕',NanumGothic,Malgun Gothic,'돋움',Dotum,helvetica,arial,sans-serif;font-size:14px}
p{font-size:14px;line-height:1.5;}
header{margin:80%;height:275px;margin:50px auto;background-position:right top;background-repeat:no-repeat;background-image: url('data:image/png;base64,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');}
header .small{display:block;width:90px;margin-bottom:10px;padding:5px 0;font-size:12px;font-weight: normal;background:#c61d00;color:#fefcef;text-align: center;}
header h1{font-size:32px;}
header p{font-size:14px;}
form{margin:0;padding:0;}
select {margin-left:5px;width:100px;}
h2{margin:75px 0 25px;padding:15px 0 0 5px;border-top:5px solid #c61d00;font-size:18px;}
form p{padding:0 5px;color:#666;}
table{width:100%;border-collapse: none;border-spacing: 5px}
caption{overflow:hidden;width:0;height:0;font-size:0;line-height:0;}
th{background:#c61d00;border-bottom:3px solid #aa1900;color:#fefcef;padding:10px 0;font-size:16px;font-weight:normal;}
td{background:#fff;padding:10px 0;border-bottom:3px solid #ebebeb;text-align: center}
input[type="text"]{width:95%;padding:0 5px;height:26px;line-height:26px;border:1px solid #ebebeb;}
button[type="submit"]{display:block;margin:50px auto;padding:10px 20px;background:#c61d00;border:none;border-bottom:3px solid #aa1900;color:#fff;font-size:20px;}
</style>

<script>
function selectedMyName(obj){
	// alert(obj.value+" :: "+obj.options[obj.value].text+" :: "+document.getElementById("tr_"+obj.value));
	document.getElementById("tr_"+obj.value).display = "none";
}

function checkMySelf(obj){

	// 본인 이름 선택 여부
	var selectedName = document.getElementById("selectedName").value;
	if(selectedName == null || selectedName == "") {
		alert("본인 이름을 선택하여 주세요.");
		obj.checked = false;
		document.getElementById("selectedName").focus();
		return false;
	}
	
	// 본인 선택인경우
	if(selectedName == obj.value) {
		alert("자기 자신은 칭찬할수 없습니다. 당신은 욕심쟁이 우후훗!!");
		obj.checked = false;
		return false;
	}
	
	// 3개 선택한 경우
	var cnt = 0;
	for(var i=1; i<24;i++){
		if(document.getElementById("vote_"+i).checked) cnt++;
	}
	if(cnt > 3){
		alert("최대 3명까지 투표 가능합니다.");
		obj.checked = false;
		return false;
	}
	
	// 선택하였을때 사유 
	if(document.getElementById("reason_"+obj.value).disabled) {
		document.getElementById("reason_"+obj.value).disabled = "";
	}else {
		document.getElementById("reason_"+obj.value).value = "";
		document.getElementById("reason_"+obj.value).disabled = "disabled";
	}
}

function checkValid(obj){

	// 본인 이름 선택 여부
	var selectedName = document.getElementById("selectedName").value;
	if(selectedName == null || selectedName == "") {
		alert("본인 이름을 선택하여 주세요.");
		obj.checked = false;
		document.getElementById("selectedName").focus();
		return false;
	}
	
	// 1개 이상
	var cnt = 0;
	for(var i=1; i<24;i++){
		if(document.getElementById("vote_"+i).checked) cnt++;
	}
	if(cnt < 1){
		alert("최소 한명은 칭찬합시다.");
		obj.checked = false;
		return false;
	}

	for(var i=1; i<24;i++){
		if(document.getElementById("vote_"+i).checked){
			if(document.getElementById("reason_"+i).value == "") {
				alert(document.getElementById("name_"+i).innerHTML+"님 사유를 입력하여 주세요.");
				document.getElementById("name_"+i).focus();
				return false;
			} 
		}
	}
	if(confirm("제출하시겠습까??")) {
		obj.elements["memberName"].value = selectedName;
		obj.submit();
	}
	return false;
}
</script>

</head>

<body>
<div class="wrap">
	<header>
		<h1><span class="small">2014연말행사</span>접근성개발실 칭찬합시다!</h1>
		<p class="top">2014년 한 해 동안 접근성개발실에서 꼭 칭찬하고 싶었던, 고마웠던 분들이 계셨을거에요!<br/>
			투표로 마음을 표현해보아요~ <br/>(투표 관련 문의: 엄지연)</p>
	</header>


	<h2><label for="name">본인 이름</label></h2>
	<select name="selectedName" id="selectedName" onchange="javascript:selectedMyName(this);" ><!-- 전체 팀원(23명) -->
	<option value="">-- 이름-- </option>
	<?php
					while($row=mysql_fetch_array($result)){
		?>
			<option value="<?=$row['MEMBER_KEY'] ?>" <? if($row['CNT'] > 0) echo "disabled"; ?> ><?=$row['MEMBER_NAME'] ?></option>
		<?php
			}
		 ?>
	</select>

<form name="frm" method="post" action="/thanksTo_2014_complete.php" onsubmit="javascript:checkValid(this); return false;">
	<input type="hidden" name="memberName" value="" />
	<h2>칭찬하고 싶은 사람</h2>
	<p>최대 3명까지 투표 가능합니다. 이름 선택 후, 사유를 꼭 적어주세요~</p>
	<table>
		<caption>칭찬 대상 목록</caption>
		<col style="width:100px;"/>
		<col style="width:130px;"/>
		<col/>
		<thead>
			<tr>
				<th scope="col" >선택</th>
				<th scope="col" >이름</th>
				<th scope="col" >사유</th>
			</tr>
		</thead>
		<tbody>
		
		<?php
					while($row=mysql_fetch_array($result2)){
		?>
		
			<tr id="tr_<?=$row['MEMBER_KEY'] ?>"> <!-- 본인을 제외한 모든 팀원 (22명) -->
				<td class="center"><!-- 체크상자 -->
					<input type="checkbox" name="vote_<?=$row['MEMBER_KEY'] ?>" id="vote_<?=$row['MEMBER_KEY'] ?>" value="<?=$row['MEMBER_KEY'] ?>"  onclick="javascript:checkMySelf(this);">
				</td>
				<td><!-- 이름 -->
					<label for="vote_<?=$row['MEMBER_KEY'] ?>"><span id="name_<?=$row['MEMBER_KEY'] ?>"><?=$row['MEMBER_NAME'] ?></span></label>
				</td>
				<td><!-- 체크사유 (checked일경우에만 input 노출-->
					<input type="text" name="reason_<?=$row['MEMBER_KEY'] ?>" id="reason_<?=$row['MEMBER_KEY'] ?>" size="50"  disabled="disabled" title="체크사유">
				</td>
			</tr>
			
		<?php
			}
		 ?>
			
		</tbody>
	</table>
	<button type="submit">투표완료</button>
</form>

</div>
</body>
</html>


