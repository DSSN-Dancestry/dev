#!/bin/bash

# Do not copy this script on the production server!

# Author: Hanzhang Bai
# Updated on 12 Feb 2021 15:29
echo -e "INFO: Are you running this script as root or sudo? \c"
if [[ $EUID -ne 0 ]]; then
echo -e "\e[31mNo \e[0m"
echo "ERROR: This script must be run as root since it mounts this project in your xampp."
echo "INFO: Restart it as 'sudo $0' instead" 
exit 1
else
echo -e "\e[32mYes \e[0m"
fi
rm -rf /opt/lampp/htdocs
cd ..
ln -s $(pwd) /opt/lampp/htdocs