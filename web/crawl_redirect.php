<?php include_once "./layout/header.php"; ?>
<?php include_once "./layout/tree_layout.php"; ?>
<?php
	$domainSeq = $_GET['id'];	
	$table = " crawling.url_atag ";
	$where = " where url != if(right(TARGET_URL, 1)='/', substr(TARGET_URL, 1, length(TARGET_URL)-1), TARGET_URL) and status = 200 and domain_seq = ".$domainSeq;
	
	$strQuery = "select DOMAIN_SEQ, PARENT_SEQ, URL_SEQ, URL, TARGET_URL, LINK_CNT, STATUS, REG_DTS "
						." from".$table
						.$where
						." order by url";
						
	$total =  $SqlExecute->dbRownum($strQuery);
	$result = $SqlExecute->dbQuery($strQuery);
	$SqlExecute->dbClose($connect);
?>
	<?php

	if($total > 0){
		$num = 1;
	?>
	<p class="crawler_link_num">총 <strong><?=$total ?>개</strong>의 변경되는 링크가 있습니다.</p>
	<ul class="crawl_list2">
	<?php while($row=mysql_fetch_array($result)){ ?>
		<li class="redirect">
			<span class="font-date"><?=$num++ ?></span>
			<div class="org">
				<a href="<?=$row["URL"] ?>" target="_blank" class="crawl-link"><?=$row["URL"] ?></a>
			</div>
			<div class="meta">
				<span class="tx_label">Redirect to</span>
				<a href="<?=$row["TARGET_URL"] ?>" target="_blank" class="crawl-link"><?=$row["TARGET_URL"] ?></a>
			</div>
		</li>
	
	<?php }   ?>
    </ul>
	<?php }	else{ ?>
	<p class="no_page">Redirect Link가 없습니다.</p>
	<?php }   ?>
</section>
<a href="#" id="goToTop" class="crawl-img img-gotop crawl_gotop"><span class="blind">상단으로</span></a>
<?php include_once "./layout/footer.php"; ?> 
