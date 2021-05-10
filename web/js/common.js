/*$(document).ready(function() {
	$('.crawlClose').click(function(){
		$('#crawlPop').hide();
		$('#crawlDimmed').hide();
	});
	
	$('#crawlDimmed').click(function(){
		$('#crawlPop').hide();
		$('#crawlDimmed').hide();
	});
	
	$('.crawlShow').click(function(){
		$('#crawlPop').show();
		$('#crawlPop').show();
		$('#crawlDimmed').show();
		var param = $(this).attr('data-rel');
		$.ajax({
		  url: "generateData.php?domainSeq="+param,
		  context: document.body
		}).done(function(html) {
		  $( '#crawlData' ).text(html).promise().done(function(){
			  ysTree.loadByStr('crawlData').promise().done(function(){
				  goTopLink();
			  });  
			  
		  });
		});
	});
	
});*/

$(document).ready(function() {
	$("#crawlSubmit").click(function(){
		var urlStr = $("#crawlURL").val();
		if(urlStr == ''){
			alert('URL을 입력해 주세요');
			$("#crawlURL").focus();
			return false;
		}
		urlStr = urlStr.trim();
		/*if(urlStr.indexOf('http://') < 0 && urlStr.indexOf('https://') < 0 ) {
			urlStr = 'http://'+urlStr;
		}*/
		if(isValidUrl(urlStr)){
			$("form[name='crawlForm']").submit();
		}else{
			alert("URL 형식이 아닙니다.");
			return false;
		}
	});
	
	$("#crawlOption select[name='listArrange']").change(function(){
		listOption();
	});
	
	$("#crawlOption input[name='listStat']").change(function(){
		listOption();
	});
	
});

function isValidUrl(urls) {
	 var chkExp = /([\w\-_]+(\.[\w\-_]+)+([\wㄱ-ㅎㅏ-ㅣ가-힣\;\-\.,@?^=%&:/~\+#]*[\w\-\@?^=%&/~\+#])?)/g;
	 return chkExp.test(urls);
}

function treeAjaxSet(file){
	var param = getUrlValue()['id'];
	$.ajax({
	  url: file+".php?domainSeq="+param,
	  context: document.body
	}).done(function(html) {
		if(html.length < 38){
			$( '#ysTree' ).html("<p class=\"no_page\">존재하는 페이지가 없습니다.</p>");
		}else{
		  $( '#crawlData' ).text(html).promise().done(function(){
			  ysTree.loadByStr('crawlData');
			  if($('#ysTree > ul > li').hasClass('ystree-folder')){$('#ysTree > ul > li').addClass('ystree-opened');}
			  goTopLink();
		  });
		}
	});
}

function getUrlValue()
{
    var vars = [], hash;
    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
    for(var i = 0; i < hashes.length; i++)
    {
        hash = hashes[i].split('=');
        vars.push(hash[0]);
        vars[hash[0]] = hash[1];
    }
    return vars;
}

function goTopLink(){
	// alert($( document ).height() + '  ' + $( window ).height());
	if(Number($( document ).height()) > Number($( window ).height())){
		$('#goToTop').fadeIn();
	}else {
		$('#goToTop').fadeOut();
	}
}

function listAjax(seq,stat,arrange,page){
	var param = getUrlValue()['id'];
	// alert("seqNum: "+seq+"     listStat: "+stat+"    listArrange: "+arrange+"    currentPage: "+page);
	$.ajax({
		type: "POST",
		url: "/layout/list.php",
		data: {seqNum:seq,listStat:stat,listArrange:arrange,currentPage:page},
		context: document.body
	}).done(function(list) {
	  $( '#crawlList' ).html(list);
	});
}

function listOption(pageNum){
	var seqNum = $("#seqNum").val();
	if(!seqNum || seqNum == ''){
		seqNum = '';
	}
	var listStat = $("#crawlOption input[name='listStat']").filter(':checked').val();
	if(!listStat || listStat == ''){
		listStat = '';
	}
	var listArrange = $("#crawlOption select[name='listArrange']").val();
	
	if(!listArrange || listArrange == ''){
		listArrange = '';
	}
	if(!pageNum || pageNum == ''){
		pageNum = '1';
	}
	// alert(listArrange);
	listAjax(seqNum,listStat,listArrange,pageNum);
}

function pageLink(page){
	listOption(page);
}

function reCrwaler(num){
	var seq = $("#seqNum").val();
	if(!seq || seq == ''){
		seq = '';
	}
	var stat = $("#crawlOption input[name='listStat']").filter(':checked').val();
	if(!stat || stat == ''){
		stat = '';
	}
	var arrange = $("#crawlOption select[name='listArrange']").val();	
	if(!arrange || arrange == ''){
		arrange = '';
	}
	var page = $("#pageNum").val();
	$.ajax({
		type: "POST",
		url: "/crawl_reload.php",
		data: {updateNum:num, seqNum:seq,listStat:stat,listArrange:arrange,currentPage:page},
		context: document.body
	}).done(function(list) {
	  $( '#crawlList' ).html(list);
	});
}