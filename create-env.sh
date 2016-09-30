#!/bin/bash

#Launching 3 new instances, using the passed parameter
aws ec2 run-instances --image-id $1 --key-name inclassnew --security-group-ids sg-6a60ad13 --client-token $2 --instance-type t2.micro --user-data file://installapp.sh --placement AvailabilityZone=us-west-2b --count 3

#reteriving the instances ID with given clinet token in run instances command
instance_id=`aws ec2 describe-instances --filters "Name=client-token,Values=$2" --query 'Reservations[*].Instances[].InstanceId'`

#Printing the instances ID's
echo $instance_id

#wait for the  launched instances to come to runniing state
aws ec2 wait instance-running --instance-ids $instance_id

#launch a load balancer  with HTTP listner 
aws elb create-load-balancer --load-balancer-name itmo-544-kro --listeners Protocol=Http,LoadBalancerPort=80,InstanceProtocol=Http,InstancePort=80 --subnets subnet-47b29123 --security-groups sg-6a60ad13

#aws elb create-load-balancer-listeners --load-balancer-name itmo-544-kro --listeners "Protocol=HTTP,LoadBalancerPort=80,InstanceProtocol=HTTP,InstancePort=80"

#register the running instances with the load balancer
aws elb register-instances-with-load-balancer --load-balancer-name itmo-544-kro --instances $instance_id

#creat a launch configuration to attch  it to the auto scaling group
aws autoscaling create-launch-configuration --launch-configuration-name webserver --image-id $1  --key-name inclassnew --instance-type t2.micro --user-data file://installapp.sh --security-groups sg-6a60ad13

#create a auto scaling group with minumum capacity as 0 and desired capacity as 1
aws autoscaling create-auto-scaling-group --auto-scaling-group-name webserver_demo --launch-configuration-name webserver --availability-zones us-west-2b --min-size 0 --max-size 5 --desired-capacity 1

#attach the running instances with the auto scaling group to over come the existing extra autoscaling problem now the desired capacity is increased to 4 
aws autoscaling attach-instances --instance-ids $instance_id --auto-scaling-group-name webserver_demo

#attach the load balancer to the autoscaling group
aws autoscaling attach-load-balancers --load-balancer-names itmo-544-kro --auto-scaling-group-name webserver_demo
