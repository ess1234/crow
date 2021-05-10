package ver3;

import java.util.ArrayList;
import java.util.HashMap;

public class GenerateTree {

	// tree 만들기
	public void createTree(String domainSeq) {

		DataHandler dataHandler = new DataHandler();
		HashMap<String, String> param = new HashMap<String, String>();
		param.put("domainSeq", domainSeq);
		ArrayList urls = (ArrayList) dataHandler.getUrls(param);
		HashMap<String, String> tempUrls = new HashMap<String, String>();

		// 순서 유의해야함.
		for (int i = 0; i < urls.size(); i++) {
			HashMap<String, String> data = (HashMap<String, String>) urls.get(i);

			// url
			String url = data.get("URL");

			// url temp save
			tempUrls.put(url, (i + 1) + "");

			// root
			if (i == 0) {
				// System.out.println(url+" key :: "+tempUrls.get(url)+" ===parent===>>> "+url+" key :: "+tempUrls.get(url));
				data.put("crawlingSeq", tempUrls.get(url));
				data.put("domainSeq", domainSeq);
				data.put("parentSeq", "0");
				data.put("url", url);
				data.put("linkCnt", String.valueOf(data.get("LINK_CNT")));
				data.put("status", String.valueOf(data.get("STATUS")));
				dataHandler.insertCrawlingData(data);
				continue;
			}

			// 부모
			String parentUrl = url.substring(0, url.lastIndexOf("/"));

			do {
				// parent가 있는지 체크
				if (tempUrls.containsKey(parentUrl)) {
					break;
				} else {
					parentUrl = parentUrl.substring(0,	parentUrl.lastIndexOf("/"));
				}
			} while (parentUrl.indexOf("/") > -1);

			// System.out.println(url+" key :: "+tempUrls.get(url)+" ===parent===>>> "+parentUrl+" key :: "+tempUrls.get(parentUrl));
			data.put("crawlingSeq", tempUrls.get(url));
			data.put("domainSeq", domainSeq);
			data.put("parentSeq", tempUrls.get(parentUrl));
			data.put("url", url);
			data.put("linkCnt", String.valueOf(data.get("LINK_CNT")));
			data.put("status", String.valueOf(data.get("STATUS")));

			try {
				dataHandler.insertCrawlingData(data);
			} catch (Exception e) {
				// TODO: handle exception
				e.printStackTrace();
				continue;
			}

		}
	}

}
