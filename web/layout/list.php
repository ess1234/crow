<?php
	include_once $_SERVER["DOCUMENT_ROOT"]."/lib/config_db.php"; //db 정보
	include_once $_SERVER["DOCUMENT_ROOT"]."/lib/class_mysql.php"; //db class
	include_once $_SERVER["DOCUMENT_ROOT"]."/lib/fnc_common.php"; //기본 기능
	$SqlExecute = new dbMySql($connect);
	
	$currentPage = $_POST['currentPage'];
	$viewCnt = $_GET['viewCnt'];
	
	$seqNum = $_POST["seqNum"];
	$listStat = $_POST["listStat"];
	$listArrange = $_POST["listArrange"];
	$where = " WHERE 1=1 ";
	$table = " crawling.domain b ";
	$order = " ORDER BY DOMAIN_SEQ DESC ";
	//echo $listStat;
	if(empty($currentPage)||$currentPage=="") $currentPage=1; //현재페이지
	if(empty($viewCnt)||$viewCnt=="") $viewCnt = 10; //한 페이지당 게시물 수
	if(!empty($seqNum) && $seqNum != "") $where = $where." AND DOMAIN_SEQ = ".$seqNum." ";	
	if(!empty($listStat) && $listStat != "") $where = $where." AND CRAWLING_STATE = '".$listStat."' ";
	if($listArrange == "DOMAIN") {
		$order = " ORDER BY ".$listArrange." ASC ";
	}else if($listArrange == "END_DTS"){
		$order = " ORDER BY ".$listArrange." DESC ";
	}else{
		$order = " ORDER BY DOMAIN_SEQ DESC ";
	}

	//echo $listArrange;
	$limit = " LIMIT ".(($currentPage-1)*$viewCnt).", ".($currentPage*$viewCnt);
	$strQuery = "select DOMAIN_SEQ, DOMAIN, CRAWLING_STATE, REQUEST_USER, REG_DTS, START_DTS, END_DTS, "
						." (select count(*) from url_atag c where c.DOMAIN_SEQ = b.DOMAIN_SEQ) CNT, " 
						." (select count(*) from url_atag c where c.DOMAIN_SEQ = b.DOMAIN_SEQ and c.STATUS != 200 ) ERROR_CNT "
						." from "
						.$table.$where.$order.$limit;	
	$total = $SqlExecute->dbCnt($table," DOMAIN_SEQ ",$where); //총 게시물 수
	
	$cnt = $total-(($currentPage-1)*$viewCnt); // 넘버링
	$result = $SqlExecute->dbQuery($strQuery);
	
	//echo "result ===>>> ".$strQuery;
	//$pagination = createPaging($total, $currentPage, $viewCnt);
	$SqlExecute->dbClose($connect);

?>
<div id="crawlList">
	<div class="crawl_list">
		<input id="seqNum" type="hidden" value="<?=$seqNum ?>">
		<table class="crawl-table w-100">
			<caption>목록</caption>
			<colgroup>
				<col style="width:70px">
				<col>
				<col style="width:120px">
				<col style="width:160px">
			</colgroup>
			<thead>
				<tr>
					<th scope="col">번호</th>
					<th scope="col">URL <span class="font-normal">(에러페이지수 / 총페이지수)</span></th>
					<th scope="col">상태</th>
					<th scope="col">수집완료일</th>
				</tr>
			</thead>
				<tbody>
				<?php
					if($total > 0){
						while($row=mysql_fetch_array($result)){
				?>
					
						<tr>
							<td class="font-date"><?=$cnt-- ?></td>
							<td class="align-left">
								<?php if($row["CRAWLING_STATE"] == "END"){ ?>
								<a class="crawlShow crawl-link" href="/crawl_tree.php?id=<?=$row["DOMAIN_SEQ"] ?>"><?=$row["DOMAIN"] ?></a>
								<span class="crawl_count">(<?=$row["ERROR_CNT"] ?> / <?=$row["CNT"] ?>)</span>
								<?php }else{ ?><span class="crawl_nolink"><?=$row["DOMAIN"] ?></span>
								<?php } ?>
							</td>
							<td class="crawl_stats">
								<?php if($row["CRAWLING_STATE"] == "END"){ ?><div class="stats_end">완료</div>
								<?php }else if($row["CRAWLING_STATE"] == "ING"){ ?><div class="stats_ing">수집중</div>
								<?php }else if($row["CRAWLING_STATE"] == "FAIL"){ ?><div class="stats_wait">실패</div>
								<?php }else{ ?><div class="stats_wait">대기중</div>
								<?php } ?>
							</td>
							
							<td class="font-date">
								<?php if($row["CRAWLING_STATE"] == "WAIT"){ ?>대기중
								<?php }else if($row["CRAWLING_STATE"] == "ING"){ ?>수집중
								<?php }else{ ?>
									<?=$row["END_DTS"] ?>
									<a class="crawl-btn2" style="margin-top:3px;" href="#" onclick="reCrwaler(<?=$row["DOMAIN_SEQ"] ?>); return false;">재수집</a>
								<?php } ?>
							</td>
						</tr>
					
						
				<?php
						}
					}else{
				?>
						<td class="no-result" colspan="4">데이타가 없습니다</td>
				<?php
					}
				?>
			</tbody>
		</table>
	</div>
	<input type="hidden" id="pageNum" value="<?=$currentPage ?>">
	<?php
	if($total < $viewCnt){
	}else{
		createPaging($total, $currentPage, $viewCnt);
	}
	?>
</div>