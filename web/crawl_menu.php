<?php include_once "./layout/header.php"; ?>
<?php include_once "./layout/tree_layout.php"; ?>

<?php
	$domainSeq = $_GET['id'];	
	$table = " crawling.url_nav c  ";
	$where = " where c.domain_seq = ".$domainSeq;
	
	$strQuery = "select NAV_SEQ, DOMAIN_SEQ from" 
				.$table
				.$where;
	
	$total =  $SqlExecute->dbRownum($strQuery);
	$SqlExecute->dbClose($connect);
?>
	<?php

	if($total  == 1){
	?>
	<p class="no_page">문서 규격이 HTML5가 아니거나, 네비게이션을 찾을 수 없습니다.</p>

	<?php }	else{ ?>

		<script>
		treeAjaxSet('generateData4');
		$( window ).resize(function() {
			goTopLink();
		});
		</script>

		<div id="ysTree" class="crawl_tree <? if(strpos($host,'crawl_error.php') !== false){ ?>error_tree<? } ?>">
			<div style="text-align:center;"><img src="/images/loading.gif" /></div>
		</div>
		<textarea id="crawlData" class="blind"></textarea>
	<?php }   ?>
</section>
<?php include_once "./layout/footer.php"; ?> 