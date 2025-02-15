default="development"
read -p "Enter local branch (my default, local branch = development): " local
local=${local:-$default}
echo "Local branch is $local"

if [ -z "$local" ]
then
	local='development'
	echo "Default Local branch is $local"
	exit
else
    printf "Enter remote branch: "
    read remote

    if [ -z "$remote" ]
    then
        remote='production'
		echo "Default Remote branch is $remote"
		exit
    fi

	read -r -p 'Commit description: ' desc
    if [ -z "$desc" ]
    then
    	desc='dev_to_prod'
        echo "Default commit description is $desc"
		exit
    fi

    git checkout $remote
    git pull origin $remote
    git pull --no-commit origin $local
    git commit -m "$desc"
    git push origin $remote
    git status
    printf "\nEnd development commit on $local; push to branch $remote. Well done!\n"
fi