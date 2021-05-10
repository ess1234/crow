package model;

import java.util.ArrayList;

public class UrlModel {
	
	private String url = "";
	private String targetUrl = "";
	private int depth = 0;
	private int cnt = 0;
	private String parentUrl = "";
	private ArrayList links = new ArrayList();
	
	public String getUrl() {
		return url;
	}
	public void setUrl(String url) {
		this.url = url;
	}
	public String getTargetUrl() {
		return targetUrl;
	}
	public void setTargetUrl(String targetUrl) {
		this.targetUrl = targetUrl;
	}
	public int getDepth() {
		return depth;
	}
	public void setDepth(int depth) {
		this.depth = depth;
	}
	public int getCnt() {
		return cnt;
	}
	public void setCnt(int cnt) {
		this.cnt = cnt;
	}
	public String getParentUrl() {
		return parentUrl;
	}
	public void setParentUrl(String parentUrl) {
		this.parentUrl = parentUrl;
	}
	public ArrayList getLinks() {
		return links;
	}
	public void setLinks(ArrayList links) {
		this.links = links;
	}
	
	@Override
	public String toString() {
		return "UrlModel [url=" + url + ", targetUrl=" + targetUrl + ", depth=" + depth + ", cnt=" + cnt + ", parentUrl=" + parentUrl + ", links=" + links + "]";
	}
	
}
