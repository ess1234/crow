package ver3;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.Iterator;
import java.util.Stack;

import org.openqa.selenium.WebElement;
import org.springframework.context.ApplicationContext;
import org.springframework.context.support.ClassPathXmlApplicationContext;

import phantom.Phantom;

public class Controller implements Runnable {
	
	public ApplicationContext context = new ClassPathXmlApplicationContext("/config/phantom_config.xml");
	Phantom phantom = context.getBean("phantom", Phantom.class);
	
	private Stack<HashMap<String, String>> targetUrls = new Stack<HashMap<String, String>>();
	private HashMap<String, String> doneUrls = new HashMap<String, String>();
	private HashMap<String, String> subDomains = new HashMap<String, String>();
	private Stack<String> errorUrls = new Stack<String>();
	
	String domain = "";
	String domainSeq = "";
	
	public Controller(String domain, String domainSeq){
		this.domain = domain;
		this.domainSeq = domainSeq;
	}
	
	// first crawling
	public void run() {
		this.targetUrls.add(this.generateUrlMap(this.getUrl(this.domain), "0"));
		// loop
		do {
			HashMap<String, String> urlMap = this.targetUrls.pop();
			String url = this.getUrl(urlMap.get("url"));
			String parentSeq = this.getUrl(urlMap.get("parentSeq"));
			
			// url status check
			UrlStatus urlStatus = new UrlStatus();
			int status = urlStatus.getStatus(url);
			if(status != 200){
				this.saveUrl(url, 0, parentSeq, status);
				continue;
			}
			
			if (!checkUrl(url)) continue;

			try {
				
				try {
					Thread.sleep(1000);
				} catch (InterruptedException e) {
					e.printStackTrace();
				}
				
				// phantom set url
				phantom.setUrlPhantom(url);

				// done url add
				this.putDoneUrls(url);

				// phantom get links
				ArrayList links = phantom.getHref();
				
				// insert url
				String urlSeq = this.saveUrl(url, links.size(), parentSeq, status);

				// target url add
				this.putTargetUrl(links, urlSeq);

			} catch (Exception e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
				errorUrls.add(url);
			}
		} while (!this.targetUrls.empty());
		
		// phantom 종료
		phantom.killPhantom();
		
		// sub domain 저장
		this.saveSubDomain();
				
		// 트리 만들기
		new GenerateTree().createTree(this.domainSeq);
		
		// 완료	 url 상태 변경
		new ChangeState().completeCrawling(this.domainSeq);
		
		// scv 파일 만들기
		// new GenerateCSV().createCSV(this.domainSeq);
	}
	
	// second crawling (error 보정)
	
	public HashMap<String, String> generateUrlMap(String url, String parentSeq){
		HashMap<String, String> urlMap = new HashMap<String, String>();
		urlMap.put("url",url);
		urlMap.put("parentSeq",parentSeq);
		return urlMap;
	}
	
	// target url add
	public void putTargetUrl(ArrayList links, String parentSeq){
		
		for (int i = 0; i < links.size(); i++) {
			WebElement element = (WebElement) links.get(i);
			String url = element.getAttribute("href");
			
			if(url == null) continue;
			url =  this.getUrl(url);
			
			// 대상 url 수집
			if(this.checkUrl(url)) {
				// targetUrl 이미 포함되어 있는지 체크
				if(!this.checkTargetUrls(url)){
					HashMap<String, String> urlMap = new HashMap<String, String>();
					urlMap.put("url",url);
					urlMap.put("parentSeq",parentSeq);
					targetUrls.add(urlMap);
				}
			}
		}
	}
	
	public boolean checkTargetUrls(String url){
		Stack<HashMap<String, String>> tempTargetUrls = (Stack<HashMap<String, String>>)this.targetUrls.clone();
		while(!tempTargetUrls.empty()){
			HashMap<String, String> tempUrlMap = tempTargetUrls.pop();
			if(tempUrlMap.get("url").equals(url)) return true;
		}
		return false;
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
	
	// insert url 
	public String saveUrl(String url, int cnt, String parentSeq, int status){
		DataHandler dataHandler = new DataHandler();
		HashMap<String, String> data = new HashMap<String, String>();
		data.put("domainSeq", this.domainSeq);
		data.put("parentSeq", parentSeq);
		data.put("url", url);
		data.put("linkCnt", cnt+"");
		data.put("status", status+"");
		HashMap<String, String> result = dataHandler.insertUrl(data);
		// System.out.println(result.get("urlSeq"));
		return result.get("urlSeq");
	}
	
	// target url check
	public boolean checkUrl(String url){
		
		// null 제외
		if (url == null || url.equals(null)) return false;
		
		if (!url.startsWith("http://")) return false;
		
		// 같은 domain
		if (!url.startsWith(this.domain)) {
			// 서브 도메인 체크
			this.subDomainCheck(url);
			return false;
		}
		
		// 첨부 파일 및 링크 제외
		if (url.toLowerCase().indexOf(".zip") > -1 || url.toLowerCase().indexOf(".pdf") > -1 || url.toLowerCase().indexOf(".png") > -1 
				|| url.toLowerCase().indexOf(".jpg") > -1 || url.toLowerCase().indexOf(".gif") > -1 || url.toLowerCase().indexOf(".exe") > -1) return false;
			
		// 한번 돌았던 url 제외
		if (doneUrls.containsKey(url)) return false;
		
		// 파라미터 제거
		if (url.indexOf("?")>-1) {
			String tempUrl = url.split("\\?")[0];
			if (doneUrls.containsKey(tempUrl)) return false;
		}
		return true;
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
	
	// sub domain 체크
	public void subDomainCheck(String url) {
		String domain = this.domain.replaceFirst("http://www.", "");
		String tempUrl = "http://"+url.split("\\/")[2];
		if(tempUrl.indexOf(domain) > -1){
			if(!this.subDomains.containsKey(tempUrl)){
//				System.out.println("sub domain ===>>> "+tempUrl);
				this.subDomains.put(tempUrl, "done");
			}
				
		}
	}
	
	// sub domain 
	public void saveSubDomain(){
		Iterator<String> itr = this.subDomains.keySet().iterator();
		while(itr.hasNext()){
			String subDomain = itr.next();
			if(subDomain != null){
				saveSubDomain(subDomain);
			}
			
		}
	}
	
	// sub domain  
	public void saveSubDomain(String subDomain){
		DataHandler dataHandler = new DataHandler();
		HashMap<String, String> data = new HashMap<String, String>();
		data.put("domainSeq", this.domainSeq);
		data.put("subDomain", subDomain);
		dataHandler.insertSubDomain(data);
	}
}
