1. Port 8080 and 22
2. found tomcat webserver
3. website shows a Yaml Parser
4. Google for Yaml Parser Exploit
5. https://medium.com/@swapneildash/snakeyaml-deserilization-exploited-b4a2c5ac0858 -->does not work but good poc description
6. https://github.com/artsploit/yaml-payload --> works
7. Shell as Tomcat
8. find folders owned by tomcat, grep passwords in /opt/tomcat, found the password for admin in the tomcat-users.xml
9. ssh as admin
10. sudo -l shows that we can run a specific go file
11. found the wasm (web assembly) and the wasmer-go runtime in /opt/wasmer-functions https://github.com/wasmerio/wasmer-go
12. the sudo command outside of this folder does not work but if we are into the folder it does, thats becouse the main.wasm is not path specified so go search for it in the current directory, this means we can create ower own file. we can also change the deploy.sh. 
13. much google to understand what instance.Exports["info"] "wants", is it a variable? a map? a function? it is a function! https://github.com/wasmerio/wasmer-go/issues/74
14. the deploy is only called if the main.wasm contains a function called info with the return value 1.
15. search how to create a wasm file from rust: https://developer.mozilla.org/en-US/docs/WebAssembly/Rust_to_wasm 
16.  installed wasm-pack
17.  create example project
18.  change the lib.rs
19.  copy over to server
20.  change the deploy.sh to create a reverse shell
21.  run the sudo command
22.  got root shell