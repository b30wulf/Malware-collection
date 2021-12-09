#!/bin/bash

while read d; 
do
 iptables -I INPUT -s  $d -j DROP;
 echo -e "\033[38;5;235m -> Added ip:\033[38;5;239m $d \033[39m";
done <ips.txt
