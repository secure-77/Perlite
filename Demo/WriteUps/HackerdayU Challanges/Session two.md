# Control Flows

1. Value must be greater then second
2. second value* 2 must be greater then first
3. first value + second value + first value must be greater then 100

![[docs/Pasted image 20211101222602.png]]

![[docs/Pasted image 20211101222713.png]]

# Loops

1. Needs to 15 Chars long
2. needs to loop exactly 8 times
3. it loops if the character is between A and Z

![[docs/Pasted image 20211101225112.png]]

![[docs/Pasted image 20211101225239.png]]

# Functions

1. compare the both functions 
2. provide the same amount of lower and upper cases as parameter

![[docs/Pasted image 20211101231817.png]]

![[docs/Pasted image 20211101231853.png]]

# Heap

1. the program allocate 12 bytes in the heap memory
2. provide a 12 bytes long string

![[docs/Pasted image 20211101232631.png]]

![[docs/Pasted image 20211101232651.png]]

# Arrays

1. select the keywords and change them to type char**
2. then create a array

![[docs/Pasted image 20211101234623.png]]

to solve this i copied the p code from ghydra and created a simple loop around this, who prints me the solutions

```c
#include <stdio.h>
#include <string.h>

int main()
{
      int arraySize = 5;
      
      
      for (int index = 0; index < arraySize; ++index)
     {
      
      char* keywords[] = {"hackadayu", "software", "reverse", "engineering", "ghidra"};
      
      size_t keyword_len = strlen((char *)keywords[index]);
      int count = 0;
      
      printf("keyword %s = %i ",keywords[index],index);
      
      while (count < (int)keyword_len) {
       char singleChar_ofElement = *(char *)((long)count + (long)keywords[index]);
       char temp_singleChar = *(char *)keywords[index];
      
        if (count == (int)keyword_len + -1) {
          temp_singleChar = *(char *)keywords[index];
        }
        else {
          temp_singleChar = *(char *)((long)keywords[index] + (long)count + 1);
        }
        
        if (temp_singleChar < singleChar_ofElement) {
          singleChar_ofElement = singleChar_ofElement - temp_singleChar;
        }
        else {
          singleChar_ofElement = temp_singleChar - singleChar_ofElement;
        }
        
        singleChar_ofElement = singleChar_ofElement + '`';
        printf("%c",(char)singleChar_ofElement);
      
        count = count + 1;
      }
      
      printf("\n\r");
     } 
     

    return 0;
}
```

output
```plain
keyword hackadayu = 0 gbhjccxdm
keyword softwar = 1 dincvqmn
keyword reverse = 2 mqqmanm
keyword engineering = 3 igbei`miegb
keyword ghidra = 4 aaenqf
```

so we can solve them easy
![[docs/Pasted image 20211102145724.png]]