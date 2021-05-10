package atag;

import java.io.BufferedWriter;
import java.io.File;
import java.io.FileWriter;
import java.io.IOException;
import java.util.ArrayList;
import java.util.HashMap;

import model.UrlModel;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;
import org.openqa.selenium.WebElement;
import org.springframework.context.ApplicationContext;
import org.springframework.context.support.ClassPathXmlApplicationContext;

import phantom.Phantom;

public class Crawling {
	
	public ApplicationContext context = new ClassPathXmlApplicationContext("/config/exec_config.xml");
	Phantom phantom = context.getBean("phantom", Phantom.class);
	
	String url = "";
	ArrayList urls = new ArrayList();
	HashMap<String, String> doneUrls = new HashMap<String, String>(); 
	
	// url 세팅
	public void setUrl(String url){
		this.url = url;
		try {
			phantom.setUrlPhantom(url);
		} catch (Exception e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
	}
	
	// depth별 링크 가져오기
	public void getLinks(int depth, String parentUrl){
		ArrayList ar = new ArrayList();
		try {
			ar = phantom.getHref();
		} catch (Exception e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		String targetUrl = phantom.getCurrentUrl();
		
//		this.putDoneUrls(url);
		
		UrlModel model = new UrlModel();
		ArrayList tempUrls = new ArrayList();
		
		for(int i=0; i<ar.size(); i++){
			WebElement element =(WebElement)ar.get(i);
			String tempUrl = element.getAttribute("href");
			// System.out.println(tempUrl);
			tempUrls.add(i, tempUrl);
		}
		
		model.setUrl(url);
		model.setTargetUrl(targetUrl);
		model.setCnt(ar.size());
		model.setLinks(tempUrls);
		model.setDepth(depth);
		model.setParentUrl(parentUrl);
		
		urls.add(model);
	}
	
	
	// doneUrls 에 crwaling url 넣기
	public void putDoneUrls(String url){
		doneUrls.put(url, "done");
	}
	
	public boolean checkUrls(){
		boolean result = true;
		
		for(int i=0; i<urls.size(); i++){
			UrlModel model = (UrlModel)urls.get(i);
			
			ArrayList ar = model.getLinks();
			for(int j=0; j<ar.size(); j++){
				String url = (String)ar.get(j);
				if(wasCrwalingUrl(url)) return false;
			}
		}
		return result;
	}
	
	// 이미 crwaling 한 url 검증
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
		
		this.createFile("data.csv", result);
	}
	
	public void createFile(String filename, String result){
		String realPath = "D:/dhtmlxTree_v403_std/samples/dhtmlxTree/11_json_support/";
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
	
	public void killPhatom(){
		phantom.killPhantom();
	}

}
