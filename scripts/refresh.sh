#!/bin/bash

Project_Name="$1"
Port="$2"

if [ -z $Project_Name ]
then
echo "Project Name should be first prameter"
exit 1
fi

cp -R /var/www/vamo/public/* /var/www/white/${Project_Name}/public/
cp -R /var/www/vamo/app/views/* /var/www/white/${Project_Name}/app/views/ 
cp -R /var/www/vamo/app/controllers/* /var/www/white/${Project_Name}/app/controllers/
cp -R /var/www/vamo/app/routes.php /var/www/white/${Project_Name}/app/routes.php
cp /var/www/white/ahad/public/assets/imgs/logo.png.orig /var/www/white/ahad/public/assets/imgs/logo.png
if [ -d /var/www/white/${Project_Name} ]
then

upperProjName=$(echo ${Project_Name} | awk '{print toupper($0)}')

find /var/www/white/${Project_Name} -name "*.php" -type f -exec sed -i 's/VAMOS/'${upperProjName}'/g' {} \;
find /var/www/white/${Project_Name} -name "*.js" -type f -exec sed -i 's#/vamo/public#:'${Port}'/'${Project_Name}'/public/#g' {} \;

fi
