package ver4;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.regex.Matcher;
import java.util.regex.Pattern;

import org.openqa.selenium.By;
import org.openqa.selenium.WebElement;

import phantom.Phantom;

public class GenerateSiteMap {

	public ArrayList makeNav(List<WebElement> navs) {

		ArrayList siteMap = new ArrayList();

		for (int i = 0; i < navs.size(); i++) {
			WebElement nav = (WebElement) navs.get(i);
			String navPath = this.findAncestor(nav);

			ArrayList navLinks = (ArrayList) nav.findElements(By.tagName("a"));
			for (int j = 0; j < navLinks.size(); j++) {
				WebElement element = (WebElement) navLinks.get(i);
				String url = element.getAttribute("href");
				System.out.println("URL :: " + url);

				String[] data = { navPath, url };
				siteMap.add(data);
			}
		}
		return siteMap;
	}

	public void getNavPath(WebElement nav) {
		ArrayList navLinks = (ArrayList) nav.findElements(By.tagName("a"));
		for (int j = 0; j < navLinks.size(); j++) {
			WebElement element = (WebElement) navLinks.get(j);
			String url = element.getAttribute("href");
			System.out.println("URL :: " + url);
		}
	}

	public String findAncestor(WebElement tag) {
		String tempTag = "";
		WebElement parent = tag;
		String path = "";
		do {
			parent = parent.findElement(By.xpath(".."));
			tempTag = parent.getTagName();
			path += tempTag + ",";
		} while (!tempTag.equals("body"));

		// 역정렬
		String[] paths = path.split(",");
		path = "";
		for (int i = paths.length; i > 0; i--) {
			path += paths[i - 1] + " > ";
		}

		if (!tag.getTagName().equals("a")) {
			path += tag.getTagName();
		} else {
			path = path.substring(0, path.lastIndexOf(">"));
		}

		return path.trim();
	}

	public String getNavTitle(String navPath) {

		String title = "Site Navigation";

		if (navPath != null) {

			// 1. main 과 article 모두 있는 경우
			if (navPath.indexOf("article") > -1 && navPath.indexOf("article") > -1) {
				// System.out.println("아티클이 있는 경우");

				// 2. main 과 article 순서 체크
				if (navPath.indexOf("main") > navPath.indexOf("article")) return "Main Content Navigation";
					// System.out.println("메인이 가까이 있는 경우");
				if (navPath.indexOf("main") < navPath.indexOf("article")) return "Article Navigation";
					// System.out.println("아티클이 가까이 있는 경우");
			} else {

				// 3. main 있는경우
				if (navPath.indexOf("main") > -1) return "Main Content Navigation";
					// System.out.println("메인이 있는 경우");
				// 3. article만 있는 경우
				if (navPath.indexOf("article") > -1) return "Article Navigation";
					// System.out.println("아티클이 있는 경우");

				// 4. hearder 만 있는경우
				if (navPath.indexOf("header") > -1) return "Header Navigation";
					// System.out.println("해더만 있는 경우");
				// 4.footer만 있는 경우
				if (navPath.indexOf("footer") > -1) return "Footer Navigation";
					// System.out.println("풋터만 있는 경우");
			}
		}
		// 나머지 모두
		// System.out.println("메인이 있는 경우");
		return title;
	}

	public String removeTag(String str) {

		Matcher mat;
		Pattern imgTag = Pattern
				.compile("<img[^>]*alt=[\"]?([^>\"]+)[\"']?[^>]*>");
		mat = imgTag.matcher(str);
		str = mat.replaceAll("$1");

		Pattern imgEndTag = Pattern.compile("</img>");
		mat = imgEndTag.matcher(str);
		str = mat.replaceAll(" ");

		Pattern tag = Pattern.compile("<(\"[^\"]*\"|\'[^\']*\'|[^\'\">])*>");
		mat = tag.matcher(str);
		str = mat.replaceAll(" ");

		// ntag 처리
		Pattern ntag = Pattern.compile("<\\w+\\s+[^<]*\\s*>");
		mat = ntag.matcher(str);
		str = mat.replaceAll(" ");

		return str;
	}

	public String removeTag_(String str) {
		Matcher mat;
		// script 처리
		Pattern script = Pattern.compile("<(no)?script[^>]*>.*?</(no)?script>",
				Pattern.DOTALL);
		mat = script.matcher(str);
		str = mat.replaceAll("");

		// style 처리
		Pattern style = Pattern.compile("<style[^>]*>.*</style>",
				Pattern.DOTALL);
		mat = style.matcher(str);
		str = mat.replaceAll("");

		// tag 처리
		Pattern tag = Pattern.compile("<(\"[^\"]*\"|\'[^\']*\'|[^\'\">])*>");
		mat = tag.matcher(str);
		str = mat.replaceAll("");

		// ntag 처리
		Pattern ntag = Pattern.compile("<\\w+\\s+[^<]*\\s*>");
		mat = ntag.matcher(str);
		str = mat.replaceAll("");

		// entity ref 처리
		Pattern Eentity = Pattern.compile("&[^;]+;");
		mat = Eentity.matcher(str);
		str = mat.replaceAll("");

		// whitespace 처리
		Pattern wspace = Pattern.compile("\\s\\s+");
		mat = wspace.matcher(str);
		str = mat.replaceAll("");

		return str;
	}

}
