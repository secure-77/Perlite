```bash
[[~] The config file is expected to be at "/home/kalium/.rustscan.toml"
[!] File limit is lower than default batch size. Consider upping with --ulimit. May cause harm to sensitive servers
[!] Your file limit is very small, which negatively impacts RustScan's speed. Use the Docker image, or up the Ulimit with '--ulimit 5000'.
Open 192.168.178.73:445
Open 192.168.178.73:139
Open 192.168.178.73:80
Open 192.168.178.73:21
Open 192.168.178.73:65111
```


# nmap

```bash
Scanning Alfa.fritz.box (192.168.178.73) [65535 ports]
Discovered open port 21/tcp on 192.168.178.73
Discovered open port 80/tcp on 192.168.178.73
Discovered open port 445/tcp on 192.168.178.73
Discovered open port 139/tcp on 192.168.178.73
Discovered open port 65111/tcp on 192.168.178.73

PORT      STATE SERVICE     REASON         VERSION
21/tcp    open  ftp         syn-ack ttl 64 vsftpd 3.0.3
| ftp-anon: Anonymous FTP login allowed (FTP code 230)
|_drwxr-xr-x    2 0        0            4096 Dec 17 13:02 thomas
| ftp-syst:
|   STAT:
| FTP server status:
|      Connected to ::ffff:192.168.178.41
|      Logged in as ftp
|      TYPE: ASCII
|      No session bandwidth limit
|      Session timeout in seconds is 300
|      Control connection is plain text
|      Data connections will be plain text
|      At session startup, client count was 4
|      vsFTPd 3.0.3 - secure, fast, stable
|_End of status
80/tcp    open  http        syn-ack ttl 64 Apache httpd 2.4.38 ((Debian))
| http-methods:
|_  Supported Methods: OPTIONS HEAD GET POST
|_http-server-header: Apache/2.4.38 (Debian)
|_http-title: Alfa IT Solutions
139/tcp   open  netbios-ssn syn-ack ttl 64 Samba smbd 3.X - 4.X (workgroup: WORKGROUP)
445/tcp   open  netbios-ssn syn-ack ttl 64 Samba smbd 4.9.5-Debian (workgroup: WORKGROUP)
65111/tcp open  ssh         syn-ack ttl 64 OpenSSH 7.9p1 Debian 10+deb10u2 (protocol 2.0)
| ssh-hostkey:
|   2048 ad:3e:8d:45:48:b1:63:88:63:47:64:e5:62:28:6d:02 (RSA)
| ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAABAQC2/gN4xwraW4+k393E8l0qsfBzclz6JW+SZG4rtYaonpi1RNGoTWSOgfEUm74RQocMqqklmzlqYVpr1jWu7+hqKZyQvhS3Z02/bbl2aPLskzxJSHNQwX6C5gbA1m4ilgWr7beOvLr0ZsS1FwsM7F3UghKpgjWcXhK+PGYi9kh79q3HO0KZlhv46fpiPLxVOi7g4jA/TB7RZFEyWUgH0oUFqC6h9TGitOuH9mm1cVFbSFve/Xv+R3Fg1/nOsXtMp/dbk3/qRBLnAZuMie4Lfi6Ri/ogb16NfQBowSv65zq3V312ctWdtp9ACrqNdHukHavW09qanQ6j+MAYFqI/gVx/
|   256 1d:b3:0c:ca:5f:22:a4:17:d6:61:b5:f7:2c:50:e9:4c (ECDSA)
| ecdsa-sha2-nistp256 AAAAE2VjZHNhLXNoYTItbmlzdHAyNTYAAAAIbmlzdHAyNTYAAABBBJWoOk2y6Gj22LwB1cphvfRxANuV99NkaatiHlQ3qoGomRhyzNzK2AWLBrHasjWbJKDxci+7JEdP99XCBYZzKHQ=
|   256 42:15:88:48:17:42:69:9b:b6:e1:4e:3e:81:0b:68:0c (ED25519)
|_ssh-ed25519 AAAAC3NzaC1lZDI1NTE5AAAAICRMwXyo2xpfoG0gAJKYSDnTdwp8RRZMVHrQS2wNB5T1
MAC Address: 08:00:27:CB:4A:42 (Oracle VirtualBox virtual NIC)
Service Info: Host: ALFA; OSs: Unix, Linux; CPE: cpe:/o:linux:linux_kernel

Host script results:
|_clock-skew: mean: -39m57s, deviation: 1h09m16s, median: 2s
| nbstat: NetBIOS name: ALFA, NetBIOS user: <unknown>, NetBIOS MAC: <unknown> (unknown)
| Names:
|   ALFA<00>             Flags: <unique><active>
|   ALFA<03>             Flags: <unique><active>
|   ALFA<20>             Flags: <unique><active>
|   \x01\x02__MSBROWSE__\x02<01>  Flags: <group><active>
|   WORKGROUP<00>        Flags: <group><active>
|   WORKGROUP<1d>        Flags: <unique><active>
|   WORKGROUP<1e>        Flags: <group><active>
| Statistics:
|   00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00
|   00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00
|_  00 00 00 00 00 00 00 00 00 00 00 00 00 00
| p2p-conficker:
|   Checking for Conficker.C or higher...
|   Check 1 (port 52243/tcp): CLEAN (Couldn't connect)
|   Check 2 (port 25203/tcp): CLEAN (Couldn't connect)
|   Check 3 (port 58252/udp): CLEAN (Failed to receive data)
|   Check 4 (port 45809/udp): CLEAN (Failed to receive data)
|_  0/4 checks are positive: Host is CLEAN or ports are blocked
| smb-os-discovery:
|   OS: Windows 6.1 (Samba 4.9.5-Debian)
|   Computer name: alfa
|   NetBIOS computer name: ALFA\x00
|   Domain name: \x00
|   FQDN: alfa
|_  System time: 2021-05-23T22:57:21+02:00
| smb-security-mode:
|   account_used: guest
|   authentication_level: user
|   challenge_response: supported
|_  message_signing: disabled (dangerous, but default)
| smb2-security-mode:
|   2.02:
|_    Message signing enabled but not required
| smb2-time:
|   date: 2021-05-23T20:57:21
|_  start_date: N/A

```

## udp scan

```bash
PORT    STATE         SERVICE     REASON       VERSION
68/udp  open|filtered dhcpc       no-response
137/udp open          netbios-ns  udp-response Samba nmbd netbios-ns (workgroup: WORKGROUP)
138/udp open|filtered netbios-dgm no-response
MAC Address: 08:00:27:CB:4A:42 (Oracle VirtualBox virtual NIC)
Service Info: Host: ALFA

Host script results:
| nbstat: NetBIOS name: ALFA, NetBIOS user: <unknown>, NetBIOS MAC: <unknown> (unknown)
| Names:
|   ALFA<00>             Flags: <unique><active>
|   ALFA<03>             Flags: <unique><active>
|   ALFA<20>             Flags: <unique><active>
|   \x01\x02__MSBROWSE__\x02<01>  Flags: <group><active>
|   WORKGROUP<00>        Flags: <group><active>
|   WORKGROUP<1d>        Flags: <unique><active>
|   WORKGROUP<1e>        Flags: <group><active>
| Statistics:
|   00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00
|   00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00
|_  00 00 00 00 00 00 00 00 00 00 00 00 00 00

NSE: Script Post-scanning.
NSE: Starting runlevel 1 (of 3) scan.
Initiating NSE at 23:18
Completed NSE at 23:18, 0.00s elapsed
NSE: Starting runlevel 2 (of 3) scan.
Initiating NSE at 23:18
Completed NSE at 23:18, 0.00s elapsed
NSE: Starting runlevel 3 (of 3) scan.
Initiating NSE at 23:18
Completed NSE at 23:18, 0.00s elapsed
Read data files from: /usr/bin/../share/nmap
Service detection performed. Please report any incorrect results at https://nmap.org/submit/ .
Nmap done: 1 IP address (1 host up) scanned in 1208.98 seconds
           Raw packets sent: 1393 (62.016KB) | Rcvd: 4946838 (474.820MB)
```



# webserver / Port 80
Apache/2.4.38 (Debian)

![[Pasted image 20210523231021.png]]

= no links available

robots.txt = nothing 

![[Pasted image 20210523230914.png]]

/js = nothing

![[Pasted image 20210523230900.png]]

/css = nothing

# feroxbuster 

## common.txt

```bash
301        9l       28w      314c http://alfa.fritz.box/css
301        9l       28w      316c http://alfa.fritz.box/fonts
301        9l       28w      317c http://alfa.fritz.box/images
200       95l      279w     3870c http://alfa.fritz.box/index.html
301        9l       28w      313c http://alfa.fritz.box/js
200      262l        9w      459c http://alfa.fritz.box/robots.txt
```

## raft-smal-words.txt with php and html

```bash
301        9l       28w      317c http://alfa.fritz.box/images
301        9l       28w      313c http://alfa.fritz.box/js
301        9l       28w      314c http://alfa.fritz.box/css
200       95l      279w     3870c http://alfa.fritz.box/index.html
200       23l      123w     2421c http://alfa.fritz.box/images/
301        9l       28w      316c http://alfa.fritz.box/fonts
200       95l      279w     3870c http://alfa.fritz.box/
200       20l      102w     1816c http://alfa.fritz.box/js/
200       22l      115w     2217c http://alfa.fritz.box/css/
200       31l      218w     4448c http://alfa.fritz.box/fonts/
```


# alfa-support

http://alfa.fritz.box/alfa-support/

![[Pasted image 20210524000417.png]]




# FTP

Anonymous login is accessible

found user thomas
found dog picture milo.jpg

check for stego:

```bash
┌──(kalium㉿kali)-[~/alfa]
└─$ exiv2 milo.jpg                                                                                                                                                                                            1 ⨯
Dateiname       : milo.jpg
Dateigröße      : 104068 Bytes
MIME-Typ        : image/jpeg
Bildgröße       : 1280 x 720
milo.jpg: Es wurden keine Exif-Daten in der Datei gefunden
```

maybe try bruteforce 
https://book.hacktricks.xyz/stego/stego-tricks

= nothing with rockyou.txt





