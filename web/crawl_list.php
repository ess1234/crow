<?php include_once "./layout/header.php"; ?>

<section class="container">
	<h1>수집 도메인</h1>
	<div id ="crawlOption" class="crwal_option">
		<p class="crawl_tip">수집완료시간은 해당 수집 페이지수에 따라서 늘어날 수도 있습니다.</p>
		<ul class="crawl_arrange">
			<li><input type="radio" id="listStat1" name="listStat" checked="checked" value=""><label for="listStat1">전체</label></li>
			<li><input type="radio" id="listStat2" name="listStat" value="END"><label for="listStat2">완료</label></li>
			<li><input type="radio" id="listStat3" name="listStat" value="ING"><label for="listStat3">수집중</label></li>
			<li><input type="radio" id="listStat4" name="listStat" value="WAIT"><label for="listStat4">대기</label></li>
			<li>
				<select name="listArrange">
					<option value="">등록순</option>
					<option value="DOMAIN">URL순</option>
					<option value="END_DTS">수집완료순</option>
				</select>
			</li>
		</ul>
	</div>
	
	<?php include_once "./layout/list.php"; ?>

</section>


<?php include_once "./layout/footer.php"; ?>
