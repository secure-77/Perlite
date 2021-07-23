# Write UP
## foothold

1. Port 80 CuteNews Python Exploit gefunden  CUPS 2.1.3
2. Reverse Shell
3. Linpeas
	1. USBCreator ?



## User Paul

1. Das Exploit gibt uns auch gleich ein paar Hashes
2. .\hashcat64.exe -m 1400 -w 3 hashes.txt .\rockyou.txt --outfile final.txt
3. findet das Passwort atlanta1
4. ssh Key im .ssh ordner


## User Nadav
1. der SSH Key geht auch für diesen user
2. linpeas findet USBCreator vulnerability
3. wir sind mitglied der sudo gruppe


## root
1. Ubuntu USBCreator Exploit funktioniert hier
2. Wir können beliebe Dateien als root kopieren (für eine shell wäre wohl am besten ein eigenes c programm mit bash -p oder sh -p möglich, somit wird der password prompt unterbunten)




# was hab ich gelernt was war neu
1. CuteNews Exploit
2. SSH keys auch immer für andere User probieren, authorized_keys checken!
3. Ubuntu USBCreator Exploit genutzt
4. ssh key aus root\.ssh kopiert