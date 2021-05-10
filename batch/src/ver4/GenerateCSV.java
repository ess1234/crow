package ver4;

import java.io.BufferedWriter;
import java.io.File;
import java.io.FileWriter;
import java.io.IOException;
import java.util.ArrayList;
import java.util.HashMap;

import utils.Util;

public class GenerateCSV {
	
	public void createCSV(String domainSeq){
		String result = "id,parent,url,link,count,desc"+"\n";
		DataHandler dataHandler = new DataHandler();
		HashMap<String, String> param = new HashMap<String, String>();
		param.put("domainSeq", domainSeq);
		ArrayList ar = (ArrayList)dataHandler.getCrawlingList(param);
		for(int i=0; i<ar.size(); i++){
			HashMap<String, String> data = (HashMap<String, String>) ar.get(i);
			result += String.valueOf(data.get("CRAWLING_SEQ"))+","+String.valueOf(data.get("PARENT_SEQ"))+","+(String.valueOf(data.get("URL"))).replaceFirst("http://", "")
					+","+String.valueOf(data.get("URL"))+","+String.valueOf(data.get("LINK_CNT"))+",desc"+"\n";
		}
		this.createFile(domainSeq+".csv", result);
	}
	
	// scv 파일 만들기
	public void createFile(String filename, String result){
		Util util = new Util();
		String realPath = util.readDeployInfo("FILE_PATH");
		File file = new File(realPath+filename);
		try {
			BufferedWriter out = new BufferedWriter(new FileWriter(file));
			out.write(result);
			out.flush();
			out.close();
		} catch (IOException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
	}

}
