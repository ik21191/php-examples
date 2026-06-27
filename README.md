# PHP installation and setup

## Important

When you install or download `PHP`, it does not come with an active **php.ini** file by default. Instead, it provides **php.ini-development** and **php.ini-production** as templates. PHP will completely ignore both of these files until you create a dedicated `php.ini` file.

- php.ini-development : Use this for local coding, testing, and debugging. It is pre-configured to output detailed errors, warnings, and debugging notices directly to your web browser screen, making it much easier to fix broken code

- php.ini-production : Use this for live websites facing the public. It suppresses on-screen error displays to protect sensitive database information or path structures from being exposed to hackers, while silently logging errors into a private file.

- Rename one of the file as per your environment with exactly `php.ini` and restart the server.

## Enable MySql in PHP

- Locate and open your `php.ini` configuration file.
- Use Ctrl + F to find the line `;extension=mysqli`
- Remove the semicolon `(;)` from the beginning of the line to uncomment it.
- Find `;extension_dir = "ext"` and ensure it is also uncommented.
- Save the file and restart your `PHP Server`.

## Error Logs
How to Find Your Specific Log Path. 

The absolute fastest way to verify where your logs are saving is to check your live/dev environment settings:

- Via PHP Code
	Run `echo ini_get('error_log');` in a test script to print the exact destination path.
- Via Info Page(Recommended)
	Create a file with `<?php phpinfo(); ?>` and look for the `error_log` directive row. If your `phpinfo()` or `ini_get('error_log')` command displays `no value (blank)` for the error_log directive, it means PHP is defaulting to its Server Application Programming Interface (SAPI) logger.
	
	When unconfigured, errors are automatically sent straight to your web server's primary error stream (like Apache's error.log or Nginx's error.log) or to `stderr if you are using the Command Line Interface (CLI)`.
	
	To fix this and explicitly route your errors to a dedicated file, follow these 
	
	1. Update your `php.ini` File, Locate your active `php.ini` file. Ensure you uncomment (remove the leading semicolon ;) and update the following directives to turn logging on and specify a path.
	
	```
	inilog_errors = On
	error_log = /var/log/php_errors.log
	log_verbosity_level=4
	```
	
	**Note: ** For Windows systems, use a path format like `C:\php\logs\php_errors.log`. 
	
- Restart the server and check if `phpinfo()` is displaying your log path location.

- Write logs: To write an informational message to a PHP log file, you can use the built-in PHP error_log() Function like below

```
<? error_log("This is infor message"); ?>
```

## Error: The error "Call to undefined function curl_init()" 

means that the cURL extension is disabled in your XAMPP configuration. PHP uses cURL to communicate with the Razorpay API servers, so it must be enabled in your settings.You can fix this inside your XAMPP setup by following these steps:

- Open PHP (php.ini), usually present in `/php/php.ini` location.
- Press `Ctrl + F` and search for the word `;extension=curl`
- Remove the semicolon `(;)` at the beginning of the line to uncomment `extension=curl`
- Save the file (Ctrl + S) and close Notepad and restart php server.


1. Go to the PHP for Windows Download Page.
- Download the `Thread Safe ZIP` file for the latest version.
- Extract the ZIP file into a new folder named `C:\php` on your computer.
- Open your `Start Menu`, search for **Edit the system environment variables**, and open it.

- Click **Environment Variables**, find the **Path** variable under **System variables**, and click `Edit`.

- Click `New`, paste `C:\php`, and click `OK` on all windows to save.

2. Verify the Installation

- To confirm `PHP` is working, open your terminal (Command Prompt, PowerShell, or Terminal App) and run

```
php -v
```

If configured correctly, this command will print the installed PHP version.

3. Write and Run Your First `PHP` Script

- PHP code must always be enclosed inside below tag.
```
<?php  
//code
....
//code
?>
```

- Open a text editor (like Notepad or Visual Studio Code) and create a new file and paste the following.

```
<?php
echo "Hello, World!";
?>
```
- Save the file exactly as `index.php`.

    - Run via Command Line

        - Open your terminal, navigate to the folder where you saved the file `index.php`, and `run` .
            ```
            php index.php
            ```

        The terminal will display: `Hello, World!`

        - Run via Web Browser (Built-in Server)

            PHP has a built-in **web server** perfect for local development. In your terminal, inside the folder where your file lives, `run` .
            ```
            php -S localhost:8000
            ```

            Open any web browser and go to [http://localhost:8000](http://localhost:8000). You will see `Hello, World!` displayed on the screen.


# Composer installation and setup
- Download and Run the Installer

    Open your terminal or command prompt, navigate to your desired directory, and run the following combined command.

    ```
    php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
    php composer-setup.php
    php -r "unlink('composer-setup.php');"
    ```

- What these commands do

    **copy(...)** Downloads the official installation script from the Composer Download Page.

    **php composer-setup.php** Executes the installer script to check your PHP settings and output the execution file (composer.phar).

    **unlink(...)** Deletes the temporary installer script to keep your folder clean.

- Check composer version using below command on new command terminal

```
composer -V
```

- Output will be like 

```
Composer version 2.10.0 2026-05-28 11:22:08
PHP version 8.4.22 (D:\Study\php-8.4.22\php.exe)
Run the "diagnose" command to get more detailed diagnostics output.
```