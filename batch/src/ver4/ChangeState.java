package ver4;

import java.util.HashMap;

public class ChangeState {
	
	DataHandler dataHandler;
	
	public ChangeState(DataHandler dataHandler){
		this.dataHandler = dataHandler;
	}

	// crawling 완료 상태 변경
	public void goingCrawling(String domainSeq) {
		// 진행중인 url 상태 변경
		HashMap<String, String> param = new HashMap<String, String>();
		param.put("domainSeq", String.valueOf(domainSeq));
		param.put("crawlingState", "ING");
		int result = dataHandler.updateDomainStatus(param);
	}

	// crawling 완료 상태 변경
	public void completeCrawling(String domainSeq) {
		// 진행중인 url 상태 변경
		HashMap<String, String> param = new HashMap<String, String>();
		param.put("domainSeq", String.valueOf(domainSeq));
		param.put("crawlingState", "END");
		int result = dataHandler.updateDomainStatus(param);
	}
	
	// crawling 완료 상태 변경
	public void failCrawling(String domainSeq) {
		// 진행중인 url 상태 변경
		HashMap<String, String> param = new HashMap<String, String>();
		param.put("domainSeq", String.valueOf(domainSeq));
		param.put("crawlingState", "FAIL");
		int result = dataHandler.updateDomainStatus(param);
	}

}
