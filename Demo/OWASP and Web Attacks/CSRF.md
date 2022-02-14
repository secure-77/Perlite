# CSRF

Cross Site Request Forgery, known as CSRF occurs when a user visits a page on a site, that performs an action on a different site. For instance, let's say a user clicks a link to a website created by a hacker, on the website would be an html tag such as `<img src="https://vulnerable-website.com/email/change?email=pwned@evil-user.net">` which would change the account email on the vulnerable website to "pwned@evil-user.net".  CSRF works because it's the victim making the request not the site, so all the site sees is a normal user making a normal request.

## tool
`pip3 install xsrfprobe`

localtest.me which resolves every subdomain to 127.0.0.1.

https://blog.viadee.de/samesite-cookies-strict-oder-lax


![[docs/Pasted image 20211029172804.png]]