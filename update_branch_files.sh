#!/bin/sh

# tr -d '\r' <update_branch_files.sh>update_branch_files_new.sh
# mv update_branch_files_new.sh update_branch_files.sh
# chmod -R 777 update_branch_files.sh

default="development"
read -p "Enter branch (by default, branch = development): " local
local=${local:-$default}
echo "Current Branch is $local"

if [ -z "$local" ]
then
	local='development'
	echo "Default branch is $local"
	# exit
else
    printf "Enter username: "
    read username

    if [ -z "$username" ]
    then
        username=''
        echo "No username found!"
        exit
    fi

    git checkout $local
    git pull "https://$username@bitbucket.org/dancecl/choreographic-lineage.git" $local
    git status
    printf "\n Successfully pulled the code from $local. Well done!\n"
fi