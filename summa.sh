#!/bin/bash

var1="how are you"
var2="how are you hdoing"

var3= diff var1 var2

echo var3

if [ $# -eq 5 ]
then
echo "you have five parameter"
else
echo "you dont have exactly five parameter"
fi
