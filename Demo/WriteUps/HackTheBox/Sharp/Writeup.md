1. rustscan found some openports
2. Anonymous Login via SMB is allowed, found the directory kanban
3. decompiled the kanban exe. found the DES Encription, KEY in IV also found the passwords in the db
4. chybercheff, decrypt the passwords
5. found with user lars the smb/dev share, downloaded the exe files and dll
6. decompiled it, this is .NET Remoting, found the endpoint, port and credentials
7.  google for it .net remoting
8. found a python tool ExploitRemotingService.exe  https://github.com/tyranid/ExploitRemotingService
9. needed a hint from the forum, that i have to set the ASM (still dont know what this is) to .NET 4.5 and also set some parameters (also not knewledge about them) and to use ysoserial
10. finding a good page for the payload: https://labs.f-secure.com/advisories/milestone-xprotect-net-deserialization-vulnerability/
11. crated the payload with a reverseshell (download invoke powershell and execute it)
12. got reverse shell with user lars
13. enumerate the services, found the wcf service
14. found the running path (documents in admin)
15. found a .net project in lars/documents, looked at it. found a powershell invoke function in it, 
16. changed the code to call that function and inserted a powershell reverse shell onlineer
17. compiled the exe and copied it to the server and run it from lars
18. got revers shell as root.



general information about .net remoting: https://parsiya.net/blog/2015-11-14-intro-to-.net-remoting-for-hackers/

ysoserial windows .net
https://github.com/pwntester/ysoserial.net



