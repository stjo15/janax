<?php
/**
 * Sample configuration file for Anax webroot.
 *
 */ 

/**
 * Define essential Anax paths, end with /
 *
 */
define('ANAX_INSTALL_PATH', realpath(__DIR__ . '/../') . '/');
define('ANAX_APP_PATH',     ANAX_INSTALL_PATH . 'app/');


/**
 * Define db options
 *
 */
define('DB_OPTIONS', ANAX_APP_PATH . 'config/config_mysql.php'); 

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

/**
 * Include autoloader and database settings.
 *
 */
include(ANAX_APP_PATH . 'config/autoloader.php'); 

/**
 * Include global functions.
 *
 */
include(ANAX_INSTALL_PATH . 'src/functions.php'); 

