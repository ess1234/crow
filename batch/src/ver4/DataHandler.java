package ver4;

import java.util.HashMap;
import java.util.List;

import org.apache.ibatis.session.SqlSession;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.context.ApplicationContext;
import org.springframework.context.support.ClassPathXmlApplicationContext;
import org.springframework.stereotype.Component;

import dao.CrawlingDAO;

public class DataHandler {
	
	ApplicationContext context = new ClassPathXmlApplicationContext("/config/db_config.xml");
	CrawlingDAO crawlingDAO = context.getBean("crawlingDAO", CrawlingDAO.class);
	
	
	public List getDomainList(HashMap<String, String> param){
		return crawlingDAO.getDomainList(param);
	}
	
	public int updateDomainStatus(HashMap<String, String> param){
		return crawlingDAO.updateDomainStatus(param);
	}
	
	public int insertCrawlingData(HashMap<String, String> param){
		return crawlingDAO.insertCrawlingData(param);
	}
	
	public List getCrawlingList(HashMap<String, String> param){
		return crawlingDAO.getCrawlingList(param);
	}
	
	public HashMap<String, String> insertUrl(HashMap<String, String> param){
		return crawlingDAO.insertUrl(param);
	}
	
	public List getUrls(HashMap<String, String> param){
		return crawlingDAO.getUrls(param);
	}
	
	public int insertSubDomain(HashMap<String, String> param){
		return crawlingDAO.insertSubDomain(param);
	}
	
	public int insertDirData(HashMap<String, String> param){
		return crawlingDAO.insertDirData(param);
	}
	
	public HashMap<String, String> insertNav(HashMap<String, String> param){
		return crawlingDAO.insertNav(param);
	}
	
	public int checkCrawling(HashMap<String, String> param){
		return crawlingDAO.checkCrawling(param);
	}
	
}
