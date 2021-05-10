package ver3;

public class Exec_ver3 {
	
	// 실행
	public static void main(String[] args) {
		System.out.println("crawling Start!!!");
		
		// 로직 실행
		ExecThread exec =  new ExecThread();
		exec.start();
		
//		GenerateTree gt = new GenerateTree();
//		gt.createTree("6");
		
		System.out.println("crawling End!!!");
	}

}
