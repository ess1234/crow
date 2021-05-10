package ver3;

import java.util.HashMap;

public class ChangeState {

	// crawling 완료 상태 변경
	public void goingCrawling(String domainSeq) {
		DataHandler dataHandler = new DataHandler();
		// 진행중인 url 상태 변경
		HashMap<String, String> param = new HashMap<String, String>();
		param.put("domainSeq", String.valueOf(domainSeq));
		param.put("crawlingState", "ING");
		int result = dataHandler.updateDomainStatus(param);
	}

	// crawling 완료 상태 변경
	public void completeCrawling(String domainSeq) {
		DataHandler dataHandler = new DataHandler();
		// 진행중인 url 상태 변경
		HashMap<String, String> param = new HashMap<String, String>();
		param.put("domainSeq", String.valueOf(domainSeq));
		param.put("crawlingState", "END");
		int result = dataHandler.updateDomainStatus(param);
	}

}
