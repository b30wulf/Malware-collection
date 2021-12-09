clear
echo "\e[1;32m-> Supercharge Setup Script"
echo "---------------------------"
echo "\e[1;33m-> Installing Mingw Cross Compiler"
echo "---------------------------" 
apt-get install mingw-w64 > /dev/null
echo "\e[1;33m-> Installing Python3 Pip"
echo "---------------------------"
apt-get install python3-pip > /dev/null
echo "\e[1;33m-> Installing Required Python Packages"
echo "---------------------------"
pip3 install -r requirements.txt 
sleep 1
clear
echo "\e[1;94m-> DONE."
