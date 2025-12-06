import java.io.BufferedReader;
import java.io.File;
import java.io.FileInputStream;
import java.io.InputStreamReader;
import java.io.OutputStream;
import java.io.PrintWriter;
import java.net.ServerSocket;
import java.net.Socket;
import java.nio.file.Files;
import java.util.concurrent.ExecutorService;
import java.util.concurrent.Executors;

public class server{
    private File raiz = new File("./view");
    private ServerSocket serv;
    private ExecutorService pool = Executors.newFixedThreadPool(8);
    public static void main(String[] args)  {
        new Thread(() -> new server().start(8080)).start();
    }
    public void start(int port) {
        try {
            serv = new ServerSocket(port);
            System.out.println("http://localhost:" + port);
            
            while (true) {
                Socket client = serv.accept();
                pool.submit(() -> hend(client));
            }
            
        } catch (Exception e) {
            e.printStackTrace();
        }
    }
    public void hend(Socket serv){
    BufferedReader in;
    OutputStream out;
    PrintWriter printWriter;
    FileInputStream fileInputStream;
    String requestLine;
    try{
        in = new BufferedReader(new InputStreamReader(serv.getInputStream()));
        out = serv.getOutputStream();
        printWriter = new PrintWriter(out, true);
        requestLine = in.readLine();

        if (requestLine==null) return;
        String path = requestLine.split(" ")[1];
        if (path.equals("/")) path = "/index.html";

        File file = new File(raiz,path);
        if (!file.getCanonicalPath().startsWith(raiz.getCanonicalPath())) {
                send404(printWriter);
                return;
            }
            if (!file.exists() || file.isDirectory()) {
                send404(printWriter);
                return;
            }
            String mini = Files.probeContentType(file.toPath());
            StringBuilder type = new StringBuilder().append("Content-Type: ").append(mini!=null?mini:"application/octet-stream");
            
            printWriter.println("HTTP/1.1 200 OK");
            printWriter.println(type.toString());
            printWriter.println("Content-Length: " + file.length());
            printWriter.println();
            printWriter.flush();
            
            fileInputStream = new FileInputStream(file);
            fileInputStream.transferTo(out);
            out.flush();
        }catch(Exception e){
            e.printStackTrace();
        }finally {
            try {
                serv.close();
            }catch(Exception e){

            }
        }
    }
    private void send404(PrintWriter writer) {
        String page = "<h1>404 Not Found</h1>";
        writer.println("HTTP/1.1 404 Not Found");
        writer.println("Content-Type: text/html");
        writer.println("Content-Length: " + page.length());
        writer.println();
        writer.println(page);
        writer.flush();
    }
}