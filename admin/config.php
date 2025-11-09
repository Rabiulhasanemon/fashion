<?php
// HTTP
if (!defined('HTTP_SERVER')) define('HTTP_SERVER', 'http://localhost/fashion/admin/');
if (!defined('HTTP_CATALOG')) define('HTTP_CATALOG', 'http://localhost/fashion/');

// HTTPS
if (!defined('HTTPS_SERVER')) define('HTTPS_SERVER', 'http://localhost/fashion/admin/');
if (!defined('HTTPS_CATALOG')) define('HTTPS_CATALOG', 'http://localhost/fashion/');

// DIR - ADMIN SPECIFIC - these override any root config
define('DIR_APPLICATION', 'E:/xampp/htdocs/fashion/admin/');
define('DIR_SYSTEM', 'E:/xampp/htdocs/fashion/system/');
define('DIR_LANGUAGE', 'E:/xampp/htdocs/fashion/admin/language/');
// CRITICAL: Force admin template directory even if already defined
if (defined('DIR_TEMPLATE')) {
	// Cannot redefine constant, so we'll handle this in the loader
} else {
	define('DIR_TEMPLATE', 'E:/xampp/htdocs/fashion/admin/view/template/');
}
define('DIR_CONFIG', 'E:/xampp/htdocs/fashion/system/config/');
define('DIR_IMAGE', 'E:/xampp/htdocs/fashion/image/');
define('DIR_CACHE', 'E:/xampp/htdocs/fashion/system/cache/');
define('DIR_DOWNLOAD', 'E:/xampp/htdocs/fashion/system/download/');
define('DIR_UPLOAD', 'E:/xampp/htdocs/fashion/system/upload/');
define('DIR_LOGS', 'E:/xampp/htdocs/fashion/system/logs/');
define('DIR_MODIFICATION', 'E:/xampp/htdocs/fashion/system/modification/');
define('DIR_CATALOG', 'E:/xampp/htdocs/fashion/catalog/');



// DB
define('DB_DRIVER', 'mysqli');
define('DB_HOSTNAME', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_DATABASE', 'fashion');
define('DB_PORT', '3306');
define('DB_PREFIX', 'sr_');


define("SMS_DRIVER", "robi");