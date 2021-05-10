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

import sun.reflect.generics.tree.Tree;

public class Exec {

	private static Crawling c = new Crawling();
	private static String domain = "http://nuli.navercorp.com/";

	public static void main(String[] args) {
		System.out.println("crawling Start!!!");
		// ExecThread exec = new ExecThread();
		// Scraping sc = new Scraping();
		// sc.testPhantomjs();

		int depth = 0;
		c.setUrl(domain);
		c.getLinks(depth, "0");
		c.putDoneUrls(domain);

		// 모든 url이 doneUrls 에 있으면 끝남
		while (checkUrls()) {
			depth++;
			ArrayList tempUrls = (ArrayList) c.urls.clone();
			exec(tempUrls, depth);
		}

		c.printCrawling();
		c.killPhatom();
		System.out.println("crawling End!!!");

	}
	
	public static void printCSV(){
		ArrayList tempUrls = (ArrayList) c.urls.clone();
		for (int i = 0; i < tempUrls.size(); i++) {
			UrlModel model = (UrlModel) tempUrls.get(i);
			ArrayList ar = model.getLinks();
			String parentUrl = model.getUrl();
		}
	}
	
	

	public static void exec(ArrayList tempUrls, int depth) {
		for (int i = 0; i < tempUrls.size(); i++) {
			UrlModel model = (UrlModel) tempUrls.get(i);

			ArrayList ar = model.getLinks();
			String parentUrl = model.getUrl();

			for (int j = 0; j < ar.size(); j++) {
				String url = (String) ar.get(j);

				HashMap<String, String> doneUrls = c.doneUrls;

				// null 제외
				if (url != null && !url.equals(null)) {

					// if(url.lastIndexOf("#") == (url.length()-1)) url =
					// url.substring(0, url.lastIndexOf("#"));
					if (url.lastIndexOf("#") > -1)
						url = url.substring(0, url.lastIndexOf("#"));

					// 같은 domain
					if (url.indexOf(domain) > -1) {
						// 첨부 파일 및 링크 제외
						if (!(url.indexOf(".zip") > -1) && !(url.indexOf(".pdf") > -1) && !(url.indexOf(".png") > -1) && !(url.indexOf(".jpg") > -1) && !(url.indexOf(".gif") > -1) && !(url.indexOf(".exe") > -1)) {
							// 한번 돌았던 url 제외
							if (!doneUrls.containsKey(url)) {
								System.out.println("url ====>>>> " + url);

								c.putDoneUrls(url);
								try {
									c.setUrl(url);
									c.getLinks(depth, parentUrl);
								} catch (Exception e) {
									e.printStackTrace();
									// TODO: handle exception
								}

							}
						}
					}
				}

			}

		}
	}

	public static boolean checkUrls() {

		ArrayList tempUrls = (ArrayList) c.urls.clone();
		for (int i = 0; i < tempUrls.size(); i++) {
			UrlModel model = (UrlModel) tempUrls.get(i);

			ArrayList ar = model.getLinks();
			for (int j = 0; j < ar.size(); j++) {
				String url = (String) ar.get(j);
				HashMap<String, String> doneUrls = c.doneUrls;

				if (url != null && !url.equals(null)) {

					// if(url.lastIndexOf("#") == (url.length()-1)) url =
					// url.substring(0, url.lastIndexOf("#"));
					if (url.lastIndexOf("#") > -1)
						url = url.substring(0, url.lastIndexOf("#"));

					if (url.indexOf(domain) > -1) {
						if (!(url.indexOf(".zip") > -1) && !(url.indexOf(".pdf") > -1) && !(url.indexOf(".png") > -1) && !(url.indexOf(".jpg") > -1) && !(url.indexOf(".gif") > -1) && !(url.indexOf(".exe") > -1)) {
							if (!doneUrls.containsKey(url))
								return true;
						}

					}
				}
			}
		}
		return false;
	}

}
