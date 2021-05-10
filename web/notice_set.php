<?php include_once "./layout/header.php"; ?>
	
<!-- Body Header -->
<section class="container">
<?php include_once "./layout/lnb.php"; ?> 		
	<div class="contents">
		<h2 class="sub_title">글쓰기</h2>
		<hr class="horizon-line">
						
			<form name="frm" action="./admin/notice_save.php" method="post">
				<div class="write_upload">
					<input type="file" class="upload" id="uploadInputBox" name="Filedata">
					<strong>10MB</strong>이하의 파일만 등록할 수 있습니다.
				</div>					
				<script type="text/javascript" src="<?=$IMG_ROOT ?>/board/js/HuskyEZCreator.js" charset="utf-8"></script>
	
				<textarea name="content" id="content" rows="10" cols="100" style="width:100%; height:412px; display:none;" ><?=stripslashes($result[1]); ?></textarea>
				<!--textarea name="content" id="content" rows="10" cols="100" style="width:100%; height:412px; min-width:610px; display:none;"></textarea-->
				<!--
				<p>
					<input type="button" onclick="pasteHTML();" value="본문에 내용 넣기" />
					<input type="button" onclick="showHTML();" value="본문 내용 가져오기" />
					<input type="button" onclick="submitContents(this);" value="서버로 내용 전송" />
					<input type="button" onclick="setDefaultFont();" value="기본 폰트 지정하기 (궁서_24)" />
				</p>
				-->
			
				<script type="text/javascript">
					var oEditors = [];
					nhn.husky.EZCreator.createInIFrame({
						oAppRef: oEditors,
						elPlaceHolder: "content",
						sSkinURI: "<?=$IMG_ROOT ?>/board/SmartEditor2Skin.html",	
						htParams : {
							bUseToolbar : true,				// 툴바 사용 여부 (true:사용/ false:사용하지 않음)
							bUseVerticalResizer : true,		// 입력창 크기 조절바 사용 여부 (true:사용/ false:사용하지 않음)
							bUseModeChanger : true,			// 모드 탭(Editor | HTML | TEXT) 사용 여부 (true:사용/ false:사용하지 않음)
							fOnBeforeUnload : function(){
								//alert("아싸!");	
							}
						}, //boolean
						fOnAppLoad : function(){
							//예제 코드
							//oEditors.getById["content"].exec("PASTE_HTML", ["로딩이 완료된 후에 본문에 삽입되는 text입니다."]);
						},
						fCreator: "createSEditor2"
					});
					
					function pasteHTML() {
						
						var sHTML = "<span style='color:#FF0000;'>이미지도 같은 방식으로 삽입합니다.<\/span>";
						oEditors.getById["content"].exec("PASTE_HTML", [sHTML]);
					}
					
					function showHTML() {
						
						var sHTML = oEditors.getById["content"].getIR();
						alert(sHTML);
					}
						
					function submitContents(elClickedObj) {
						
						oEditors.getById["content"].exec("UPDATE_CONTENTS_FIELD", []);	// 에디터의 내용이 textarea에 적용됩니다.
						
						// 에디터의 내용에 대한 값 검증은 이곳에서 document.getElementById("content").value를 이용해서 처리하면 됩니다.
						
						try {
							elClickedObj.form.submit();
						} catch(e) {}
					}
					
					function setDefaultFont() {
						var sDefaultFont = '궁서';
						var nFontSize = 24;
						oEditors.getById["content"].setDefaultFont(sDefaultFont, nFontSize);
					}
				</script>
				<div class="crawl-submit">
					<input class="crawl-btn" type="button" onclick="submitContents(this);" value="서버로 내용 전송" />
					<input class="crawl-btn" type="button" onclick="javascript:history.back(1);" value="취소" />
				</div>
			</form>
		</div>
	</section>
	
<?php include_once "./layout/footer.php"; ?> 
	