#!/bin/bash

aws ec2 run-instances --image-id $1 --key-name inclassnew --security-group-ids sg-6a60ad13 --instance-type t2.micro --user-data file://installapp.sh --placement AvailabilityZone=us-west-2b --count 3 
instance_id=`aws ec2 describe-instances --filters "Name=instance-state-code,Values=0" --query 'Reservations[*].Instances[].InstanceId'`
echo $instance_id
aws ec2 wait instance-running --instance-ids $instance_id
aws elb create-load-balancer --load-balancer-name itmo-544-kro --listeners Protocol=HTTP,LoadBalancerPort=80,InstanceProtocol=HTTP,InstancePort=80 --subnets subnet-47b29123
aws elb create-load-balancer-listeners --load-balancer-name itmo-544-kro --listeners "Protocol=HTTP,LoadBalancerPort=80,InstanceProtocol=HTTP,InstancePort=80"
aws elb register-instances-with-load-balancer --load-balancer-name itmo-544-kro --instances $instance_id
aws autoscaling create-launch-configuration --launch-configuration-name webserver --image-id $1  --key-name inclassnew --instance-type t2.micro --user-data file://installapp.sh
aws autoscaling create-auto-scaling-group --auto-scaling-group-name webserver_demo --launch-configuration-name webserver --availability-zones us-west-2b --min-size 0 --max-size 5 --desired-capacity 1
aws autoscaling attach-instances --instance-ids $instance_id --auto-scaling-group-name webserver_demo
aws autoscaling attach-load-balancers --load-balancer-names itmo-544-kro --auto-scaling-group-name webserver_demo
