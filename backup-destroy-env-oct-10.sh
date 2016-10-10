#!/bin/bash

#Grab the instance Id' of the running instance
instance_id_running=`aws ec2 describe-instances --filters "Name=instance-state-code,Values=16" --query 'Reservations[*].Instances[].InstanceId'`

echo "Running instance ID's"

echo $instance_id_running

#Grab the instance id of teh instance with the client token

instance_id=`aws ec2 describe-instances --filters "Name=client-token,Values=$1" --query 'Reservations[*].Instances[].InstanceId'`

echo "Instances Id with client token"

echo $instance_id

#grab the load balancer name

load_balancer_name=`aws elb describe-load-balancers --query 'LoadBalancerDescriptions[*].LoadBalancerName'`

echo $load_balancer_name

#grab the launch configuration name

launch_config_name=`aws autoscaling describe-launch-configurations --query 'LaunchConfigurations[].LaunchConfigurationName'`

echo $launch_config_name

#grab the  auto scaling group name

auto_scaling_name=`aws autoscaling describe-auto-scaling-groups --query 'AutoScalingGroups[*].AutoScalingGroupName'`

echo $auto_scaling_name

#Detach load balancer from the autoscaling group

aws autoscaling detach-load-balancers --load-balancer-names $load_balancer_name --auto-scaling-group-name $auto_scaling_name

#detach instances from the autoscaling group so that we can perform the deregister operations

aws autoscaling detach-instances --instance-ids $instance_id --auto-scaling-group-name $auto_scaling_name --should-decrement-desired-capacity

#set  the  autos caling  desired capacity to zero

aws autoscaling set-desired-capacity --auto-scaling-group-name $auto_scaling_name --desired-capacity 0 

#deregister instances

aws elb deregister-instances-from-load-balancer --load-balancer-name $load_balancer_name --instances $instance_id

# delete load balancer
aws elb delete-load-balancer --load-balancer-name $load_balancer_name

#terminate instances
aws ec2 terminate-instances --instance-ids $instance_id

#wait for instances to get terminated
aws ec2 wait instance-terminated --instance-ids $instance_id

aws ec2 wait instance-terminated --instance-ids $instance_id_running

#delete autoscaling group and  launch configuration
aws autoscaling delete-auto-scaling-group --auto-scaling-group-name $auto_scaling_name

aws autoscaling delete-launch-configuration --launch-configuration-name $launch_config_name
