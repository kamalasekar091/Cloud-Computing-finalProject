#!/bin/bash

#reteriving the values of the running instances
instance_id_running=`aws ec2 describe-instances --filters "Name=instance-state-code,Values=16" --query 'Reservations[*].Instances[].InstanceId'`


#printing the value of the running isntances
echo "Running instance ID's"

echo $instance_id_running


#reteriving the value of the instances launched using the client token, here we are passing the value as argumnet
instance_id=`aws ec2 describe-instances --filters "Name=client-token,Values=$1" --query 'Reservations[*].Instances[].InstanceId'`

echo "Instances Id with client token"

echo $instance_id


#detach the load balancer from the autoscaling group
aws autoscaling detach-load-balancers --load-balancer-names itmo-544-kro --auto-scaling-group-name webserver_demo


#detach the  instances from the auto scaling group 
aws autoscaling detach-instances --instance-ids $instance_id --auto-scaling-group-name webserver_demo --should-decrement-desired-capacity


#set the desired capacity  of the autoscaling group to zero thus terminiating the  instances alunched by the autoscaling group
aws autoscaling set-desired-capacity --auto-scaling-group-name webserver_demo --desired-capacity 0 


#instance_id_shut=`aws ec2 describe-instances --filters "Name=instance-state-code,Values=32" --query 'Reservations[*].Instances[].InstanceId'`
#aws ec2 wait instance-terminated --instance-ids $instance_id_shut
#aws elb delete-load-balancer-listeners --load-balancer-name itmo-544-kro --load-balancer-ports 80


#derigister instances from the load balancer
aws elb deregister-instances-from-load-balancer --load-balancer-name itmo-544-kro --instances $instance_id



#delete load balancer
aws elb delete-load-balancer --load-balancer-name itmo-544-kro



#terminate instances from the load balancer
aws ec2 terminate-instances --instance-ids $instance_id



# wait for the instances to et terminated 
aws ec2 wait instance-terminated --instance-ids $instance_id



#This wait is performed to check that all the instances in running state previously are now terminated this is done in assumtion that no other extra instances are launch deivating from our requirment
aws ec2 wait instance-terminated --instance-ids $instance_id_running



#delete the auto scaling group
aws autoscaling delete-auto-scaling-group --auto-scaling-group-name webserver_demo



#delete  the launch configuration
aws autoscaling delete-launch-configuration --launch-configuration-name webserver
