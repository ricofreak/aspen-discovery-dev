#!/bin/sh
if [ -z "$1" ]
  then
    echo "Please provide the server name to update as the first argument."
    exit 1
fi

printf "Updating apache configuration\n"
php /usr/local/aspen-discovery/install/updateCron_24.12.00.php $1
