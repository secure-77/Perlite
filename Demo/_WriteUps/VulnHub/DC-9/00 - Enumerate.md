# NMAP

Only Port 80

[[../../../_Demo Documents/markdown-sample]]

![[../../../_Demo Documents/pdf-test.pdf]]

[[../../../../Changelog]]

[[../../HackTheBox/Sharp/Notes and Commands]]
# Feroxbuster

```bash
─$ feroxbuster -u http://192.168.178.55 -w /usr/share/seclists/Discovery/Web-Content/raft-small-words.txt -C 403 -x php

 ___  ___  __   __     __      __         __   ___
|__  |__  |__) |__) | /  `    /  \ \_/ | |  \ |__
|    |___ |  \ |  \ | \__,    \__/ / \ | |__/ |___
by Ben "epi" Risher 邏                 ver: 2.2.3
───────────────────────────┬──────────────────────
   Target Url            │ http://192.168.178.55
   Threads               │ 50
   Wordlist              │ /usr/share/seclists/Discovery/Web-Content/raft-small-words.txt
   Status Codes          │ [200, 204, 301, 302, 307, 308, 401, 403, 405]
   Status Code Filters   │ [403]
   Timeout (secs)        │ 7
 說  User-Agent            │ feroxbuster/2.2.3
   Extensions            │ [php]
   Recursion Depth       │ 4
───────────────────────────┴──────────────────────
   Press [ENTER] to use the Scan Cancel Menu™
──────────────────────────────────────────────────
301        9l       28w      319c http://192.168.178.55/includes
301        9l       28w      314c http://192.168.178.55/css
200       50l       88w     1091c http://192.168.178.55/search.php
200       43l       79w      917c http://192.168.178.55/index.php
302        0l        0w        0c http://192.168.178.55/logout.php
200        0l        0w        0c http://192.168.178.55/config.php
200       42l     1062w        0c http://192.168.178.55/display.php
200       16l       58w      933c http://192.168.178.55/css/
200       43l       79w      917c http://192.168.178.55/
200       15l       49w      747c http://192.168.178.55/includes/
200       51l       87w     1210c http://192.168.178.55/manage.php
200       55l      791w     7386c http://192.168.178.55/results.php
302        0l        0w        0c http://192.168.178.55/welcome.php
302        0l        0w        0c http://192.168.178.55/session.php
[####################] - 1m    516036/516036  0s      found:14      errors:252    
[####################] - 56s    86006/86006   1537/s  http://192.168.178.55
[####################] - 56s    86006/86006   1539/s  http://192.168.178.55/includes
[####################] - 56s    86006/86006   1564/s  http://192.168.178.55/css
[####################] - 56s    86006/86006   1545/s  http://192.168.178.55/css/
[####################] - 55s    86006/86006   1553/s  http://192.168.178.55/
[####################] - 59s    86006/86006   1448/s  http://192.168.178.55/includes/

```


https://www.exploit-db.com/exploits/46676