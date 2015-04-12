#!/bin/sh

str="Somestring"
str2=$(echo $str | awk '{print toupper($0)}')
#$str1=$( echo "$str" | tr -s  '[:upper:]'  '[:lower:]' )
echo $str2
