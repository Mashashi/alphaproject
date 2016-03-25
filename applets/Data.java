import java.applet.Applet;
import java.awt.Cursor;
import java.awt.Graphics;
import java.awt.Image;
import java.awt.event.MouseEvent;
import java.awt.event.MouseListener;
import java.awt.event.MouseMotionListener;

public class Data extends Applet implements Runnable, MouseListener, MouseMotionListener {
    
    
	/**
     * 
     */
	private  static final long serialVersionUID = 1L;
	
	/**
     * 
     */
	private  Cursor point;
    
	/**
     * 
     */
    private  Image imagem;
    
    /**
     * 
     */
    private  int height;
    
    /**
     * 
     */
    private  boolean threadControl=true;
    
    /**
     * 
     */
    private  boolean threadStart=true;
    
    /**
     * 
     */
    private Thread scrollThread;
    
    /**
     * 
     */
    private int heigthIni;
    
    /**
     * 
     */
    private StringBuffer buffer;

    /**
     * 
     */
    private Image offscreen;
    
    /**
     * 
     */
    private Graphics bufferi;
    
    
    /**
     * 
     */
    public void init(){
        
        imagem = getImage(getCodeBase(), getParameter("imagem"));
        
        offscreen = createImage(600,200);

        bufferi = offscreen.getGraphics();
        
        point = new Cursor(12); 
    	
        setCursor(point);
        
        heigthIni = 0;
        
        height = 15;
        
        buffer = new StringBuffer();
        
        scrollThread = new Thread(this);
        
        addItem(getParameter("recente"));
        
        addMouseListener(this);
        
        addMouseMotionListener(this);
        
        scrollThread.start();
            
    }
    
    
    
    /**
     * 
     */
    public void start(){
    	
    	showStatus("");
    	
    }
    
    
    /**
     * 
     */
    public void stop(){}
    
    
    
    /**
     * 
     */
    public void destroy(){}
    
    
    
    /**
     * 
     */
    public void addItem(String newWord){
        
        buffer.append(newWord).append("\r\n");
        
        repaint();
        
    }
    
    
   public void update( Graphics g ) {
		
		paint(g);

	}
    
    
    
    /**
     * 
     */
    public void paint(Graphics g){
    	
    	bufferi.clearRect(0,0,600,200);

        bufferi.drawImage(imagem,0,0,this);
        
        bufferi.drawString(buffer.toString(), 5, height);
        
        g.drawImage(offscreen,0,0,this);
    	
    }
    
    
    /**
     * 
     */
    @Override
    public void run() {

        
        while(threadControl){
        
            
            if(threadStart){

                try{

                    Thread.sleep(100);

                }catch(InterruptedException e){}
        
                if (this.height>-1)
                    
                    this.height--;
                
                else 
                    
                    this.height=getHeight()+15;
                
                repaint();
                
                
                
            }   
            

            //if((!threadStart)&&(apontExp==true)){
                
                //apontExp = false;
                
            //}
    
        }
    
    }
    
    @Override
    public void mouseEntered(MouseEvent arg0) {
        
        threadStart =  false;
        
    }

    @Override
    public void mouseExited(MouseEvent arg0) {
        
        threadStart =  true;
    
    }

    @Override
    public void mousePressed(MouseEvent arg0) {
            
            heigthIni = height-arg0.getY();
            
    }
    
    @Override
    public void mouseReleased(MouseEvent arg0) {}
    @Override
    public void mouseClicked(MouseEvent arg0) {}
    @Override
    public void mouseMoved(MouseEvent arg0) {}
    
    @Override
    public void mouseDragged(MouseEvent arg0) {
        
        height = arg0.getY()+heigthIni;
        
        
        while (height<-1){
            
            height += getHeight()+15;
            
        }
        
        while (height>getHeight()+15){
            
            height -= getHeight()+15;
            
        }
    
        repaint();
        
    }
    
}
