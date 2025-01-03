#!/bin/bash
# Ensure MariaDB client is installed
sudo apt-get update
sudo apt-get install -y mariadb-client

# Ensure we have the latest files from the CANNEX FTP
/var/www/platform/artisan cannex:update

# Refresh CANNEX table data
echo -e "\r\n==[ PART 01/09 ] =======================================================\r\n"
/var/www/platform/artisan cannex:import carriers
echo -e "\r\n==[ PART 02/09 ] =======================================================\r\n"
/var/www/platform/artisan cannex:import indexes
echo -e "\r\n==[ PART 03/09 ] =======================================================\r\n"
/var/www/platform/artisan cannex:import products
echo -e "\r\n==[ PART 04/09 ] =======================================================\r\n"
/var/www/platform/artisan cannex:import products-instances
echo -e "\r\n==[ PART 05/09 ] =======================================================\r\n"
/var/www/platform/artisan cannex:import products-profiles
echo -e "\r\n==[ PART 06/09 ] =======================================================\r\n"
/var/www/platform/artisan cannex:import death-benefits
echo -e "\r\n==[ PART 07/09 ] =======================================================\r\n"
/var/www/platform/artisan cannex:import income-benefits
echo -e "\r\n==[ PART 08/09 ] =======================================================\r\n"
/var/www/platform/artisan cannex:import text
echo -e "\r\n==[ PART 09/09 ] =======================================================\r\n"
/var/www/platform/artisan cannex:import rules
echo -e "\r\n========================================================================\r\n"

# TODO: optional cache refresh of guaranteed income for all annuities for platform sorting/ordering
#

# Dump SQL file
mysqldump -v -u alpha_web -ppassword -h db -r /var/www/docker/db-exports/alpha-mysql-export-latest.sql alpha

# Done
echo -e "\r\n\r\n"
echo "========================================================================"
echo "COMPLETE: Database refresh and export complete."
echo "========================================================================"
echo "* The latest SQL export is located in /var/www/docker/db-exports."
echo "* The database on this running docker instance has been updated-in-place."
echo "* You do not need to import the SQL dump if you plan to use this instance."
echo "* Commit the SQL export to GitHub to avoid having to do this again on another instance."