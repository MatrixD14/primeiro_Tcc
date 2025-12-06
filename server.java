import java.io.IOException;
import java.net.InetSocketAddress;
import java.nio.ByteBuffer;
import java.nio.channels.AsynchronousServerSocketChannel;
import java.nio.channels.AsynchronousSocketChannel;
import java.nio.channels.CompletionHandler;
import java.nio.channels.FileChannel;
import java.nio.charset.StandardCharsets;
import java.nio.file.Files;
import java.nio.file.Path;
import java.nio.file.Paths;

public class server{
    private final Path root = Paths.get("view");
    public static void main(String[] args)  {
        new Thread(() -> new server().start(8080)).start();
        // new server().start(8080);
    }
    public void start(int port) {
        try {
            AsynchronousServerSocketChannel serv =
                AsynchronousServerSocketChannel.open()
                .bind(new InetSocketAddress(port));

            System.out.println("http://localhost:" + port);
            
           serv.accept(null, new CompletionHandler<AsynchronousSocketChannel,Void>() {

            @Override
            public void completed(AsynchronousSocketChannel client, Void att) {
                serv.accept(null, this);
                handle(client);
            }

            @Override
            public void failed(Throwable exc, Void att) {
                exc.printStackTrace();
            }
        });

        Thread.currentThread().join();
}catch(Exception e){
e.printStackTrace();
}
}


private void handle(AsynchronousSocketChannel client) {
    ByteBuffer buffer = ByteBuffer.allocate(2048);

        client.read(buffer, null, new CompletionHandler<Integer,Void>() {
            @Override
            public void completed(Integer result, Void att) {
                       try {
                    if (result == -1) {
                        close(client);
                        return;
                    }

                    buffer.flip();
                    String req = StandardCharsets.UTF_8.decode(buffer).toString();
                    buffer.clear();

                    String path = parsePath(req);
                    boolean keepAlive = req.toLowerCase().contains("connection: keep-alive");

                    Path file = root.resolve(path).normalize();

                    if (!file.startsWith(root) || !Files.exists(file)) {
                        send404(client, keepAlive);
                        return;
                    }

                    sendFile(client, file, keepAlive);
                } catch (Exception e) {
                    e.printStackTrace();
                    close(client);
                }
            }

            @Override
            public void failed(Throwable exc, Void att) {
                close(client);
            }
        });
    }

    private String parsePath(String req) {
        if (req == null) return "/index.html";
        String[] parts = req.split(" ");
        if (parts.length < 2) return "/index.html";
        if (parts[1].equals("/")) return "index.html";
        return parts[1].substring(1);
    }

    private void sendFile(AsynchronousSocketChannel client, Path file, boolean keepAlive) {
        try {
            String mime = Files.probeContentType(file);
            if (mime == null) mime = "application/octet-stream";

            long size = Files.size(file);

            StringBuilder header = new StringBuilder()
                    .append("HTTP/1.1 200 OK\r\n")
                            .append("Content-Type: ").append(mime).append("\r\n")
                            .append("Content-Length: ").append(size).append("\r\n")
                            .append((keepAlive ? "Connection: keep-alive\r\n" : "Connection: close\r\n"))
                            .append("\r\n");

            ByteBuffer head = ByteBuffer.wrap(header.toString().getBytes());

            client.write(head).get();

            FileChannel fc = FileChannel.open(file);
            ByteBuffer buffer = ByteBuffer.allocate(8192);

            sendChunk(client, fc, buffer);

            if (keepAlive) handle(client);
            else close(client);
            
        } catch (Exception e) {
            close(client);
        }
    }

private void sendChunk(AsynchronousSocketChannel client, FileChannel fc, ByteBuffer buffer) {
    try {
        buffer.clear();
        int bytesRead = fc.read(buffer);

        if (bytesRead == -1) {
            fc.close();
            close(client);
            return;
        }

        buffer.flip();

        client.write(buffer, null, new CompletionHandler<Integer, Void>() {
            @Override
            public void completed(Integer result, Void att) {
                sendChunk(client, fc, buffer);
            }

            @Override
            public void failed(Throwable exc, Void att) {
                try { fc.close(); } catch (Exception ignored) {}
                close(client);
            }
        });
    } catch (Exception e) {
        e.printStackTrace();
        try { fc.close(); } catch (Exception ignored) {}
        close(client);
    }
}



     private void send404(AsynchronousSocketChannel client, boolean keepAlive) {
        try {
            String msg = "<h1>404 Not Found</h1>";
            StringBuilder header = new StringBuilder()
                    .append("HTTP/1.1 404 Not Found\r\n")
                            .append("Content-Type: text/html\r\n")
                            .append("Content-Length: ").append(msg.length()).append("\r\n")
                            .append((keepAlive ? "Connection: keep-alive\r\n" : "Connection: close\r\n"))
                            .append("\r\n")
                            .append(msg);

            ByteBuffer buf = ByteBuffer.wrap(header.toString().getBytes());

            client.write(buf).get();

            if (keepAlive)
                handle(client);
            else
                close(client);

        } catch (Exception e) {
            close(client);
        }
    }

    private void close(AsynchronousSocketChannel client) {
        try { client.close(); } catch (IOException ignored) {}
    }
}