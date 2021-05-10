package ver2;

import java.io.BufferedWriter;
import java.io.File;
import java.io.FileWriter;
import java.io.IOException;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.Stack;

import org.openqa.selenium.WebElement;
import org.springframework.context.ApplicationContext;
import org.springframework.context.support.ClassPathXmlApplicationContext;

import phantom.Phantom;
import utils.Util;

public class Controller implements Runnable {
	
	public ApplicationContext context = new ClassPathXmlApplicationContext("/config/phantom_config.xml");
	Phantom phantom = context.getBean("phantom", Phantom.class);
	
	private Stack<String> targetUrls = new Stack<String>();
	private HashMap<String, String> doneUrls = new HashMap<String, String>();
	private Stack<String> errorUrls = new Stack<String>();
	private ArrayList urls = new ArrayList();
	
	private HashMap<String, String> keyValue = new HashMap<String, String>();
	int idx = 1;
	
	String domain = "";
	String domainSeq = "";
	
	public Controller(String domain, String domainSeq){
		this.domain = domain;
		this.domainSeq = domainSeq;
	}
	
	// first crawling
	public void run() {
		this.targetUrls.add(this.domain);
		// loop
		do {
			String url = this.getUrl(this.targetUrls.pop());
			if (!checkUrl(url)) continue;
			
			// 카테고라이징!!
			this.makeParent(url);

			try {
				// phantom set url
				phantom.setUrlPhantom(url);

				// done url add
				this.putDoneUrls(url);

				// phantom get links
				ArrayList links = phantom.getHref();

				// crawling url add
				this.putCrawlingUrl(url, links.size());

				// target url add
				this.putTargetUrl(links);

			} catch (Exception e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
			}
		} while (!this.targetUrls.empty());

		// database 저장
		this.saveCrawling();
		
		// scv 파일 만들기
		this.createSCV();

		// phantom 종료
		phantom.killPhantom();
	}
	
	// second crawling (error 보정)
	
	// target url add
	public void putTargetUrl(ArrayList links){
		
		for (int i = 0; i < links.size(); i++) {
			WebElement element = (WebElement) links.get(i);
			String url = element.getAttribute("href");
			if(url == null) continue;
			
			url =  this.getUrl(url);
			
			// 대상 url 수집
			if(this.checkUrl(url)) targetUrls.add(url);
		}
	}
	
	// done url add
	public void putDoneUrls(String url){
		// url 넣기
		doneUrls.put(url, "done");
		// 파라미터 제거 url 넣기
		if (url.indexOf("?")>-1) {
			String tempUrl = url.split("\\?")[0];
			if (!doneUrls.containsKey(tempUrl)) doneUrls.put(tempUrl, "done");
		}
	}
	
	// crawling url add
	public void putCrawlingUrl(String url, int cnt){
		this.keyValue.put(url, String.valueOf(this.idx++));
		String parentUrl = url.substring(0, url.lastIndexOf("/"));
//		System.out.println("domain key ===>>> " + this.domainSeq + " :: url ===>> " + url + " :: key ===>>> "+ (idx-1) + " :: links count ===>>> " + cnt + " :: parent key ===>>> " + this.keyValue.get(parentUrl));
		String parentSeq =  (this.keyValue.get(parentUrl) == null) ? "0":this.keyValue.get(parentUrl);
		
		HashMap<String, String> data = new HashMap<String, String>();
		data.put("crawlingSeq", (idx-1)+"");
		data.put("domainSeq", this.domainSeq);
		data.put("parentSeq", parentSeq);
		data.put("url", url);
		data.put("linkCnt", cnt+"");
		urls.add(data);
	}
	
	// target url check
	public boolean checkUrl(String url){
		
		// null 제외
		if (url == null || url.equals(null)) return false;
		
		// 같은 domain
		if (!(url.indexOf(this.domain) > -1)) return false;
		
		// 첨부 파일 및 링크 제외
		if (url.indexOf(".zip") > -1 || url.indexOf(".pdf") > -1 || url.indexOf(".png") > -1 
				|| url.indexOf(".jpg") > -1 || url.indexOf(".gif") > -1 || url.indexOf(".exe") > -1) return false;
			
		// 한번 돌았던 url 제외
		if (doneUrls.containsKey(url)) return false;
		
		// 파라미터 제거
		if (url.indexOf("?")>-1) {
			String tempUrl = url.split("\\?")[0];
			if (doneUrls.containsKey(tempUrl)) return false;
		}
		return true;
	}
	
	// database 저장
	public void saveCrawling(){
		
		DataHandler dataHandler = new DataHandler();
		
		for(int i=0; i<urls.size(); i++){
			HashMap<String, String> data = (HashMap<String, String>) urls.get(i);
			dataHandler.insertCrawlingData(data);
		}
		
		// 진행중인 url 상태 변경
		HashMap<String, String> param = new HashMap<String, String>();
		param.put("domainSeq", String.valueOf(this.domainSeq));
		param.put("crawlingState", "END");
		int result = dataHandler.updateDomainStatus(param);
	}
	
	public void createSCV(){
		String result = "id,parent,url,link,count,desc"+"\n";
		DataHandler dataHandler = new DataHandler();
		HashMap<String, String> param = new HashMap<String, String>();
		param.put("domainSeq", String.valueOf(this.domainSeq));
		ArrayList ar = (ArrayList)dataHandler.getCrawlingList(param);
		for(int i=0; i<ar.size(); i++){
			HashMap<String, String> data = (HashMap<String, String>) ar.get(i);
			result += String.valueOf(data.get("CRAWLING_SEQ"))+","+String.valueOf(data.get("PARENT_SEQ"))+","+(String.valueOf(data.get("URL"))).replaceFirst("http://", "")
					+","+String.valueOf(data.get("URL"))+","+String.valueOf(data.get("LINK_CNT"))+",desc"+"\n";
		}
		this.createFile(this.domainSeq+".csv", result);
	}
	
	// scv 파일 만들기
	public void createFile(String filename, String result){
		Util util = new Util();
		String realPath = util.readDeployInfo("FILE_PATH");
		File file = new File(realPath+filename);
		try {
			BufferedWriter out = new BufferedWriter(new FileWriter(file));
			out.write(result);
			out.flush();
			out.close();
		} catch (IOException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
	}
	
	// error url add
	
	// get url 
	public String getUrl(String url){
		// # 뒤에 제외
		if (url.lastIndexOf("#") > -1) url = url.substring(0, url.lastIndexOf("#"));
		// / 뒤에 제외
		if (url.lastIndexOf("/") > -1 && url.lastIndexOf("/") == url.length()-1)  url = url.substring(0, url.lastIndexOf("/"));
		return url;
	}
	
	// make parentUrl	
	public void makeParent(String url){
		if(!url.equals(domain) && url.indexOf("/")>-1) {
			String parentUrl = url.substring(0, url.lastIndexOf("/"));
			if(!this.keyValue.containsKey(parentUrl)){
				this.keyValue.put(parentUrl, String.valueOf(this.idx++));
				this.putCrawlingUrl(parentUrl, 0);
			}
		}
	}

}
