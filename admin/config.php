<?php
// HTTP
if (!defined('HTTP_SERVER')) define('HTTP_SERVER', 'http://ruplexa1.master.com.bd/admin/');
if (!defined('HTTP_CATALOG')) define('HTTP_CATALOG', 'http://ruplexa1.master.com.bd/');

// HTTPS
if (!defined('HTTPS_SERVER')) define('HTTPS_SERVER', 'https://ruplexa1.master.com.bd/admin/');
if (!defined('HTTPS_CATALOG')) define('HTTPS_CATALOG', 'https://ruplexa1.master.com.bd/');

// DIR - ADMIN SPECIFIC - these override any root config
define('DIR_APPLICATION', '/home/masterco/ruplexa1.master.com.bd/admin/');
define('DIR_SYSTEM', '/home/masterco/ruplexa1.master.com.bd/system/');
define('DIR_STORAGE', '/home/masterco/ruplexa1.master.com.bd/system/storage/');
define('DIR_LANGUAGE', '/home/masterco/ruplexa1.master.com.bd/admin/language/');
// CRITICAL: Force admin template directory even if already defined
if (defined('DIR_TEMPLATE')) {
	// Cannot redefine constant, so we'll handle this in the loader
} else {
	define('DIR_TEMPLATE', '/home/masterco/ruplexa1.master.com.bd/admin/view/template/');
}
define('DIR_CONFIG', '/home/masterco/ruplexa1.master.com.bd/system/config/');
define('DIR_IMAGE', '/home/masterco/ruplexa1.master.com.bd/image/');
define('DIR_CACHE', '/home/masterco/ruplexa1.master.com.bd/system/cache/');
define('DIR_DOWNLOAD', '/home/masterco/ruplexa1.master.com.bd/system/download/');
define('DIR_UPLOAD', '/home/masterco/ruplexa1.master.com.bd/system/upload/');
define('DIR_LOGS', '/home/masterco/ruplexa1.master.com.bd/system/logs/');
define('DIR_MODIFICATION', '/home/masterco/ruplexa1.master.com.bd/system/modification/');
define('DIR_CATALOG', '/home/masterco/ruplexa1.master.com.bd/catalog/');



// DB
define('DB_DRIVER', 'mysqli');
define('DB_HOSTNAME', 'localhost');
define('DB_USERNAME', 'masterco_rup');
define('DB_PASSWORD', 'masterco_new1');
define('DB_DATABASE', 'masterco_rup');
define('DB_PORT', '3306');
define('DB_PREFIX', 'sr_');


define("SMS_DRIVER", "shiram");