<?php include_once "./layout/header.php"; ?>
<?php include_once "./layout/tree_layout.php"; ?>

<script>
treeAjaxSet('generateData');
$( window ).resize(function() {
	goTopLink();
});
</script>

<?php
	$domainSeq = $_GET['id'];	
	$table = " crawling.url_atag c  ";
	$where = " where c.domain_seq = ".$domainSeq;

	$strQuery = "select count(*), " 
				."sum(if(c.status = 200, 1, 0)) from "
				.$table
				.$where;
	
	$result = $SqlExecute->dbQuery($strQuery);
	$value = mysql_fetch_array($result);
	$SqlExecute->dbClose($connect);
?>
	<p class="crawler_link_num">총 <strong><?=$value[0] ?>개</strong>의 링크 유형이 수집되었습니다. 
	<?php if($value[0]-$value[1] > 0) {?> 이 중 접근할 수 없는 링크는 <strong><?=$value[0]-$value[1] ?>개</strong>입니다. <?php } ?></p>
	<div id="ysTree" class="crawl_tree <? if(strpos($host,'crawl_error.php') !== false){ ?>error_tree<? } ?>">
		<div style="text-align:center;"><img src="/images/loading.gif" /></div>
	</div>
	<textarea id="crawlData" class="blind"></textarea>
</section>
<?php include_once "./layout/footer.php"; ?> 



