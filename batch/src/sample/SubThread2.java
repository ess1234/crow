package sample;

public class SubThread2 implements Runnable {
	
	String str = "";
	
	public SubThread2(String str){
		this.str = str;
	}
	
	public void run() {
		
		for(int i=0; i<30; i++){
			System.out.println(this.str+" :: "+i + " :: run!!");
			try {
				Thread.sleep(1000);
			} catch (InterruptedException e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
			}
		}
	}
}