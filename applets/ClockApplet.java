import java.applet.*;
import java.awt.*;
import java.util.*;

	public class ClockApplet extends Applet implements Runnable{
		
		/**
		 * 
		 */
		private static final long serialVersionUID = 1L;
		
		private Calendar cal;
		private String hour;
		private String minute;
		private String second;
		private String dia;
		private String mes;
		private String ano;
		private Font clockFaceFont; 
		private Cursor point;
		
		/*Resolver Flickering*/
		private Graphics buffer;
		private Image offScreen;
		
		
	Thread t,t1;
	  public void start(){
		  offScreen = createImage(600,200);
	      buffer = offScreen.getGraphics();
		  //newline = System.getProperty("line.separator");
		  clockFaceFont = new Font("Verdana", Font.PLAIN, 11);
		  point = new Cursor(12); 
		  this.setBackground(new Color(251, 249, 244) );
		  t = new Thread(this);
		  setCursor(point);
		  t.start();
		  
	  }
	  
	  public void run(){
	    t1 = Thread.currentThread();
	    while(t1 == t){
	    	  cal  = new GregorianCalendar();
			  hour = String.valueOf(cal.get(Calendar.HOUR_OF_DAY));
			  minute = String.valueOf(cal.get(Calendar.MINUTE));
			  second = String.valueOf(cal.get(Calendar.SECOND));
			  byte mesn= (byte) cal.get(Calendar.MONTH);
			  mes = String.valueOf(cal.get(Calendar.MONTH));
			  dia = String.valueOf(cal.get(Calendar.DAY_OF_MONTH));
			  ano = String.valueOf(cal.get(Calendar.YEAR));
			  
			  
			  if(second.length() == 1)
				  second = "0"+second;
			  
			  if(minute.length() == 1)
				  minute = "0"+minute;
			  
			  if(hour.length() == 1)
				  hour = "0"+hour;
				  
			  switch(mesn){
			  
			  case 0: mes = "Janeiro";
			  break;
			  case 1: mes = "Fevereiro";
			  break;
			  case 2: mes = "Março";
			  break;
			  case 3: mes = "Abril";
			  break;
			  case 4: mes = "Maio";
			  break;
			  case 5: mes = "Junho";
			  break;
			  case 6: mes = "Julho";
			  break;
			  case 7: mes = "Agosto";
			  break;
			  case 8: mes = "Setembro";
			  break;
			  case 9: mes = "Outubro";
			  break;
			  case 10: mes = "Novembro";
			  break;
			  case 11: mes = "Dezembro";
			  break;
			  
			  }
			  
			  
			  
			  repaint();
	      try{
	        
	    	  Thread.sleep(1000);  
	    	  
	      }catch(InterruptedException e){}
	    }
	  }

	  public void paint(Graphics g){
		  
		  buffer.clearRect(0,0,4000,4000);
	      
	      buffer.setFont(clockFaceFont);
		  
	      buffer.drawLine(0, 0, getSize().width-1, 0);
	    
	      buffer.drawLine(0, getSize().height-1, getSize().width, getSize().height-1);
	    
	      buffer.drawLine(0, 0, 0, getSize().height-1);
	    
	      buffer.drawLine(getSize().width-1, 0, getSize().width-1, getSize().height-1);
	    
	      buffer.drawString(
				  dia +" de " + 
				  mes+", "
				  + ano, 10, 20 ); 
	      
	      buffer.drawString( hour + ":" + minute + ":" + second, 10, 35 ); 

				  
	      g.drawImage(offScreen,0,0,this);
	      
	  }
	  
	  
	  public void update(Graphics g){
		  
		  paint(g);
		  
	  }
	}
	
