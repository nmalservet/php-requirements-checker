# php-requirements-checker
php-requirements-checker is a php script to integrate in each php software to check if the requirements are good

## How to use
You need to copy the two files php-requirements-checker.php and my-requirements.php into your php application
An other method will be to use composer to get the last stable release. Go to the composer section to do it.


## Add your own libraries, folders and file to check
You need to add your own libraries to check, and folders and file. To do it, edit the file my-requirements.php.


## Launch your custom php requirements checker :
Via the web page : http://localhost/myapp/php-requirements-checker.php

##The result
You will have a result in an html , like the example below 

![](https://cloud.githubusercontent.com/assets/7512899/18478975/8560e356-79d3-11e6-891c-cb3da42fed14.png)


#Optional section : Install via Composer
Composer is a powerfull tool o install external libraries. If you need to know how composer works, go on this page : https://getcomposer.org/


To add this library to your installation via composer, you only need to add "nmalservet/php-requirements-checker" to your "require" section into your composer.json as example below :
```javascript
"require": {
        "nmalservet/php-requirements-checker":"*",
        ...
    },
```

Then, run the command "composer update".
Composer will install the library into your "vendor" folder probably.

You need to create your own configuration and call the script from your installation.
 First copy the file my-requirements.php into your folder, for example "my-own-requirements.php".
Open a file, for example my-checker.php then include the configuration file and the script.
```PHP
include ('my-own-requirements.php');
require(__DIR__ . '/../vendor/nmalservet/php-requirements-checker/php-requirements-checker.php');
```

So to check if your requirements are installed, you need to use this url:
http://localhost/myapp/my-checker.php


#Optional section : protect your script to check requirements with a restricted access
To protect your script, create an .htaccess file into the directory of your "my-checker.php".
Then copy the following lines :
```<FilesMatch "my-checker.php">
AuthName "Admin Only"
AuthType Basic
AuthUserFile /var/www/.passwd
require valid-user
</FilesMatch> ```

You will need to create a file .passwd into /var/www.
To do it use toe command line tool htpasswd : https://httpd.apache.org/docs/current/programs/htpasswd.html
