<?php include_once "./layout/header.php"; ?>
<?php include_once "./layout/tree_layout.php"; ?>
<?php
	$domainSeq = $_GET['id'];	
	$table = " crawling.url_atag c  ";
	$where = " where c.domain_seq = ".$domainSeq;
	
	$strQuery = "select" 
				."(SELECT URL FROM url_atag p WHERE c.PARENT_SEQ = p.URL_SEQ AND c.DOMAIN_SEQ = p.DOMAIN_SEQ ) parent , "
				."c.* from "
				.$table
				.$where
				." and c.status != 200 "
				."order by url_seq";
	
	$total =  $SqlExecute->dbRownum($strQuery);
	$result = $SqlExecute->dbQuery($strQuery);
	$SqlExecute->dbClose($connect);
?>
	<?php

	if($total > 0){
		$num = 1;
	?>
	<p class="crawler_link_num">총 <strong><?=$total ?>개</strong>의 오류 링크가 있습니다.</p>
	<ul class="crawl_list2">	
	<?php while($row=mysql_fetch_array($result)){ ?>
		<li>
			<span class="font-date"><?=$num++ ?></span>
			<div class="org">
				<span class="tx_error_code"><span class="blind">Error Code: </span><?=$row["STATUS"]?></span>
				<a href="<?=$row["URL"] ?>" target="_blank" class="crawl-link"><?=$row["URL"] ?></a>
			</div>
			<div class="meta">
				<span class="tx_label">Child of</span>
				<a href="<?=$row["parent"] ?>" target="_blank" class="crawl-link"><?=$row["parent"] ?></a>
			</div>
		</li>
	
	<?php }   ?>
    </ul>
	<?php }	else{ ?>
	<p class="no_page">Error Link가 없습니다.</p>
	<?php }   ?>
</section>
<a href="#" id="goToTop" class="crawl-img img-gotop crawl_gotop"><span class="blind">상단으로</span></a>
<?php include_once "./layout/footer.php"; ?> 
