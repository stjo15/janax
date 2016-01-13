
# This is how you setup Janax Forum Framework! #

## Installation ##

1. Clone or download the [Janax repository](https://github.com/stjo15/janax) from GitHub to the htdocs/webroot of your server/domain/host.

2. By default, Janax supports MySQL database integration. You will need to setup your 
database if you didn't already. Open Janax/app/config/confiq_mysql.php in your text editor and change
'myusername', 'mypassword', 'localhost' and 'mydbname' according to your environment.

3. Import all .sql files in db-tables/* to your database.

4. If you have problems with the theme or CSS, you may need to change the user rights 
of Janax/webroot/css/stalle-grid/ folder to 777. Delete the cache file, reload the page and
you should be good to go!

5. Immediately after installation, open up the application in a web browser, go to 'My Profile'
in the menu, choose 'Register new user' and create the admin account with username 'admin'.

## Basic configuration ##

1. Open Janax/app/config/theme_me.php in your code editor and change values of the siteTitle, siteTagline and title_append properties.

2. Paste your own logo image over Janax/webroot/img/logo.png and favicon image over Janax/webroot/favicon.png.

3. Open Janax/webroot/config.php in your code editor and look for this code:

        /**
        * Define mail settings and configure mail
        *
        */
        // Exchange your admin mail account address
        define('ADMIN_MAIL', 'admin@mydomain.com'); 

        // Exchange mailserver settings for recovery mail
        ini_set('SMTP', 'mailout.mymailserver.com'); 
        ini_set('smtp_port', '25'); 
        ini_set('sendmail_from', ADMIN_MAIL); 

Exchange the parameters 'admin@mydomain.com', 'mailout.mymailserver.com' and port number to your 
accounts' mail server options. If unsure, ask your web service provider / web host. These 
settings are important for sending automatic emails to your users, like password recovery.

Finally, to customize the CSS style of your application, go to Janax/webroot/css/stalle-grid/
folder and open up navbar_janax.less, structure.less, typography.less and variables.less.

## Content editing ##

1. To change the content of the sidebar, open Janax/app/view/question/sidebar.tpl.php

2. To change the content of the welcome text, open Janax/app/view/welcome/welcome.tpl.php

3. To change the content of the 'About', 'Documentation', 'Privacy' or 'How To Use' pages, 
go to Janax/app/content/ and edit the corresponding .md files.

4. To remove or add new pages ('views' or 'routes'), add them in Janax/webroot/index.php. 

5. To make changes in the navigation menu, open Janax/app/config/navbar_janax.php.

/ Janax creator