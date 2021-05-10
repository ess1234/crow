<?php
//page number 만들기
function createPaging($total, $currentPage, $viewCnt){ 	
		
	$last = ceil($total/$viewCnt);	
	$prev = ($currentPage > 1) ? ($currentPage-1) : 1;
	$next = ($currentPage < $last) ? ($currentPage+1) : $last;
	
	$page = ($currentPage > 5) ?  ($currentPage-4) : 1;
	$max = $page+9;

	echo"	<div id=\"currentPage\" class=\"crawl_paginate\">";
	echo"	<a href=\"#\" class=\"crawl-img paginate_first\" onclick=\"pageLink(1);return false;\"><span class=\"blind\">처음</span></a>";
			echo"	<a href=\"#\" class=\"crawl-img  paginate_prev\" onclick=\"pageLink($prev);return false;\"><span class=\"blind\">이전</span></a>";
			
			while($last>=$page){
				if($page == $currentPage){
					echo"	<span class=\"paginate_current\">".$page."</span>";
				} else {
					echo"	<a href=\"#\" class=\"paginate_num\" onclick=\"pageLink($page);return false;\">".$page."</a>";
				}
				$page++;
				if($page > $max || $page> $last) break;
			}

			echo"	<a href=\"#\" class=\"crawl-img paginate_next\" onclick=\"pageLink($next);return false;\"><span class=\"blind\">다음</span></a>";
			echo"	<a href=\"#\" class=\"crawl-img paginate_last\" onclick=\"pageLink($last);return false;\"><span class=\"blind\">끝</span></a>";
	echo"	</div>";
}

//달력 만들기
function createCalendar(){
	
	$year = date("Y");
	$month = date("m");
	$day = date("d");
	
	$firstDay = date("N",$firstDate);
	$lastDay = date("t", mktime(0,0,0,$month,$day,$year) );
	
	if ( $firstDay == 7 ) $firstDay = 0;

	$lastDate = mktime(0,0,0,$month,$lastDay,$year);
	
	$cellCnt = 0;
	
echo "<div style=\"height:200px; position:relative;\"> ";
echo "<div style=\"left:10px; top:10px; width:175px;\" class=\"ly_popup\"> ";
echo "<div class=\"shadow\"> ";
echo "<div class=\"shadow_side\"> ";
echo "<div class=\"shadow2\"> ";
echo "<div class=\"shadow_side2\"> ";
echo "	<div class=\"border_type\"> ";
echo "		<a class=\"close\" href=\"#\"> ";
echo "		<img alt=\"달력 레이어 닫기\" src=\"http://imgnews.naver.com/image/news/2007/new_section/btn_close.gif\" width=\"15\" height=\"14\"> ";
echo "		</a> ";
echo "		<table class=\"cal_simple\" border=\"1\" cellspacing=\"0\" summary=\"2007년 9월 달력\"> ";
echo "		<caption> ";
echo "			<a href=\"javascript:changeDate(".date("Ymd",$prevDate).");\"> ";
echo "			<img alt=\"이전달\" src=\"http://imgnews.naver.com/image/news/2007/new_section/ico_prev_ca.gif\" width=\"6\" height=\"7\"> ";
echo "			</a> ";
echo "			<strong>2007년 9월</strong> ";
echo "			<a href=\"#\"> ";
echo "			<img alt=\"다음달\" src=\"http://imgnews.naver.com/image/news/2007/new_section/ico_next_ca.gif\" width=\"6\" height=\"7\"> ";
echo "			</a> ";
echo "		</caption> ";
echo "		<thead> ";
echo "		<tr> ";
echo "		<th scope=\"col\">일</th> ";
echo "		<th scope=\"col\">월</th> ";
echo "		<th scope=\"col\">화</th> ";
echo "		<th scope=\"col\">수</th> ";
echo "		<th scope=\"col\">목</th> ";
echo "		<th scope=\"col\">금</th> ";
echo "		<th scope=\"col\">토</th> ";
echo "		</tr> ";
echo "		</thead> ";
echo "		<tbody> ";
echo "		<tr> ";
							for($i=0;$i<$firstDay;$i++){
								if ( $cellCnt % 7 == 0 ) echo "<td>&nbsp;</td>\n";
								else echo "<td>&nbsp;</td>\n";
								$cellCnt++;
							}
							for($i=1;$i<=$lastDay;$i++) {
      					if ( $cellCnt != 0 && $cellCnt % 7 == 0 ) echo "</tr>\n";
      					if ( $cellCnt % 7 == 0 ) echo "<tr>\n";
      					
      					if ( $cellCnt % 7 == 0 ) {
      						echo "<td align='center'><a href='".$IMG_ROOT."/lecture/calendar.php'><img src='".$IMG_ROOT."/img/num/".$i."_r.jpg' style='vertical-align:middle;'></a></td>\n";
      					} else if ( $cellCnt % 7 == 6 ) {
      						echo "<td align='center'";
      						if ( $i == 28 ) echo " class='today'";
      						echo "><a href='".$IMG_ROOT."/lecture/calendar.php'><img src='".$IMG_ROOT."/img/num/".$i."_b.jpg' style='vertical-align:middle;'></a></td>\n";
      					} else {
      						echo "<td align='center'";
      						if ( $i == 28 ) echo " class='today'";
      						echo "><a href='".$IMG_ROOT."/lecture/calendar.php'><img src='".$IMG_ROOT."/img/num/$i.jpg' style='vertical-align:middle;'></a></td>\n";
      					}
      					
      					$cellCnt++;
      				}
      				
      					for($i=0;$i<($cellCnt%7);$i++){
      					
      					if ( $cellCnt % 7 == 0 ) echo "<td>&nbsp;</td>\n";
      					else echo "<td>&nbsp;</td>\n";
      					$cellCnt++;
      				}
      			
echo "			</tr> ";
echo "		</table>	 ";
echo "	</div>	 ";
echo "	</div>	 ";
echo "	</div>	 ";
echo "	</div>	 ";
echo "	</div>	 ";
echo "	</div>	 ";
echo "	</div>	 ";
				
}	

//sms 발송 및 상태

function smsSend($SENDVALUE){

	global $SqlExecute;

	$sms_server	= "211.172.232.124";	## SMS 서버 
	$sms_id		= "schoolsafe";				## icode 아이디
	$sms_pw		= "0191";				## icode 패스워드
	$portcode	= 1;				## 정액제 : 2, 충전식 : 1
	
	$SMS	= new SMS;
	$SMS->SMS_con($sms_server,$sms_id,$sms_pw,$portcode);

	$tran_phone = str_replace("-","",$SENDVALUE['tran_phone']); //수신번호
	$tran_callback = str_replace(" ","",$SENDVALUE['tran_callback']); //회신번호
	$tran_msg = $SENDVALUE['tran_msg']; //발송 메세지
	$tran_date = $SENDVALUE['tran_date']; //발송시간

	$result = $SMS->Add($tran_phone, $tran_callback, $sms_id, $tran_msg, $tran_date);
	$result = $SMS->Send();
	if ($result) {
		//echo "SMS 서버에 접속했습니다.<br>";
		$success = $fail = 0;
		
		foreach($SMS->Result as $result) {
			list($phone,$code)=explode(":",$result);
			if ($code=="Error") {
				//echo $phone.'로 발송하는데 에러가 발생했습니다.<br>';
				$fail++;
			} else {
				//echo $phone."로 전송했습니다. (메시지번호:".$code.")<br>";
				$success++;
				
				// 발송 정보 디비 저장
				// $SqlExecute = new dbMySql($connect);
				$TBL_SETTING = 'SMS';
				$dbvalue['TRAN_PHONE'] = $tran_phone;
				$dbvalue['TRAN_CALLBACK'] = $tran_callback;
				$dbvalue['TRAN_MSG'] =$tran_msg;
				$dbvalue['TRAN_DATE'] =$tran_date;
				$dbvalue['TRAN_RESULT'] = $code;
				// insert
				$result = $SqlExecute->dbSetSql($TBL_SETTING,$dbvalue,'insert');
					
				unset($TBL_SETTING);
				unset($dbvalue);
				unset($where);
			}
		}
		//echo $success."건을 전송했으며 ".$fail."건을 보내지 못했습니다.<br>";
		$SMS->Init(); // 보관하고 있던 결과값을 지웁니다.
		$sendresult = $success."건을 전송했으며 ".$fail."건을 보내지 못했습니다.<br>";
	}else{
		$sendresult = 'error';
	}
	//else echo "에러: SMS 서버와 통신이 불안정합니다.<br>";

	return $sendresult;

}

//비밀번호확인
function confirmPasswd($passwd){
	if(!ereg("^[0-9a-zA-Z]{4,10}$", $passwd)){
		jsAlert_goto("비밀번호는 4~10자의 영문, 숫자만 입력가능합니다.","-1");
	}
}

//전자우편확인
function confirmEmail($email){
	if(ereg("([^[:space:]]+)",$email)){
		if(!ereg("(^[_0-9a-zA-Z-]+(\.[_0-9a-zA-Z-]+)*@[0-9a-zA-Z-]+(\.[0-9a-zA-Z-]+)*$)", $email,$regs)){
			jsAlert_goto("형식에 맞는 전자우편주소를 입력하십시오.","-1");
		}
	} else {
		jsAlert_goto("형식에 맞는 전자우편주소를 입력하십시오.","-1");
	}
}

// 빈문자열 경우 1을 리턴
function isBlank($str) {
	$temp=str_replace("　","",$str);
	$temp=str_replace("\n","",$temp);
	$temp=strip_tags($temp);
	$temp=str_replace("&nbsp;","",$temp);
	$temp=str_replace(" ","",$temp);
	if(eregi("[^[:space:]]",$temp)) return 0;
	return 1;
}


// URL, Mail을 자동으로 체크하여 링크만듬
function autoLink($str) {
	// URL 치환
	$homepage_pattern = "/([^\"\=\>])(mms|http|HTTP|ftp|FTP|telnet|TELNET)\:\/\/(.[^ \n\<\"]+)/";
	$str = preg_replace($homepage_pattern,"\\1<a href=\\2://\\3 target=_blank>\\2://\\3</a>", " ".$str);

	// 메일 치환
	$email_pattern = "/([ \n]+)([a-z0-9\_\-\.]+)@([a-z0-9\_\-\.]+)/";
	$str = preg_replace($email_pattern,"\\1<a href=mailto:\\2@\\3>\\2@\\3</a>", " ".$str);

	return $str;
}


/******************************메일발송******************************/
function funcMailSend ($ToName, $ToMail, $FromName, $FromMail, $MailSubject, $EmailBody){

	$MailHeader = "From: {$FromName}<{$FromMail}>\r\n";
  $MailHeader .= "Reply-To: {$FromMail}\r\n";
  $MailHeader .= "Return-Path: {$FromMail}\r\n";
  $MailHeader .= "Content-Type: text/html;charset=EUC-KR \n";

	$MailError = mail($ToMail, $MailSubject, $EmailBody, $MailHeader);

	return $MailError;
}

//요일 찾기
function getWeekCk($Date) { 
	$ArrayWeek =array("일","월","화","수","목","금","토");
	return $ArrayWeek[date('w',$Date)];
}
//시간 생성
function getCreateDate($year='',$month='',$day='',$type='') { 
	if(!$year){
		$year = date('Y');
	}
	if(!$month){
		$month = date('n');
	}
	if(!$day){
		$day = date('j');
	}
	if($type==1){
		$hour = date('H');
		$minute = date('i');
		$seconds = date('s');
		return mktime($hour,$minute,$seconds,$month,$day,$year);
	}else{
		return mktime(0,0,1,$month,$day,$year);
	}
	
}

//DB field type date,datetime
function getNowTimeDate($type){

	if($type==2){
		$nowdate = date('Ymd');
	}else{
		$nowdate = date('Y-m-d');
	}
	
	if($type==1){
		$nowdate .= ' '.date('H:i:s');
	}

	return $nowdate;
}

function getDateChar($datevalue){
	$charlen = strlen($datevalue);
	
	$years = substr($datevalue,0,4);
	$months = substr($datevalue,4,2);
	$days = substr($datevalue,6,2);

	if($charlen>8){
		$hours = substr($datevalue,8,2);
		$minutes = substr($datevalue,10,2);
		$seconds = substr($datevalue,12,2);

		return $years.'-'.$months.'-'.$days.' '.$hours.':'.$minutes.':'.$seconds;

	}else{
		return $years.'-'.$months.'-'.$days;
	}

}

function getTotalDays($months,$years){
	$date = date("t",mktime(0,0,1,$months,1,$years));
	return $date;
}

function getPrevNextDate($nowdate,$pntype,$mdtype){

	$arrNowDate = explode('-',$nowdate);

	$cutyear = $arrNowDate[0];
	$cutmonth = $arrNowDate[1];
	$cutday = $arrNowDate[2];

	if($mdtype=="m"){
		$plusminus = (60*60*24 *2);
	}else{
		$plusminus = (60*60*24);
	}

	if($pntype=="next"){

		if(!$cutday){
			$cutday = getTotalDays($cutmonth,$cutyear);
		}
		$adddays = mktime(0,0,1,$cutmonth,$cutday,$cutyear);
		$calcdate = $adddays + $plusminus;

	}else{

		if(!$cutday){
			$cutday = "01";
		}
		$adddays = mktime(0,0,1,$cutmonth,$cutday,$cutyear);
		$calcdate = $adddays - $plusminus;

	}

	$rtndate['Y'] = date('Y',$calcdate);
	$rtndate['M'] = date('m',$calcdate);
	$rtndate['D'] = date('d',$calcdate);
	return $rtndate;

}

//max 구하기
function createMax($max,$increase){
	if(!$max||$max==0||empty($max)){
		$no=$increase;
	}else{
		$no=$max+$increase;
	}

	return $no;
}

//0값으로 채우기
function creatZeroFill($no,$length){

	if(strlen($no)==$length){
		$zerofill=$no;
	}else{
		$zerofill=str_pad($no,$length,"0", STR_PAD_LEFT);
	}
	
	return $zerofill;
}

?>