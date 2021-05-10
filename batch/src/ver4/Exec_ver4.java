package ver4;

import java.util.regex.Matcher;
import java.util.regex.Pattern;

public class Exec_ver4 {
	
	// 실행
	public static void main(String[] args) {
		System.out.println("crawling Start!!!");
		
		// 로직 실행
		ExecThread exec =  new ExecThread();
		exec.start();
		
		/*
		 * 수동 개별 작업
		 * 
		// 트리 만들기
		ExecThread exec =  new ExecThread();
		exec.startTree("9");
		
		// 수동 메뉴 만들기
		Controller controller = new Controller("http://www.smashingmagazine.com", "9");
		try {
			controller.generateSiteMap();
		} catch (Exception e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		} finally{
			controller.phantom.killPhantom();
		}
		*/
		
		System.out.println("crawling End!!!");
	}

}
