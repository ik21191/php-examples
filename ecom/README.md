# aitechkart.com
A custom website and mobile development company.

## Steps to run this application

### Prerequisite

**PHP Version:** 8.4.22


- Make sure the `.htaccess` file is present in the root folder. If the `.htaccess` file is not present in the root folder, then make it using the below contents with the name `.htaccess` . In this `.htaccess` file, we are routing all the traffic to the `index.php` file, and from the `index.php` file we are sending the request to the correct handler. So, `index.php` file is working as the `Front Controller` of this application.

```
RewriteEngine On
RewriteBase /

# Do not route requests if the file or directory actually exists
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d

# Route everything else to index.php
RewriteRule ^ index.php [L,QSA]
```

- You must be present in the `root` folder of this application and then open the command prompt and run the below command.

```
php -S localhost:8000
```
## Application logs
Logs are written in the `logs` folder which is present in the parallel of the main application folder.

## Packages installed in php folder
To install any package go to `php` folder and install the package there and use accordingly.

- composer require phpmailer/phpmailer
- composer require monolog/monolog

## Secrets
Create a `.env` file and put in the root folder of this application. For your convenience a `.env-sample` is placed in the `root` folder. In the production server replace sample value with the actual one. In case you add a new property in `.env` file, then make sure to add a placeholder in `.env-sample` file.

## PDO MySql Error
The error **Uncaught PDOException: could not find driver** means, PHP is trying to connect to a database using **PHP Data Objects (PDO)**, but the specific database extension is either not installed or not enabled in your PHP configuration.

To enable `PDO MySql` extension in your `php`, follow below steps.

- Open your `php.ini` configuration file 
- search for your database driver `;extension=pdo_mysql`
- Remove the semicolon `(;)` from the start of the line to uncomment and enable it. The uncommented line should like below.

```
iniextension=pdo_mysql
```