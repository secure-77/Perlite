# Structs

1. We start to define the signature

![[docs/Pasted image 20211104223730.png]]

Then we rename the variables we can guess

looking at the add function we see, this takes only two arguments, so we going to fix this signature as well.

![[docs/Pasted image 20211104224839.png]]

![[docs/Pasted image 20211104224911.png]]

on local_20 we recognize that there is something wrong.

So we need to check the assembly code step by step, first we label the local variables what where not shown in the decompiler and also set the datatypes that we know.

![[docs/Pasted image 20211104235056.png]]

then we try to analyses the rest of the variables, this can be done by click on the first (W) locations and go from there back to check what is into this.

![[docs/Pasted image 20211104235207.png]]
## local_4c

if the first value is 0x0, then we check the next write command.
so for local_4c, it is bottom of cmd arguments + 8byte (which is char*).
the first argument is always the path/bin it self (like argv[0]) 

as we know the parameters from this string:

![[docs/Pasted image 20211105000032.png]]

this is our real first parameter, the key

![[docs/Pasted image 20211104235641.png]]
## local_48

this is bottom of cmd arguments + 16byte, so our second real argument (char* + char*) = 16byte
the username

![[docs/Pasted image 20211104235824.png]]

## local_40

this is bottom of cmd arguments + 24byte, so our third real argument (char* + char* + char*) = 24byte
the password

![[docs/Pasted image 20211105000143.png]]

## gen_password

inspecting the gen_password function we first recognize, that this function gives no value back to any variable.
Also there is some offset for the parameter defined, which is passed to the function

![[docs/Pasted image 20211105001853.png]]

`RAX=>key,[RBP + -0x30]` means that RAX is set to a pointer to key and the address offset -0x30 is passed to this pointer.  Then, this is stored in the RDI, which means this will be passed to the gen_password function.

That is the indicator, that from our RBP (stack button) there are -48 bytes offset and the rest will be stored to the RAX

![[docs/Pasted image 20211105003656.png]]

so we create the following struct as we now already some addresses of data.

![[docs/Pasted image 20211105003856.png]]

and align the int key to be 8 bytes big

we accept to overwrite the variables and rename the variable to generator

now we set the function signature of gen_pasword with our new struct

![[docs/Pasted image 20211105004201.png]]

the gen password function looks now clean
```pcode
int gen_password(generator *generator)

{
  size_t user;
  char *pass;
  int i;
  
  user = strlen(generator->username);
  pass = (char *)malloc((long)(int)user);
  i = 0;
  while (i < (int)user) {
    pass[i] = (byte)generator->key ^ generator->username[i];
    pass[i] = pass[i] + -0x13;
    i = i + 1;
  }
  generator->password = pass;
  return 0;
}
```


our main function is also finally clean

```pcode
int main(int argc,char **argv)

{
  size_t cheklen;
  int count;
  generator generator;
  char *password;
  
  if (argc == 4) {
    generator.key = atoi(argv[1]);
    if (generator.key == 0) {
      puts("Improper key provided, please provide and integer key!\r");
    }
    else {
      generator.username = argv[2];
      cheklen = strlen(generator.username);
      if ((cheklen < 0x256) && (cheklen = strlen(generator.username), 7 < cheklen)) {
        password = argv[3];
        cheklen = strlen(password);
        if ((cheklen < 0x256) && (cheklen = strlen(password), 7 < cheklen)) {
          generator.doubleKey = add(generator.key,generator.key);
          gen_password(&generator);
          count = 0;
          while( true ) {
            cheklen = strlen(generator.password);
            if (cheklen <= (ulong)(long)count) {
              puts("Correct! Access granted!\r");
              free(generator.password);
              return 0;
            }
            if (password[count] != generator.password[count]) break;
            count = count + 1;
          }
          puts("Invalid character in password detected, exiting now!\r");
          return -1;
        }
        puts("Improper password provided, please check the length!\r");
        return -1;
      }
      puts("Improper username provided, please check the length!\r");
    }
  }
  else {
    puts("Please provide your key, username and password!\r\nExample: 12738 wrongbaud P@55W0rd1\r");
  }
  return -1;
}
```

## solution 

So what we need to do:

1. recognize that the key + key is not used, so its just a fake
2. XOR every char from the username with the key
3. then add for every char -0x13

solution in c
```c
#include <stdio.h>
#include <string.h>
#include <stdlib.h>

int main()
{
    
    
    size_t user;
    char *username = "hackingu";
    int i;
    int key = 10;
    
    user = strlen(username);
    char* pass = malloc(user);
    // alternative: char pass[user];

    i = 0;
    while (i < (int)user) {
        
    pass[i] = key ^ username[i];
    pass[i] = pass[i] + -0x13;

    i = i + 1;
    }
    printf("%s",pass);
    

    return 0;
}

```

solution in python

```python
key = 10
username = "hackingu"

user_len = len(username)
password = []

for i in range(user_len):
    
    password.append(key ^ ord(username[i]))
    password[i] = password[i] + -0x13
    
print("".join(chr(n) for n in password))
```

so for the key: 10
and the username: hackingu

![[docs/Pasted image 20211106110222.png]]


the password is: OXVNPQZl

![[docs/Pasted image 20211106110108.png]]


# Pointers

perform more or less the same steps as for structs, expect that we have a new member of the struct (keycalc)

![[docs/Pasted image 20211108174532.png]]

This is how the main function looks after cleaning it up

```pcode
int main(int argc,char **argv)

{
  size_t sVar1;
  int i;
  generator generator;
  char *username;
  
  if (argc == 4) {
    generator.key = atoi(argv[1]);
    if (generator.key == 0) {
      puts("Improper key provided, please provide and integer key!\r");
    }
    else {
      username = argv[2];
      sVar1 = strlen(username);
      if ((sVar1 < 0x256) && (sVar1 = strlen(username), 7 < sVar1)) {
        generator.password = argv[3];
        sVar1 = strlen(generator.password);
        if ((sVar1 < 0x256) && (sVar1 = strlen(generator.password), 7 < sVar1)) {
          generator.keyCalc = keyCalc;
          generator.new_key = keyCalc(generator.key,generator.key + 0xbeef);
          generator.username = username;
          swapNames((char *)&generator.username,(char *)&generator.password);
          gen_password(&generator);
          i = 0;
          while( true ) {
            sVar1 = strlen(generator.password);
            if (sVar1 <= (ulong)(long)i) {
              puts("Correct! Access granted!\r");
              free(generator.password);
              return 0;
            }
            if (username[i] != generator.password[i]) break;
            i = i + 1;
          }
          puts("Invalid character in password detected, exiting now!\r");
          return -1;
        }
        puts("Improper password provided, please check the length!\r");
        return -1;
      }
      puts("Improper username provided, please check the length!\r");
    }
  }
  else {
    puts("Please provide your key, username and password!\r\nExample: 12738 wrongbaud P@55W0rd1\r");
  }
  return -1;
}
```

this the genPassword function

```pcode

void gen_password(generator *param_1)

{
  size_t lenUsername;
  char *pass;
  int i;
  
  lenUsername = strlen(param_1->username);
  pass = (char *)malloc((long)(int)lenUsername);
  i = 0;
  while (i < (int)lenUsername) {
    pass[i] = (byte)param_1->new_key ^ (char)param_1->key + param_1->username[i];
    pass[i] = pass[i] + -0x13;
    i = i + 1;
  }
  param_1->password = pass;
  return;
}
```

this the swap function

```pcode
void swapNames(char *username,char *password)

{
  char tmp;
  
  malloc(8);
  tmp = *username;
  *username = *password;
  *password = tmp;
  return;
}

```

this the keyCalc

```pcode
int keyCalc(int param_1,int param_2)

{
  return (param_2 + param_1) * 8;
}

```

## Solution

based on these information we can adjust our solution script to the following

```python
key = 10

newKey = (key + (key + 48879)) * 8
username = "hackingu"

user_len = len(username)
password = []

for i in range(user_len):
    
    password.append(newKey ^ (key + ord(username[i])))
    password[i] = password[i] + -0x13
    
print("".join(hex(n) for n in password))
```

to take care about the swap function we need to switch the parameter. 
as the output from this script is not with ASCII representable we pass them directly to the challenge

```bash
./pointers 10 `python3 pointers_solution.py` hackingu
```

password: 
```
W`bZXMVT
```

![[docs/Pasted image 20211108175119.png]]

# syscalls
https://chromium.googlesource.com/chromiumos/docs/+/master/constants/syscalls.md

flag constants: https://gist.github.com/Zhangerr/6022492

## fist call

eax = 2 = file open
rdi  = filename = syscalls.txt
fileFlags and fileMode need to be long and converted to octal

rsi = int flags = 102o = O_CREATE & O_RDWR = Create and Write
rdx = int mode = 600o = the Permission 600

--> create the file syscalls.txt with permission 600

## second call

eax = 1 = file write
rdi = file discriptor = rax the return value from the first call
rsi = msg = hackerday-u
rdx= size = 14bytes

--> write into the file syscalls.txt the msg

## third call

eax = 3 = close
rdi = file discriptor

--> close the file

## fourth call
eax = 60 = sysexit
rdi = edi = error code = 0

--> exit the program

![[docs/Pasted image 20211108193649.png]]

# files

We build again a struct
![[docs/Pasted image 20211109011128.png]]

![[docs/Pasted image 20211109122102.png]]

and clean up the code (the gen_password and calcKey function are the same as in pointers)

```pcode
void main(void)

{
  ssize_t fileContent;
  size_t pw_len;
  ulong i2;
  int intKey;
  int i;
  int newFileBuffer;
  int fd_key;
  int fileBuffer;
  int fd_user;
  int fd_pass;
  char *readUser;
  char *readPass;
  struct gen;
  
  intKey = 0;
  newFileBuffer = 0;
  fd_key = open(key.y,0);
  if (fd_key == -1) {
    puts("Could not find key file, please try again!\r");
  }
  else {
    fileContent = read(fd_key,&intKey,4);
    fileBuffer = (int)fileContent;
    if (fileBuffer < 4) {
      puts("Not enough values in keyfile, please try again!\r");
    }
    else {
      newFileBuffer = fileBuffer + -1;
      gen.key = intKey;
      gen.readSizeNew = newFileBuffer;
      fd_user = open(uname.x,0);
      if (fd_user == -1) {
        puts("Could not find username file, please try again!\r");
      }
      else {
        readUser = (char *)malloc(0x255);
        fileContent = read(fd_user,readUser,0x255);
        fileBuffer = (int)fileContent;
        if (fileBuffer < 8) {
          puts("Not enough values in keyfile, please try again!\r");
        }
        else {
          fd_pass = open(pword.z,0);
          if (fd_pass == -1) {
            puts("Could not find password file, please try again!\r");
          }
          else {
            readPass = (char *)malloc(0x255);
            fileContent = read(fd_pass,readPass,0x255);
            fileBuffer = (int)fileContent;
            if (fileBuffer < 8) {
              puts("Not enough values in keyfile, please try again!\r");
            }
            else {
              gen.KeyCalc = keyCalc;
              gen.newKey = keyCalc(gen.key,gen.key + 0xbeef);
              gen.username = readUser;
              gen.password = readPass;
              gen_password(&gen);
              i = 0;
              while (i2 = SEXT48(i), pw_len = strlen(gen.password), i2 < pw_len) {
                if (readPass[i] != gen.password[i]) {
                  puts("Invalid character in password detected, exiting now!\r");
                  return;
                }
                i = i + 1;
              }
              puts("Correct! Access granted!\r");
              free(gen.password);
            }
          }
        }
      }
    }
  }
  return;
}

```


So the biggest challenge for me was to recognize how c code will read bytes into an int object
Its a little bit different to other languages like python

because if you do this in python
(filecontent = 1111)

```python
key = 0
# read as byte
with open("key.y", 'rb') as f:
   key = int(f.read()[:4])

print("key: " + str(key))
```
the Key will be `key: 1111`

but if you do this in c code

```c
int main(int argc, char * argv[])
{

    int key = 0;
    int bytesRead;
    
    int keyFd = open("key.y",O_RDONLY);
    bytesRead = read(keyFd,&key,4);
    printf("key: %i \n", key );
    
    return 0;
}
```
the key will be `key: 825307441`

this is because c will create an int value out of the bytearray and not out of the real value

so to get the same value wee need to do something like this in python
```python
key = 0

with open("key.y", 'rb') as f:
   key = f.read()[:4]

byte_array = bytearray(key)
key = int.from_bytes(byte_array, byteorder='little', signed=True)
print("key: " + str(key))
```
this will output `key: 825307441`

now we can generate our script to generate the correct password:

```python

#with open("key.y", 'rb') as f:
#    key = f.read()[:4]

key = b'1111'
byte_array = bytearray(key)
key = int.from_bytes(byte_array, byteorder='little', signed=True)

newKey = (key + (key + 48879)) * 8
username = "hackingday-u"

user_len = len(username)
password = []

for i in range(user_len):
    password.append(newKey ^ (key + ord(username[i])))
    password[i] = (password[i] + -0x13) % 256

newFile = open("pword.z", "wb")
newFileByteArray = bytearray(password)
newFile.write(newFileByteArray)

print(" ".join(hex((n)) for n in password))
```

![[docs/Pasted image 20211109123741.png]]