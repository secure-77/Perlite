# Reverse Tabnabbing

see box: [[../../WriteUps/HackTheBox/Developer/WriteUp|Custom Link]]


Background Infos:  https://html.spec.whatwg.org/multipage/browsers.html#determining-the-origin

HTB Example: https://blog.0xprashant.in/posts/htb-bug/

OWASP: https://owasp.org/www-community/attacks/Reverse_Tabnabbing

![[docs/Pasted image 20211031000935.png]]

Examples
Vulnerable page:
```html
<html>
 <body>
  <li><a href="bad.example.com" target="_blank">Vulnerable target using html link to open the new page</a></li>
  <button onclick="window.open('https://bad.example.com')">Vulnerable target using javascript to open the new page</button>
 </body>
</html>
```

Malicious Site that is linked to:

```html
<html>
 <body>
  <script>
   if (window.opener) {
      window.opener.location = "https://geizhals.de";
   }
  </script>
 </body>
</html>
```



# fixed in many browsers

target="_blank" is fixed in many browsers but if you can control the target attribute you can still use something like target="aa" to get this working