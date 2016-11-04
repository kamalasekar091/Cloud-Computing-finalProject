#!/bin/bash

aws rds create-db-instance --db-instance-identifier itmo544-krose1-mysqldb --allocated-storage 5 --db-instance-class db.t2.micro --engine mysql --master-username controller --master-user-password controllerpass  --availability-zone us-west-2b --db-name school
db_instance_id=`aws rds describe-db-instances --query 'DBInstances[*].DBInstanceIdentifier'`
echo $db_instance_id
aws rds wait db-instance-available --db-instance-identifier $db_instance_id
echo "Data base created"
echo $db_instance_id
#aws rds delete-db-instance --skip-final-snapshot --db-instance-identifier $db_instance_id
#aws rds wait db-instance-deleted --db-instance-identifier $db_instance_id
#echo "instance deleted"

# create an S3 bucket
aws s3api create-bucket --bucket $1 --region us-west-2

aws s3api create-bucket --bucket $2 --region us-west-2

#wait for bucket availability
aws s3api bucket-exists -bucket $1
echo "$1 created"

aws s3api bucket-exists -bucket $2

echo "$2 created"

#create queue
aws sqs create-queue --queue-name kro-queue

