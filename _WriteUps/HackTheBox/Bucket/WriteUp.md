1. Scan found 80 and 22
2. 80 redirect to bucket.htb -> insert in hosts
3. in sourceCode found s3.bucket.htb subdomain -> insert in hosts
4. scan s3.bucket.htb found /health and dynamodb
5. download and install aws cli, set endpoint to http://s3.bucket.htb/
6. found some credentials in dynamodb, but no useful at the moment
7. Upload PHP Remote Shell do s3 bucket via aws cli
8. Remote Shell as WWW-Data User
9. Found usr roy, escalte to user roy with the Sysadm passwortd from the dynamodb
10. ssh to user roy
11. found the virtualhost config in apache that Port 8000 runs on localhost as root
12. ssh tunnel port 8000 
13. found the php file with pd4ml, that extract data from the aws dynamodb, creates a html file and then convert it to pdf
14. found this artikel about that: https://infosecwriteups.com/how-i-hacked-redbus-an-online-bus-ticketing-application-24ef5bb083cd
15. create dynamodb table 
16. put matching item into the table with payload (pd4ml:attachment read /root/.ssh/id_rsa)
17. call the post request
18. get the result.pdf and the private root ssh key
19. login to root


# news
- hole aws cli subject
- aws s3 buckets finding, listing, creating, putting files to it, linked to web servers etc.
- aws dynamodb: read tables, scan items, create table, put item, understand searching
- pd4ml html to pdf creator, call link, read files inclusion https://pd4ml.com/cookbook/pdf-attachments.htm