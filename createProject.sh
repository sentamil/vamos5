#!/bin/bash

Project_Name="$1"
Port="$2"

if [ -z $Project_Name ]
then
echo "Project Name should be first prameter"
exit 1
fi

if [ -z ${Port} ]
then
echo "Port number should second parameter"
exit 1
fi





if [ ! -z ${Project_Name} ]
then

cd /var/www/white
composer create-project laravel/laravel ${Project_Name}  4.2
cp /var/www/vamo/app/config/database.php /var/www/white/${Project_Name}/app/config/database.php
sed -i 's/vamos.sqlite/'${Project_Name}'.sqlite/g' /var/www/white/${Project_Name}/app/config/database.php
cp -R /var/www/vamo/public/* /var/www/white/${Project_Name}/public/
cp -R /var/www/vamo/app/views/* /var/www/white/${Project_Name}/app/views/
cp -R /var/www/vamo/app/controllers/* /var/www/white/${Project_Name}/app/controllers/
cp -R /var/www/vamo/app/routes.php /var/www/white/${Project_Name}/app/routes.php
#cp -R /var/www/vamo/app/database/migrations/* /var/www/white/${Project_Name}/app/database/migrations/
cp -R /var/www/vamo/app/database/seeds/UserTableSeeder.php /var/www/white/${Project_Name}/app/database/seeds/UserTableSeeder.php
cp -R /var/www/vamo/app/database/seeds/DatabaseSeeder.php /var/www/white/${Project_Name}/app/database/seeds/DatabaseSeeder.php

if [ -d /var/www/white/${Project_Name} ]
then

find /var/www/white/${Project_Name} -name "*.php" -type f -exec sed -i 's/VAMOS/'${Project_Name}'/g' {} \;
find /var/www/white/${Project_Name} -name "*.js" -type f -exec sed -i 's#/vamo/public#:'${Port}'/'${Project_Name}'/public/#g' {} \;

fi


cd /var/www/white/${Project_Name}/app/database
touch ${Project_Name}.sqlite
chmod -R 777 /var/www/white/${Project_Name}/app/database
cd /var/www/white/${Project_Name}
php artisan migrate:make create_users_table --create=users
fileName=`ls /var/www/white/${Project_Name}/app/database/migrations/*_*_*create_users_table.php|tail -1`
cat /var/www/vamo/app/database/migrations/create_users_table.php > ${fileName}
php artisan migrate --force
php artisan db:seed --force
chmod -R 777 /var/www/white/${Project_Name}/app/storage
cp /etc/apache2/ports.conf /etc/apache2/ports.conf.bkp
echo "NameVirtualHost *:${Port}" >> /etc/apache2/ports.conf
echo "Listen ${Port}" >> /etc/apache2/ports.conf
cp /var/www/vamo/virtual_host.conf /tmp/virtual_host.conf
sed -i 's/PORT/'${Port}'/g' /tmp/virtual_host.conf
cp /etc/apache2/sites-available/default /etc/apache2/sites-available/default.bkp
cat /tmp/virtual_host.conf >> /etc/apache2/sites-available/default
service apache2 restart

else

echo "Project Name should not be empty"

fi
