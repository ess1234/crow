package sample;

import java.io.BufferedWriter;
import java.io.File;
import java.io.FileWriter;
import java.io.IOException;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;

import model.UrlModel;

import org.openqa.selenium.WebElement;
import org.springframework.context.ApplicationContext;
import org.springframework.context.support.ClassPathXmlApplicationContext;
import org.springframework.stereotype.Service;

import phantom.Phantom;
import utils.Util;
import dao.CrawlingDAO;

@Service
public class Crawling extends Common {
	
	ApplicationContext context = new ClassPathXmlApplicationContext("/config/db_config.xml");
	CrawlingDAO dao = context.getBean("dao", CrawlingDAO.class);

	String domain = "";
	String url = "";
	ArrayList urls = new ArrayList();
	Phantom phantom;
	
	public List getDomainList(HashMap param){
		return dao.getDomainList(param);
	}
	
	public Crawling(Phantom phantom) {
		super();
		// TODO Auto-generated constructor stub
		this.phantom = phantom;
	}
	
	// domain ?∏ÌåÖ
	public void setDomain(String domain){
		this.domain = domain;
	}
		
	// url ?∏ÌåÖ
	public boolean setUrl(String url){
		this.url = url;
		try {
			phantom.setUrlPhantom(url);
		} catch (Exception e) {
			// TODO: handle exception
			e.printStackTrace();
			return false;
		}
		return true;
	}
	
	// depthÎ≥?ÎßÅÌÅ¨ Í∞?†∏?§Í∏∞
	public void getLinks(int depth) {
		ArrayList ar = new ArrayList();
		try {
			ar = phantom.getHref();
		} catch (Exception e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
			return;
		}
		
		String currentUrl = phantom.getCurrentUrl();
		String parentUrl = "0";

		UrlModel model = new UrlModel();
		ArrayList tempUrls = new ArrayList();

		if (ar == null) return;

		for (int i = 0; i < ar.size(); i++) {
			WebElement element = (WebElement) ar.get(i);
			String tempUrl = element.getAttribute("href");
						
			// System.out.println(tempUrl);
			tempUrls.add(i, tempUrl);

			// ??ÉÅ url ?òÏßë
			if (checkUrl(tempUrl)) {
				targetUrls.push(tempUrl);
			}
		}

		if (depth > 0)
			parentUrl = url.substring(0, url.lastIndexOf("/"));
		if (depth > 0 && !doneUrls.containsKey(parentUrl))
			targetUrls.push(parentUrl);

		model.setUrl(url);
		model.setTargetUrl(currentUrl);
		model.setCnt(ar.size());
		model.setLinks(tempUrls);
		model.setDepth(depth);
		model.setParentUrl(parentUrl);

		urls.add(model);
	}
	
	public void putParent(String parentUrl){
		UrlModel model = new UrlModel();
		
		model.setUrl(parentUrl);
		model.setTargetUrl("");
		model.setCnt(0);
		model.setDepth(0);
		model.setParentUrl( parentUrl.substring(0, parentUrl.lastIndexOf("/")) );
		
		urls.add(model);
	}
	
	// doneUrls ??crwaling url ?£Í∏∞
	public void putDoneUrls(String url){
		doneUrls.put(url, "done");
		// ?åÎùºÎØ∏ÌÑ∞ ?úÍ±∞ url ?£Í∏∞
		if (url.indexOf("?")>-1) {
			String tempUrl = url.split("\\?")[0];
			if (!doneUrls.containsKey(tempUrl)) doneUrls.put(tempUrl, "done");
		}
	}
	
	// ?¥Î? crwaling ??url Í≤?¶ù
	public boolean wasCrwalingUrl(String url){
		return doneUrls.containsKey(url);
	}
	
	public void printCrawling(){
//		System.out.println("===================");
		String result = "";
		for(int i=0; i<urls.size(); i++){
			UrlModel model = (UrlModel)urls.get(i);
			result +=model.getUrl()+","+model.getParentUrl()+","+model.getUrl()+"\n";
			System.out.println(model.getUrl()+","+model.getParentUrl()+","+model.getUrl());
//			System.out.println(model.toString());
		}
//		System.out.println("===================");
//		System.out.println(doneUrls.toString());
//		System.out.println("===================");
		
		Util util = new Util();
		this.createFile(util.readDeployInfo("FILE_NAME"), result);
	}
	
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
	
	
	public boolean checkUrl(String url){
		// null ?úÏô∏
		if (url == null || url.equals(null)) return false;
		
		// Í∞ôÏ? domain
		if (!(url.indexOf(domain) > -1)) return false;
		
		// Ï≤®Î? ?åÏùº Î∞?ÎßÅÌÅ¨ ?úÏô∏
		if (url.indexOf(".zip") > -1 || url.indexOf(".pdf") > -1 || url.indexOf(".png") > -1 
				|| url.indexOf(".jpg") > -1 || url.indexOf(".gif") > -1 || url.indexOf(".exe") > -1) return false;
			
		// ?úÎ≤à ?åÏïò??url ?úÏô∏
//		HashMap<String, String> doneUrls = c.doneUrls;
		if (doneUrls.containsKey(url)) return false;
		
		// ?åÎùºÎØ∏ÌÑ∞ ?úÍ±∞
		if (url.indexOf("?")>-1) {
			String tempUrl = url.split("\\?")[0];
			if (doneUrls.containsKey(tempUrl)) return false;
		}
		
		return true;
	}

}
