
## LaTeX


Inline math equations go in like so: $\omega = d\phi / dt$. 
Display math should get its own line and be put in in double-dollarsigns:

$$I = \int \rho R^{2} dV$$

And note that you can backslash-escape any punctuation characters which you wish to be displayed literally, ex.: \`foo\`, \*bar\*, etc.

 
## Mermaid

Simple Flow Chart

```mermaid
flowchart LR
    id1[[This is the text in the box1]]
```


Mermaid Graph

```mermaid
graph TD;
A-->B;
A-->C;
B-->D;
C-->D;
```

Sequence Diagram

```mermaid
sequenceDiagram
    participant Alice
    participant Bob
    Alice->>John: Hello John, how are you?
    loop Healthcheck
        John->>John: Fight against hypochondria
    end
    Note right of John: Rational thoughts <br/>prevail!
    John-->>Alice: Great!
    John->>Bob: How about you?
    Bob-->>John: Jolly good!
```






