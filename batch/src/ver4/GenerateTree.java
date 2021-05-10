package ver4;

import java.util.ArrayList;
import java.util.HashMap;

public class GenerateTree {
	DataHandler dataHandler;
	HashMap<String, String> tempUrls = new HashMap<String, String>();
	String domainSeq = "";
	int idx = 1;
	
	public GenerateTree(DataHandler dataHandler){
		this.dataHandler = dataHandler;
	}

	// tree 만들기
	public void createTree(String domainSeq) {		
		this.domainSeq = domainSeq;
		HashMap<String, String> param = new HashMap<String, String>();
		param.put("domainSeq", domainSeq);
		ArrayList urls = (ArrayList) dataHandler.getUrls(param);

		// 순서 유의해야함.
		for (int i = 0; i < urls.size(); i++) {
			HashMap<String, String> data = (HashMap<String, String>) urls.get(i);

			// url
			String url = data.get("URL");

			// url temp save
			tempUrls.put(url, idx + "");
			idx++;

			// root
			if (i == 0) {
				// System.out.println(url+" key :: "+tempUrls.get(url)+" ===parent===>>> "+url+" key :: "+tempUrls.get(url));
				data.put("crawlingSeq", tempUrls.get(url));
				data.put("domainSeq", domainSeq);
				data.put("parentSeq", "0");
				data.put("url", url);
				dataHandler.insertDirData(data);
				continue;
			}
			
			// 부모 만들기
			this.makeMyParent(url);
		}
		
		Object[] urlNames = tempUrls.keySet().toArray();
		for(int i=0; i<urlNames.length; i++){
			// System.out.println(urlNames[i] +" :: "+tempUrls.get(urlNames[i]));
			this.insertCrawlingData(urlNames[i].toString());
		}
	}
		
	public void makeMyParent(String url){
		String parentUrl = url.substring(0, url.lastIndexOf("/"));
		do {
			if (tempUrls.containsKey(parentUrl)) {
				break;
			} else {
				tempUrls.put(parentUrl, idx + "");
				idx++;
				parentUrl = parentUrl.substring(0,	parentUrl.lastIndexOf("/"));
			}
		} while (parentUrl.indexOf("/") > -1);
	}
	
	public void insertCrawlingData(String url){
		String parentUrl = url.substring(0, url.lastIndexOf("/"));
		HashMap<String, String> data = new HashMap<String, String>();
		// System.out.println(url+" key :: "+tempUrls.get(url)+" ===parent===>>> "+parentUrl+" key :: "+tempUrls.get(parentUrl));
		
		data.put("crawlingSeq", tempUrls.get(url));
		data.put("domainSeq", this.domainSeq);
		data.put("parentSeq", tempUrls.get(parentUrl));
		data.put("url", url);
		if(tempUrls.get(parentUrl)!=null)  this.dataHandler.insertDirData(data);
	}

}
