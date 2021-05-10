package ver2;

public class Exec {
	
	
	// 실행
	public static void main(String[] args) {
		System.out.println("crawling Start!!!");
		
		// 로직 실행
		ExecThread exec =  new ExecThread();
		exec.start();
		
		System.out.println("crawling End!!!");
	}

}
