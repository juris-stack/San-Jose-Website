<?php
/**
 * Main configuration file
 * Make sure to edit this file with necessary configuration before running
 * 
 * @package SJM
 * @author 
 */

/*
 * Enable or disable PHP error reporting
 * See http://php.net/manual/en/function.error-reporting.php
 * E_ERROR | E_WARNING | E_PARSE | E_NOTICE, E_ALL, -1, 0
 * Note: Make sure to use 0 for production
 */
error_reporting( E_ALL );

/** define database constants */
define( 'DB_NAME', 'sjm-db' );
define( 'DB_USER',  'sjm-user' );
define( 'DB_PASSWORD', '12345' );
define( 'DB_HOST', 'localhost' );