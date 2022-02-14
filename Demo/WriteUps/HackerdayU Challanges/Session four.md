crackme.one 

# noprelo

it runs the ptrace function with the following params:

ptrace(0, 0, 1, 0) 


![[docs/Pasted image 20211117202322.png]]

we can use ltrace to see this and also check the status code

status 1 means we failed the first check, the return value of ptrace must be 1337

we also need to provide the correct parameter for the call

![[docs/Pasted image 20211117202425.png]]


we can use ldpreload to override the ptrace function with our own

inject.c
```c
#include <stdio.h>
#include <stdlib.h>

long ptrace() {
        printf("injected\n");
        return 1337;

}

```

compile this and run it
```bash
gcc -shared -fPIC inject.c -o inject.so
LD_PRELOAD=./inject.so ./noprelo __gmon_start__
```


# half-twins
need 2 params
both params must be greater then 7 and the same size

paramLen must be end with 0 so maybe 10 chars
first loop runs paramLen / 2, so 5 times, these chars must be equal

next chars must be odd

![[docs/Pasted image 20211117204138.png]]

```bash
./half-twins aaaaabbbbb aaaaaccccc
```

![[docs/Pasted image 20211117204152.png]]

# auth

follow this instruction: https://github.com/cd80-ctf/crackmes.one/tree/main/BitFriends-auth

