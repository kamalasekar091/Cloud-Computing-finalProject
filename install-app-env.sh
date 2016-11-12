#!/bin/bash

aws rds create-db-instance --db-instance-identifier itmo544-krose1-mysqldb --allocated-storage 5 --db-instance-class db.t2.micro --engine mysql --master-username controller --master-user-password controllerpass  --availability-zone us-west-2b --db-name school
db_instance_id=`aws rds describe-db-instances --query 'DBInstances[*].DBInstanceIdentifier'`
echo $db_instance_id
aws rds wait db-instance-available --db-instance-identifier $db_instance_id
echo "Data base created"
echo $db_instance_id
db_instance_url=`aws rds describe-db-instances --query 'DBInstances[*].Endpoint[].Address'`
mysql --host=$db_instance_url --user='controller' --password='controllerpass' school << EOF
CREATE TABLE records(id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,email VARCHAR(32),phone VARCHAR(32),s3-raw-url VARCHAR(32),s3-finished-url VARCHAR(32),status INT(1),receipt VARCHAR(256));
create table credentials (ID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, userName VARCHAR(30) NOT NULL, userPass VARCHAR(30) NOT NULL);
INSERT INTO credentials (userName,userPass) VALUES ('krose1','letmein');
INSERT INTO credentials (userName,userPass) VALUES ('jrh','letmein');
INSERT INTO credentials (userName,userPass) VALUES ('controller','letmein');
commit;
EOF

#aws rds delete-db-instance --skip-final-snapshot --db-instance-identifier $db_instance_id
#aws rds wait db-instance-deleted --db-instance-identifier $db_instance_id
#echo "instance deleted"

#Create SNS Topic 
topic_arn_name=`aws sns create-topic --name krose-topic`

#create Subscribe topic
aws sns subscribe --topic-arn $topic_arn_name --protocol email --notification-endpoint kamalasekar091@gmail.com

# create an S3 bucket
aws s3api create-bucket --bucket $1 --region us-west-2 --create-bucket-configuration LocationConstraint=us-west-2

aws s3api create-bucket --bucket $2 --region us-west-2 --create-bucket-configuration LocationConstraint=us-west-2

#wait for bucket availability
aws s3api wait bucket-exists --bucket $1
echo "$1 created"

aws s3api wait bucket-exists --bucket $2

echo "$2 created"

#create queue
aws sqs create-queue --queue-name kro-queue

