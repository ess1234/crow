package ver3;

import java.util.ArrayList;
import java.util.HashMap;

import org.springframework.context.ApplicationContext;
import org.springframework.context.support.ClassPathXmlApplicationContext;
import org.springframework.scheduling.concurrent.ThreadPoolTaskExecutor;

public class ExecThread {
	
	public ApplicationContext context = new ClassPathXmlApplicationContext("/config/exec_config.xml");
	ThreadPoolTaskExecutor taskExecutor = context.getBean("taskExecutor", ThreadPoolTaskExecutor.class);
	
	public void start(){
		for (;;) {
			int count = taskExecutor.getActiveCount();
			System.out.println("===========>>>>>>>>>>>>>>Active Threads : " + count);
			try {
				Thread.sleep(100000);
			} catch (InterruptedException e) {
				e.printStackTrace();
			}
			if (count < 10) {
				// taskExecutor.shutdown();
				// break;
				
				this.crawling();
			}
		}
	}
	
	public void crawling(){
		
		HashMap<String, String> param = new HashMap<String, String>();
		param.put("crawlingState", "WAIT");
		DataHandler dataHandler = new DataHandler();
		ArrayList domainList = (ArrayList)dataHandler.getDomainList(param);
		
		for(int i=0; i<domainList.size(); i++){
			HashMap<String, String> domain = (HashMap<String, String>)domainList.get(i);
			
			try {
				// 입력받은 도메인 thread 처리
				taskExecutor.execute(new Controller(domain.get("DOMAIN"), String.valueOf(domain.get("DOMAIN_SEQ"))));				
			} catch (Exception e) {
				// TODO: handle exception
				System.out.println("thread exception!!!!!!");
				e.printStackTrace();
			}
			
			// 진행중인 url 상태 변경
			new ChangeState().goingCrawling(String.valueOf(domain.get("DOMAIN_SEQ")));
		}
	}

}
