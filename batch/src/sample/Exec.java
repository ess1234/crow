package sample;

import utils.Util;


public class Exec {
	
	private static String domain = "";

	public static void main(String[] args) {
		System.out.println("crawling Start!!!");
		setDomain();
		ExecThread exec = new ExecThread(domain);
		// Scraping sc = new Scraping();
		// sc.testPhantomjs();
		System.out.println("crawling End!!!");
	}
	
	public static void setDomain(){
		Util util = new Util();
		domain = util.readDeployInfo("DOMAIN");
	}
}
