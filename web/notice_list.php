<?php include_once "./layout/header.php"; ?>
<?php

	$currentPage = $_GET['currentPage'];
	$viewCnt = $_GET['viewCnt'];
	if(empty($currentPage)||$currentPage=="") $currentPage=1; //현재페이지
	if(empty($viewCnt)||$viewCnt=="") $viewCnt = 10; //한 페이지당 게시물 수
	
	$SqlExecute = new dbMySql($connect);
	$table = " crawling.domain ";
	$where = " WHERE 1=1 ";
	
	$limit = " LIMIT ".(($currentPage-1)*$viewCnt).", ".($currentPage*$viewCnt);
	
	$strQuery = "select DOMAIN_SEQ, DOMAIN, CRAWLING_STATE, REQUEST_USER, REG_DTS, START_DTS, END_DTS from ".$table.$where." ORDER BY DOMAIN_SEQ DESC ".$limit;
	
	$total = $SqlExecute->dbCnt($table," DOMAIN_SEQ ",$where); //총 게시물 수
	
	$cnt = $total-(($currentPage-1)*$viewCnt); // 넘버링
	$result = $SqlExecute->dbQuery($strQuery);
	
	// echo "result ===>>> ".$strQuery;
	
	$SqlExecute->dbClose($connect);
?>
<!-- Body Header -->

<section class="container">
	<?php include_once "./layout/lnb.php"; ?>
	<div class="contents">
		<h2 class="sub_title">목록</h2>
		<hr class="horizon-line">
		
		<div class="crawl_list">
			<table class="crawl-table w-100">
				<caption>목록</caption>
				<colgroup>
					<col style="width: 50px">
					<col>
					<col style="width: 80px">
					<col style="width: 100px">
					<col style="width: 100px">
					<col style="width: 100px">
				</colgroup>
				<thead>
					<tr>
						<th scope="col">번호</th>
						<th scope="col">URL</th>
						<th scope="col">상태</th>
						<th scope="col">등록일</th>
						<th scope="col">시작일</th>
						<th scope="col">종료일</th>
					</tr>
				</thead>
					<tbody>
					<?php
						if($total > 0){
							while($row=mysql_fetch_array($result)){
					?>
						
							<tr>
								<td><?=$cnt-- ?></td>
								<td class="align-left"><a class="crawlShow crawl-link" href="#" data-rel="<?=$row["DOMAIN_SEQ"] ?>"><?=$row["DOMAIN"] ?></a></td>
								<td><span class="crawl_stats"><?=$row["CRAWLING_STATE"] ?></span></td>
								<td class="font-date"><?=$row["REG_DTS"] ?></td>
								<td class="font-date"><?=$row["START_DTS"] ?></td>
								<td class="font-date"><?=$row["END_DTS"] ?></td>
							</tr>
						
							
					<?php
							}
						} else {
						echo '데이타가 없습니다.';
					}
					?>
				</tbody>
			</table>
		</div>
	</div>
</section>

<?php include_once "./layout/tree.php"; ?>

<script>
	
</script>

<?php include_once "./layout/footer.php"; ?> 
