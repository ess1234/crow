package ver4;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.Iterator;
import java.util.List;
import java.util.Stack;

import org.openqa.selenium.By;
import org.openqa.selenium.WebElement;
import org.springframework.context.ApplicationContext;
import org.springframework.context.support.ClassPathXmlApplicationContext;

import phantom.Phantom;

public class Controller implements Runnable {
	
	public ApplicationContext context = new ClassPathXmlApplicationContext("/config/phantom_config.xml");
	public Phantom phantom = context.getBean("phantom", Phantom.class);
	
	private DataHandler dataHandler = new DataHandler();
	public UrlStatus urlStatus = new UrlStatus();
	public GenerateSiteMap generateSiteMap = new GenerateSiteMap();
	
	private Stack<HashMap<String, String>> targetUrls = new Stack<HashMap<String, String>>();
	private HashMap<String, String> doneUrls = new HashMap<String, String>();
	private HashMap<String, String> subDomains = new HashMap<String, String>();
	private Stack<String> errorUrls = new Stack<String>();
	
	public String domain = ""; 
	public String domainSeq = "";
	
	public Controller(String domain, String domainSeq){
		this.domain = domain;
		this.domainSeq = domainSeq;
	}
	
	// first crawling
	public void run() {
		// 진행중인 url 상태 변경
		new ChangeState(dataHandler).goingCrawling(this.domainSeq);
		
		this.targetUrls.add(this.generateUrlMap(this.getUrl(this.domain), "0"));
		
		// sitemap 만들기
		try {
			this.generateSiteMap();
		} catch (Exception e1) {
			// TODO Auto-generated catch block
			e1.printStackTrace();
			// 실패
			new ChangeState(dataHandler).failCrawling(this.domainSeq);
		}
		// if(true) return;
				
		// loop
		do {
			HashMap<String, String> urlMap = this.targetUrls.pop();
			String url = this.getUrl(urlMap.get("url"));
			String parentSeq = this.getUrl(urlMap.get("parentSeq"));
			
			try {
				Thread.sleep(1000);
			} catch (InterruptedException e) {
				e.printStackTrace();
			}
			
			try {
				// url status check
				int status = urlStatus.getStatus(url);
				if(status != 200){
					this.saveUrl(url, 0, parentSeq, status, "");
					this.putDoneUrls(url);
					continue;
				}
				
				if (!checkUrl(url)) continue;
				
				// phantom set url
				phantom.setUrlPhantom(url);

				// done url add
				this.putDoneUrls(url);

				// phantom get links
				ArrayList links = phantom.getHref();
				
				// real url
				String targetUrl = phantom.getCurrentUrl();
				
				// insert url
				String urlSeq = this.saveUrl(url, links.size(), parentSeq, status, targetUrl);

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
		new GenerateTree(dataHandler).createTree(this.domainSeq);
		
		HashMap<String, String> param = new HashMap<String, String>();
		param.put("domainSeq", this.domainSeq);
		if(dataHandler.checkCrawling(param) > 0){
			// 완료
			new ChangeState(dataHandler).completeCrawling(this.domainSeq);
		} else {
			// 실패
			new ChangeState(dataHandler).failCrawling(this.domainSeq);
		}
		
		// scv 파일 만들기
		// new GenerateCSV().createCSV(this.domainSeq);
	}
	
	// second crawling (error 보정)
	
	// sitemap
	public void generateSiteMap() throws Exception  {

		phantom.setUrlPhantom(this.domain);
		
		List<WebElement> navs = phantom.getSrcInNav();
		String url = "";
		String title = phantom.getTitle();

		String rootSeq = saveNavLink(url, "0", "root", title);
		
		for (int i = 0; i < navs.size(); i++) {
			WebElement nav = (WebElement) navs.get(i);
			
			// display : none 제외
			if(nav.getCssValue("display") == "none") {
				// System.out.println("nav display:none");
				continue;
			}
			
			String navPath = generateSiteMap.findAncestor(nav);
			url = "";
			
			// nav title 가져오기
			title = generateSiteMap.getNavTitle(navPath);
			String parentSeq = saveNavLink(url, rootSeq, navPath, title);
			
			// path sava
			HashMap<String, String> navPaths = new HashMap<String, String>();
			navPaths.put(navPath, parentSeq );

			List<WebElement> navLinks = (List<WebElement>) nav.findElements(By.tagName("a"));

			for (int j = 0; j < navLinks.size(); j++) {
				WebElement navLink = (WebElement) navLinks.get(j);
				
				url = navLink.getAttribute("href");
				title = navLink.getAttribute("innerHTML");
				
				// title 태그 제거
				title = generateSiteMap.removeTag(title);		
				
				navPath = generateSiteMap.findAncestor(navLink);
				
				// 상위 태그 seq 찾기
				String tempNavPath = navPath;
				do{
					tempNavPath = tempNavPath.substring(0, tempNavPath.lastIndexOf(">"));
					tempNavPath = tempNavPath.trim();
				}while(!navPaths.containsKey(tempNavPath));
				parentSeq = navPaths.get(tempNavPath);
				
				String navSeq = saveNavLink(url, parentSeq, navPath, title);
				navPaths.put(navPath, navSeq );
			}
		}
	}
		
	// insert nav path 
	public String saveNavPath(String navPath){
		// DataHandler dataHandler = new DataHandler();
		HashMap<String, String> data = new HashMap<String, String>();
		data.put("domainSeq", this.domainSeq);
		data.put("parentSeq", "0");
		data.put("url", this.domain);
		data.put("navPath", navPath);
		data.put("title", "");
		HashMap<String, String> result = dataHandler.insertNav(data);
		return result.get("navSeq");
	} 
	
	// insert nav link 
	public String saveNavLink(String url, String parentSeq, String navPath, String title ){
		// DataHandler dataHandler = new DataHandler();
		HashMap<String, String> data = new HashMap<String, String>();
		data.put("domainSeq", this.domainSeq);
		data.put("parentSeq", parentSeq);
		data.put("url", url);
		data.put("navPath", navPath);
		data.put("title", title);
		data.put("status", "200");
		HashMap<String, String> result = dataHandler.insertNav(data);
		// System.out.println(result.get("urlSeq"));
		return result.get("navSeq");
	} 
	
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
	public String saveUrl(String url, int cnt, String parentSeq, int status, String targetUrl){
		// DataHandler dataHandler = new DataHandler();
		HashMap<String, String> data = new HashMap<String, String>();
		data.put("domainSeq", this.domainSeq);
		data.put("parentSeq", parentSeq);
		data.put("url", url);
		data.put("targetUrl", targetUrl);
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
		// DataHandler dataHandler = new DataHandler();
		HashMap<String, String> data = new HashMap<String, String>();
		data.put("domainSeq", this.domainSeq);
		data.put("subDomain", subDomain);
		dataHandler.insertSubDomain(data);
	}
}
