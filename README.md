# The ALPHA Platform
by Annuities Genius

---

### This is a monorepo.
This repository contains all the code for all facets of the platform.  This includes:

* The Vue.js front-end ▶ [/app](/app)
* The Laravel platform (API) ▶ [/platform](/platform)
* Static assets ▶ [/static](/static)

---

# Deployment
Here we will detail the steps to install and deploy the ALPHA platform on a fresh EC2/VPS/etc. server configuration.

## Server Preparation
Obtain a *nix server that you have full access to install packages and manage configuration files.  
> Any flavor of *nix will probably work, but most of our testing internally and on staging is done using the latest version of **Debian Linux**.

You will need to have SSH access to the server, and `sudo` access on your account, so that you can manage installed software (packages), modify configuration files, and manage the status of services running on the server.
> **IMPORTANT**
> Make sure the server you are using is **not** pre-configured as a web server.  For best results, use a server that is a base Linux installation with no extraneous network services (i.e., http, ftp, etc) pre-configured.

Make sure you are **NOT** logging in as ```root```.  You need to have your own user that does not have default superuser privileges that we will do most of the work on.  

> **If you are logging in as root, you will need to create a user for yourself to use.**  To do this, issue the following command:
> > ```sudo adduser --shell=/bin/bash --ingroup www-data your_username```
> >
> > Replace the ```your_username``` text with the username you wish to use (i.e., ```greg```).  Then, follow the on-screen instructions.
> >
> > We now need to add our user to the ```sudo``` user group so that you can execute commands as root when needed.  Issue the following command:
> > ```sudo usermod –a –G sudo your_username```
> >
> > Now, you should be able to open a new terminal session and log-in as your newly created user, using the username you replaced the ```your_username``` text with, and with the password you specified during the on-screen instructions when creating your new user.

Once you've done that, we need to prepare the environment for the platform.  Let's do a system update to get the latest package lists:
> ```sudo apt update```

You should see output similar to this:
```
Get:1 file:/etc/apt/mirrors/debian.list Mirrorlist [30 B]
Get:2 file:/etc/apt/mirrors/debian-security.list Mirrorlist [39 B]
Get:3 https://deb.debian.org/debian bookworm InRelease [151 kB]
Get:4 https://deb.debian.org/debian bookworm-updates InRelease [55.4 kB]
Get:5 https://deb.debian.org/debian bookworm-backports InRelease [56.5 kB]
Get:6 https://deb.debian.org/debian-security bookworm-security InRelease [48.0 kB]
Get:7 https://deb.debian.org/debian bookworm/main Sources [9489 kB]
Get:8 https://deb.debian.org/debian bookworm/main amd64 Packages [8786 kB]
Get:9 https://deb.debian.org/debian bookworm/main Translation-en [6109 kB]
Get:10 https://deb.debian.org/debian bookworm-updates/main Sources [17.4 kB]
Get:11 https://deb.debian.org/debian bookworm-updates/main amd64 Packages [12.7 kB]
Get:12 https://deb.debian.org/debian bookworm-updates/main Translation-en [13.8 kB]
Get:13 https://deb.debian.org/debian bookworm-backports/main Sources [199 kB]
Get:14 https://deb.debian.org/debian bookworm-backports/main amd64 Packages [188 kB]
Get:15 https://deb.debian.org/debian bookworm-backports/main Translation-en [156 kB]
Get:16 https://deb.debian.org/debian-security bookworm-security/main Sources [84.8 kB]
Get:17 https://deb.debian.org/debian-security bookworm-security/main amd64 Packages [147 kB]
Get:18 https://deb.debian.org/debian-security bookworm-security/main Translation-en [88.2 kB]
Fetched 25.6 MB in 2s (11.2 MB/s)
Reading package lists... Done
Building dependency tree... Done
Reading state information... Done
63 packages can be upgraded. Run 'apt list --upgradable' to see them.
```
Next, let's upgrade all of the installed packages on the system:
> ```sudo apt upgrade```

This will produce output similar to this:
```
Reading package lists... Done
Building dependency tree... Done
Reading state information... Done
Calculating upgrade... Done
The following NEW packages will be installed:
  firmware-linux-free linux-image-6.1.0-18-cloud-amd64
The following packages will be upgraded:
  base-files bind9-host bind9-libs curl dbus dbus-bin dbus-daemon dbus-session-bus-common dbus-system-bus-common
  debian-archive-keyring debianutils distro-info-data grub-common grub-efi-amd64-bin grub-efi-amd64-signed grub-pc-bin
  grub2-common libc-bin libc-l10n libc6 libcryptsetup12 libcurl3-gnutls libcurl4 libdbus-1-3 libgnutls30 libgssapi-krb5-2
  libk5crypto3 libkrb5-3 libkrb5support0 libnetplan0 libnghttp2-14 libnss-resolve libpam-modules libpam-modules-bin
  libpam-runtime libpam-systemd libpam0g libselinux1 libssl3 libsystemd-shared libsystemd0 libudev1 libuv1 libxml2
  linux-image-cloud-amd64 locales netplan.io openssh-client openssh-server openssh-sftp-server openssl perl-base
  python3-distro-info qemu-utils sudo systemd systemd-resolved systemd-sysv systemd-timesyncd tar tzdata udev usr-is-merged
63 upgraded, 2 newly installed, 0 to remove and 0 not upgraded.
Need to get 67.1 MB of archives.
After this operation, 102 MB of additional disk space will be used.
Do you want to continue? [Y/n] y
```
Type in "y" to confirm, and allow some time for the package manager to find, download and install upgrades to the system.

> **NOTE**
> If you see any screens or questions come up that relate to the SSH server (i.e., ```sshd_config```), or any other such questions, there is almost always a "keep the current configuration" option available.  If not, then use whatever would seem to be the default option.

---

## Server Configuration
Now that we have a nice, clean, upgraded server, let's install the packages we need to run the platform.

### Git (Code Version Control)
First, let's install ```git``` so that we can access a copy of the latest version of the ALPHA platform in a later step.

Issue the following command:
> ```sudo apt install git```

That's it for now.

### Apache (Web Server)
Next, let's install our web server, Apache.  Issue the following command:
> ```sudo apt install apache2```

That's all for now, we'll come back to this later after we have installed PHP and our database.

### RDBMS (Database Server)

---

#### PostgreSQL
> **This section is under construction. Check back soon.**

---

#### MariaDB
The platform is also fully compatible with MariaDB (or Oracle's MySQL Community Edition).  If you choose to use this database engine, follow these instructions.

First, install the server:
> ```sudo apt install mariadb-server```

Next, enter the MariaDB console by issuing the following command:
> ```sudo mariadb```

You should see something resembling this:
```
Welcome to the MariaDB monitor.  Commands end with ; or \g.
Your MariaDB connection id is 31
Server version: 10.11.6-MariaDB-0+deb12u1 Debian 12

Copyright (c) 2000, 2018, Oracle, MariaDB Corporation Ab and others.

Type 'help;' or '\h' for help. Type '\c' to clear the current input statement.

MariaDB [(none)]> 
```
> If you receive an error and your console does not show the same output as the sample above, you may need to reset the password on the root user.  To do this, follow the instructions in this article:
> 
> https://www.a2hosting.com/kb/developer-corner/mysql/reset-mysql-root-password/

Now that you have logged in to MariaDB, we need to create the ALPHA database.  To do this, issue the following command in the MariaDB console:
> ```CREATE DATABASE alpha;```

You should receive this message:
> ```Query OK, 1 row affected (0.000 sec)```

You can now exit the MariaDB console by issuing the following command:
> ```QUIT```

Next, import the staging database dump.  This is a quick-start, so that you do not need to rebuild all data from CANNEX from scratch, which takes many hours to accomplish.  In the future, a section on how to get up-and-running from scratch will be added.  

Issue the following command:
> ```wget https://alpha.staged.dev/_dumps/alpha--mysql--2024-03-01.sql.gz```

You should see output like this:
```
Resolving alpha.staged.dev (alpha.staged.dev)... 18.222.23.153
Connecting to alpha.staged.dev (alpha.staged.dev)|18.222.23.153|:443... connected.
HTTP request sent, awaiting response... 200 OK
Length: 4806332 (4.6M) [application/x-gzip]
Saving to: ‘alpha--mysql--2024-03-01.sql.gz’

alpha--mysql--2024-03-01.sql.gz 100%[======================================================>]   4.58M  11.6MB/s    in 0.4s
```
Next, we need to decompress the SQL file.  Issue the following command:
> ```gunzip alpha--mysql--2024-03-01.sql.gz```

Now, we need to import this file into our newly created ```alpha``` database.  Issue the following command:
> ```sudo mariadb --verbose alpha < alpha--mysql--2024-03-01.sql```

This should complete, and will display lots of raw table data to the console.  If you'd prefer not to see this, then you can remove the ```--verbose``` from the above command.  You will not see any "Success" or "OK" message after the import completes.  You will, however, see errors if there are any.  If you encounter an error, you may need to Google any specific error messages you're receiving for help.

Let's make sure that our database imported correctly.  To take a cursory glance at this, issue the following command:
> ```sudo mariadb -e "SHOW TABLES" alpha | more```

You should receive output that looks like this:
```
Tables_in_alpha
analysis_cache
analysis_guaranteed_cache
carriers
carriers_meta
carriers_products
carriers_products_meta
carriers_ratings
death_benefits
death_benefits_current_rider_fees
death_benefits_enhancements
death_benefits_initial_premiums
death_benefits_interest_bonus_crediting
death_benefits_interest_crediting
death_benefits_interest_multiplier_crediting
death_benefits_issue_ages_annuitant
death_benefits_issue_ages_owner
death_benefits_max_premiums
death_benefits_max_rider_fees
death_benefits_meta
death_benefits_min_rider_fees
death_benefits_persistency_credits
death_benefits_premium_bonuses
death_benefits_premium_multipliers
death_benefits_product_associations
death_benefits_roll_ups
death_benefits_states
death_benefits_step_ups
failed_jobs
income_benefits
income_benefits_current_rider_fees
income_benefits_income_deferral_periods
income_benefits_income_start_ages
[...continued]
```
This looks good, but we now need to create a user within MariaDB that the platform will use to access the database server.  To do this, first open the MariaDB console.  To do this, issue the following command:
> ```sudo mariadb```

Next, let's tell MariaDB that we want to create a new user.  To do this, issue the following command:
> ```CREATE USER 'web'@'localhost' IDENTIFIED BY 'test1234';```

In the above command, you can replace ```test1234``` with an [actual secure password](https://bitwarden.com/password-generator/).  For the purposes of this guide, we'll just stick with ```test1234```.  Please don't do this in practice.

You should receive a response of ```Query OK, 0 rows affected (0.001 sec)```.  If you don't, you may have to check the password you entered, as it may need to have a single-quote ( ```'``` ) escaped with an escape character ( ```\'``` ).

Next, we need to grant this new ```web``` account privileges to the databases on the server.  In production, you would normally grant privileges to only the databases that the account needs access to.  But in this example, to keep things simple, we will just grant access to all tables, now and future, to this ```web``` account.  To do this, issue the following command:
> ```GRANT ALL PRIVILEGES ON *.* TO 'web'@'localhost' WITH GRANT OPTION;```

Again, you should receive a response of ```Query OK, 0 rows affected (0.001 sec)```.  

Finally, issue the following command to flush the cached permissions/privileges data:
> ```FLUSH PRIVILEGES;```

You can now exit the MariaDB console by issuing the following command:
> ```QUIT```

We are now done setting up MariaDB.

---

### PHP 8.x (Interpreter)
Next, we need to install PHP 8.x, or basically the latest stable version of PHP.  To do this, we need to do a few things.
> ```sudo apt install php```

This will ask you to confirm, and you should do so.  On modern Linux distributions, this will generally install PHP 8.2.  If this were a production server, we'd want to search for dedicated PHP package archive which we can get the latest version always.

Next, let's confirm that PHP installed correctly:
> ```php --version```

You should see output like this:
```
PHP 8.2.7 (cli) (built: Jun  9 2023 19:37:27) (NTS)
Copyright (c) The PHP Group
Zend Engine v4.2.7, Copyright (c) Zend Technologies
    with Zend OPcache v8.2.7, Copyright (c), by Zend Technologies
```
If you don't, something probably went wrong, and you should try to start over from the beginning of this section, restart, or search Google for any specific error messages you are receiving.

Next, we need to install a series of extensions to expand the functionality of PHP, allowing the platform to operate properly.
> ```sudo apt install php-curl php-gd php-mbstring php-mysql php-pgsql php-soap php-xml```

This will install the following PHP modules:
* CURL (for obtaining data from remote URL's)
* GD (for image manipulation)
* MBstring (for allowing PHP to handle multi-byte character sets)
* MySQL (for allowing PHP to connect to MySQL databases)
* PGSQL (for allowing PHP to connect to PostgreSQL databases)
* SOAP (for allowing PHP to create SOAP (API) requests)
* XML (for allowing PHP to read/write and manipulate XML documents and network responses)

We're now done installing packages.  Next, we just need to obtain the latest version of the ALPHA platform, and then configure the server to serve it.

---

## Platform Configuration
Before we can do anything, we need to obtain the latest version of the ALPHA platform.  To do this, first change your working directory to the webroot directory on the server.  To do this, issue the following command:
> ```cd /var/www/html```

> **NOTE**
> If you receive an error, your webroot may be in a different place, depending on what distribution of Linux you decided to use.  Both Debian and Ubuntu use this path as the webroot, but other distributions may use home directories, or other directories entirely.

Now, let's grab a copy of ALPHA.  Issue the following command:
> ```sudo git clone https://github.com/nxlv/alpha.git```

You should see output like this:
```
Cloning into 'alpha'...
remote: Enumerating objects: 28238, done.
remote: Counting objects: 100% (2065/2065), done.
remote: Compressing objects: 100% (1124/1124), done.
remote: Total 28238 (delta 1003), reused 1842 (delta 789), pack-reused 26173
Receiving objects: 100% (28238/28238), 81.67 MiB | 29.76 MiB/s, done.
Resolving deltas: 100% (6996/6996), done.
Updating files: 100% (25835/25835), done.
```

You should now have an ```alpha``` directory in your ```/var/www/html``` directory. 

Next, we need to configure the Laravel platform with a configuration file.  By default, the configuration files are not sent up to GitHub as an operational security measure, to ensure critical passwords are not uploaded.

To configure Laravel, change your directory to the Laravel root directory.  To do this, issue the following command:
> ```cd /var/www/html/alpha/platform```

Next, create a new file called ```.env```.  To do this, we'll use the ```Nano``` editor that is included in most Linux distributions.  To do this, issue the following command:
> ```sudo nano .env```

Next, copy and paste the configuration below.
```
APP_NAME="ALPHA by Annuity Association"
APP_ENV=local
APP_KEY=base64:qr4YPfxrmQ/rMmO1JtfkWwAQOzlCHc0RofDdntNMgiY=
APP_DEBUG=true
APP_URL=http://your.staging.domain/

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=alpha
DB_USERNAME=web
DB_PASSWORD=test1234

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MEMCACHED_HOST=127.0.0.1

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_APP_CLUSTER=mt1

VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
VITE_PUSHER_HOST="${PUSHER_HOST}"
VITE_PUSHER_PORT="${PUSHER_PORT}"
VITE_PUSHER_SCHEME="${PUSHER_SCHEME}"
VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"

CANNEX_WS_DEV_USERNAME=AAUAT02
CANNEX_WS_DEV_PASSWORD=1N0FGV0K4N048QE4Y0V3RY2MJB7XPJBS
CANNEX_WS_VERSION_ID_FIA=C9AQBM
CANNEX_WS_USERNAME=AALLC02
CANNEX_WS_PASSWORD=KKCK3NN3OF9LOI59LVLS90SF9EBO4JCN
CANNEX_WS_DIGEST_TYPE=PasswordDigest
CANNEX_WS_ENDPOINT_IMMEDIATE=https://www.cannex.com/app/CANX/AntuService
CANNEX_WS_ENDPOINT_FIXED=https://www.cannex.com/app/CANX/AntyAnlyService
CANNEX_WS_ENDPOINT_ILLUSTRATION=https://www.cannex.com/app/CANX/IidAntuIllustrationService
CANNEX_WS_ENDPOINT_ILLUSTRATION_GUARANTEED=https://www.cannex.com/app/CANX/AntyEvalService
CANNEX_WS_ENDPOINT_INCOME=https://www.cannex.com/app/CANX/AntyInc1Service
```
> **NOTE**
> To copy/paste into a console, usually you will just right-click into the console after you have copied it from this page.

**Make sure** to replace the application URL at the top of the configuration file with the URL of your staging server/VPS server (the server you're currently working on).  This can also be an IP address in theory, if you do not have a domain name pointing to this server.

**Also, make sure** to replace the user and password that you configured for your chosen database engine (MariaDB or PostgreSQL).  These settings are located at the top of the configuration file, under the ```DB_``` settings.  If using MariaDB, make sure the ```DB_CONNECTION``` setting is set to ```mysql```.  If using PostgreSQL, make sure it is set to ```pgsql```.

Finally, to save and exit, press ```CONTROL + X``` on your keyboard.  At the bottom of the screen, it will ask you if you truly wish to save.  Press ```Y``` on your keyboard and the editor will save the file and close itself.

### Web Server Configuration

Lastly, we need to configure Apache to serve the platform instead of the default static webpage it ships with.  To do this, we need to locate the Apache configuration files.  On most versions of Linux, it is located in ```/etc/apache2```, however, on some distributions it will be located in ```/etc/httpd```.

First, let's change our directory to the Apache "enabled sites" configuration files directory:
> ```cd /etc/apache2/sites-enabled```

Next, let's see if there are any sites enabled currently.  There should be.  Let's see:
> ```ls -alh```

You should see output like this:
```
total 8.0K
drwxr-xr-x 2 root root 4.0K Mar 20 03:59 .
drwxr-xr-x 8 root root 4.0K Mar 20 04:40 ..
lrwxrwxrwx 1 root root   35 Mar 20 03:59 000-default.conf -> ../sites-available/000-default.conf
```
Let's edit the default file to point to our platform directory.  To do this, issue the following command:
> ```sudo nano 000-default.conf```

Next, locate the ```DocumentRoot``` configuration option.  It probably looks like this right now:
> ```DocumentRoot /var/www/html```

We need to change it so that it reads as follows:
> ```DocumentRoot /var/www/html/alpha/platform/public```

That is the only change needed right now.  To save and exit, press ```CONTROL + X``` on your keyboard.  At the bottom of the screen, it will ask you if you truly wish to save.  Press ```Y``` on your keyboard and the editor will save the file and close itself.

## Permissions
Before we can restart Apache and get the platform up-and-running, we have to apply file permissions that lock down access to the platform's files to only the user that Apache uses to operate.

First, let's figure out what user the Apache service uses.  To do this, issue the following command:
> ```sudo ps aux | grep apache```

You should see output similar to this:
```
www-data  132442  0.0  0.1 271856 12780 ?        S    00:00   0:00 /usr/sbin/apache2 -k start
www-data  132444  0.0  0.1 271856 12780 ?        S    00:00   0:00 /usr/sbin/apache2 -k start
www-data  132445  0.0  0.1 271856 12780 ?        S    00:00   0:00 /usr/sbin/apache2 -k start
www-data  132446  0.0  0.1 271856 12780 ?        S    00:00   0:00 /usr/sbin/apache2 -k start
www-data  132447  0.0  0.1 271856 12740 ?        S    00:00   0:00 /usr/sbin/apache2 -k start
www-data  132474  0.0  0.1 271856 12780 ?        S    00:05   0:00 /usr/sbin/apache2 -k start
www-data  133476  0.0  0.1 271808 11208 ?        S    02:45   0:00 /usr/sbin/apache2 -k start
```

Here, we can see, on the first column of each line, that the Apache server is using the ```www-data``` user to run, so that is the user we will set ownership of the platform's files to.

To do this, let's first assign ownership and group membership on all files in the ALPHA platform directory to the user that Apache is using (```www-data``` in this example, as determined in the step above)
> ```sudo chown -R www-data:www-data /var/www/html/alpha```

Next, we need to add our user to the ```www-data``` group so that it can access the files now owned by the Apache account (```www-data```).  Issue the following command:
> ```sudo usermod –a –G www-data your_username```
>
> Remember to replace the ```your_username``` text with your username.

Next, let's set permissions on all files in the ALPHA platform directory.  Issue the following command:
> ```sudo find /var/www/html/alpha -type f -exec chmod 644 {} \;```

Next, let's set permissions on all directories in the ALPHA platform directory.  Issue the following command:
> ```sudo find /var/www/html/alpha -type d -exec chmod 755 {} \;```

Finally, we need to give the web server **write** access to some of the caching and storage directories within Laravel.  Issue the following commands:
> ```sudo chgrp -R www-data /var/www/html/alpha/platform/storage /var/www/html/alpha/platform/bootstrap/cache```
>
> ```sudo chmod -R ug+rwx /var/www/html/alpha/platform/storage /var/www/html/alpha/platform/bootstrap/cache```

## Testing
Before we can test, we need to restart the Apache server so that the new configuration changes we made earlier take place.  Issue the following command:
> ```service apache2 restart```

> **NOTE**
> We're doing this here, because Apache would have failed to restart before we applied the proper file permissions on the platform's directories, since it wouldn't have access to read those files, as they were assigned to ```root```.

You should now be able to access the application using the URL that you specified in the configuration file above.

If it worked, you may log-in to the platform as ```admin@alpha.staged.dev``` and password ```test1234```.
