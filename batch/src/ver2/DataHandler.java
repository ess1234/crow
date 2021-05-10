package ver2;

import java.util.HashMap;
import java.util.List;

import org.springframework.context.ApplicationContext;
import org.springframework.context.support.ClassPathXmlApplicationContext;

import dao.CrawlingDAO;

public class DataHandler {
	
	ApplicationContext context = new ClassPathXmlApplicationContext("/config/db_config.xml");
	CrawlingDAO dao = context.getBean("dao", CrawlingDAO.class);
	
	public List getDomainList(HashMap<String, String> param){
		return dao.getDomainList(param);
	}
	
	public int updateDomainStatus(HashMap<String, String> param){
		return dao.updateDomainStatus(param);
	}
	
	public int insertCrawlingData(HashMap<String, String> param){
		return dao.insertCrawlingData(param);
	}
	
	public List getCrawlingList(HashMap<String, String> param){
		return dao.getCrawlingList(param);
	}
}
