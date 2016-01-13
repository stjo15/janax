JANAX Forum Framework 
=========

# About Janax Forum Framework #

The Janax Framework is a ready-to-use PHP MVC (Model View Controller) Framework,
complete with a default responsive theme and jQuery/JavaScript integration. 
The theme and default setup is designed to be used as a automobile or autosport 
forum or Q&A page, although you can exchange the built-in racing game or question tags 
as you wish, to better fit the needs of your project. It lets your website visitor 
register as a user, create a profile, ask questions or answer questions. The user 
can also write news articles by default. The requirements to write an article can be 
changed at your discretion, however. The user can also play a simple HTML5 Canvas 
racing game which is included, and save the laptimes and number of laps to be displayed in
the profile. This allows users to compete against each other for the best laptime!

All this comes with modern and discrete jQuery animations to give your visitors a great experience!


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



License
------------------

This software is free software and carries a MIT license.



Use of external libraries
-----------------------------------

The following external modules are included and subject to its own license.



### Modernizr
* Website: http://modernizr.com/
* Version: 2.6.2
* License: MIT license
* Path: included in `webroot/js/modernizr.js`



### PHP Markdown
* Website: http://michelf.ca/projects/php-markdown/
* Version: 1.4.0, November 29, 2013
* License: PHP Markdown Lib Copyright © 2004-2013 Michel Fortin http://michelf.ca/
* Path: included in `3pp/php-markdown`

```
 .  
..:  Copyright (c) 2013 - 2015 Staffan Johansson, stalle.johansson@gmail.com
```
