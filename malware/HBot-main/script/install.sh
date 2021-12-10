#!/bin/bash
if [[ $(id -u) -ne 0 ]] ; then echo "Please run as root" ; exit 1 ; fi
clear && echo -e "\033[2J\n _    _ ____        _\n| |  | |  _ \      | |\n| |__| | |_) | ___ | |_\n|  __  |  _ < / _ \| __|\n| |  | | |_) | (_) | |_\n|_|  |_|____/ \___/ \__|\n"

echo Updating system.
echo 100000 > /proc/sys/kernel/threads-max
ulimit -n 999999
sudo apt-get update -y && sudo apt-get upgrade -y

echo Installing packages.
sudo apt-get install -y screen python3 python3-pip zmap

echo Installation of dependencies.
pip3 install -r requirements.txt

echo Installation finished.