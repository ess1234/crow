package phantom;

import java.util.ArrayList;
import java.util.List;
import java.util.concurrent.TimeUnit;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.phantomjs.PhantomJSDriver;
import org.openqa.selenium.phantomjs.PhantomJSDriverService;
import org.openqa.selenium.remote.DesiredCapabilities;

import utils.Util;


public class Phantom {
	
	PhantomJSDriver driver;
	
	public Phantom (){
		
		Util util = new Util();
		String phantomPath = util.readDeployInfo("PHANTOM_PATH");
		
		DesiredCapabilities capabilities = DesiredCapabilities.phantomjs();
		capabilities.setCapability(PhantomJSDriverService.PHANTOMJS_EXECUTABLE_PATH_PROPERTY, phantomPath);
		
		capabilities.setJavascriptEnabled(false);
		this.driver = new PhantomJSDriver(capabilities);
		
	}
	
	public void close(){
		driver.close();
	}
	
	public PhantomJSDriver getInstance(){
		return this.driver;
	}
	
	public void killPhantom(){
		driver.quit();
	}
	
	public List<WebElement> getSrcInNav(){
		List<WebElement> navs = driver.findElements(By.tagName("nav"));
		return navs;
	}
	
	public void setUrlPhantom(String url) throws Exception {
		driver.get(url);
		driver.manage().timeouts().implicitlyWait(30, TimeUnit.SECONDS);
		driver.manage().timeouts().pageLoadTimeout(50, TimeUnit.SECONDS);
	}
	
	public String getCurrentUrl(){
		String url = driver.getCurrentUrl();
		return url;
	}
	
	public String getTitle(){
		String title = driver.getTitle();
		return title;
	}
	
	public ArrayList getHref()  throws Exception {
		ArrayList ar = (ArrayList)driver.findElementsByTagName("a");
		return ar;
	}
	
	public void getTagsCnt(String tag){
		System.out.println("tag 갯수 : "+driver.findElementByTagName(tag));
	}
	
	public void getJS(){
		System.out.println("tag 갯수 : ");
	}
	
	public String getPageSource(){
		return driver.getPageSource();
	}
}
