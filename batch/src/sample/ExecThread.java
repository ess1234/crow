package sample;

import java.util.ArrayList;
import java.util.HashMap;

import org.springframework.context.ApplicationContext;
import org.springframework.context.support.ClassPathXmlApplicationContext;
import org.springframework.scheduling.concurrent.ThreadPoolTaskExecutor;
import org.springframework.stereotype.Component;

import phantom.Phantom;

@Component
public class ExecThread extends Common {
	
	public ApplicationContext context = new ClassPathXmlApplicationContext("/config/exec_config.xml");
	ThreadPoolTaskExecutor taskExecutor = context.getBean("taskExecutor", ThreadPoolTaskExecutor.class);
	Phantom phantom = context.getBean("phantom", Phantom.class);
	
	ArrayList urls = new ArrayList();
	HashMap<String, String> urlMap = new HashMap<String, String>();
		
	public ExecThread(String domain){
		
//		taskExecutor.execute(new ThreadURL());
//		taskExecutor.execute(new ThreadJS());
//		taskExecutor.execute(new ThreadCSS());
//		taskExecutor.execute(new ThreadIMG());
		
		Crawling c = new Crawling(phantom);
		int depth = 0;
		c.setDomain(domain);
		c.setUrl(domain);
		c.getLinks(depth);
		c.putDoneUrls(domain);
		
		HashMap<String, String> param = new HashMap<String, String>();
		ArrayList ar = (ArrayList) c.getDomainList(param);
		
		for(int i=0; i<ar.size(); i++){
			HashMap<String, String> data = (HashMap<String, String>)ar.get(i);
			System.out.println(data.toString());
		}

		// targetUrls ëª¨ë‘ ë¹„ìš°ë©???
		while(!targetUrls.empty() && false){
			String url = targetUrls.pop();
			
			// # ?¤ì— ?œì™¸
			if (url.lastIndexOf("#") > -1) url = url.substring(0, url.lastIndexOf("#"));
			// / ?¤ì— ?œì™¸
			if (url.lastIndexOf("/") > -1 && url.lastIndexOf("/") == url.length()-1)  url = url.substring(0, url.lastIndexOf("/"));
			
			if(c.checkUrl(url)) {
				
				try {
					Thread.sleep(1000);
				} catch (InterruptedException e) {
					e.printStackTrace();
				}
				
				try{
					c.putDoneUrls(url);
					c.setDomain(domain);
					if(!c.setUrl(url))  continue;
					c.getLinks(++depth);
				} catch(Exception e) {
					e.printStackTrace();
					System.out.println("=============error==========="+url); 
					continue;
				}
				
			}
		}

		// c.printCrawling();
		
		for (;;) {
			int count = taskExecutor.getActiveCount();
			System.out.println("===========>>>>>>>>>>>>>>Active Threads : " + count);
			try {
				Thread.sleep(10000);
			} catch (InterruptedException e) {
				e.printStackTrace();
			}
			if (count == 0) {
				phantom.killPhantom();
				taskExecutor.shutdown();
				break;
			}
		}
	}
	
	
}
