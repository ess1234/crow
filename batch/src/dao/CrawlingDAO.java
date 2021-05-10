package dao;

import java.util.HashMap;
import java.util.List;

import org.apache.ibatis.session.SqlSession;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Component;
import org.springframework.stereotype.Repository;

@Component
public class CrawlingDAO {
	
	@Autowired
    private SqlSession sqlSession;
	
	public void setSqlSession(SqlSession sqlSession) {
	      this.sqlSession = sqlSession;
	}
	
	public List getDomainList(HashMap<String, String> param){
		List<HashMap<String, String>> outputs = sqlSession.selectList("query.getDomainList", param);
		return outputs;
	}
	
	public int updateDomainStatus(HashMap<String, String> param){
		int outputs = sqlSession.update("query.updateDomainStatus", param);
		return outputs;
	}
	
	public int insertCrawlingData(HashMap<String, String> param){
		int outputs = sqlSession.update("query.insertCrawlingData", param);
		return outputs;
	}
	
	public List getCrawlingList(HashMap<String, String> param){
		List<HashMap<String, String>> outputs = sqlSession.selectList("query.getCrawlingList", param);
		return outputs;
	}
	
	public HashMap<String, String> insertUrl(HashMap<String, String> param){
		int outputs = sqlSession.insert("query.insertUrl", param);
		return param;
	}
	
	public List getUrls(HashMap<String, String> param){
		List<HashMap<String, String>> outputs = sqlSession.selectList("query.getUrls", param);
		return outputs;
	}
	
	public int insertSubDomain(HashMap<String, String> param){
		int outputs = sqlSession.insert("query.insertSubDomain", param);
		return outputs;
	}
	
	public int insertDirData(HashMap<String, String> param){
		int outputs = sqlSession.update("query.insertDirData", param);
		return outputs;
	}
	
	public HashMap<String, String> insertNav(HashMap<String, String> param){
		int outputs = sqlSession.update("query.insertNav", param);
		return param;
	}
	
	public int checkCrawling(HashMap<String, String> param){
		int outputs = sqlSession.selectOne("query.checkCrawling", param);
		return outputs;
	}
	

}
