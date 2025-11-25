<?php
// Direct access to admin dashboard - bypasses cPGuard redirect issues
// Use this if normal login redirect is blocked
// Access: https://ruplexa1.master.com.bd/admin/direct_access.php?token=YOUR_TOKEN

// Start session
session_start();

// Load OpenCart
require_once(__DIR__ . '/config.php');
require_once(DIR_SYSTEM . 'startup.php');

// Registry
$registry = new Registry();

// Config
$config = new Config();
$registry->set('config', $config);

// Database
$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
$registry->set('db', $db);

// Settings
$query = $db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE store_id = '0'");
foreach ($query->rows as $setting) {
	if (!$setting['serialized']) {
		$config->set($setting['key'], $setting['value']);
	} else {
		$config->set($setting['key'], unserialize($setting['value']));
	}
}

// Loader
$loader = new Loader($registry);
$registry->set('load', $loader);

// URL
$url = new Url(HTTP_SERVER, $config->get('config_secure') ? HTTPS_SERVER : HTTP_SERVER);
$registry->set('url', $url);

// Request
$request = new Request();
$registry->set('request', $request);

// Response
$response = new Response();
$response->addHeader('Content-Type: text/html; charset=utf-8');
$registry->set('response', $response);

// Session
$session = new Session();
$registry->set('session', $session);

// User
$user = new User($registry);
$registry->set('user', $user);

// Check if user is logged in
if (!$user->isLogged()) {
    // Redirect to login
    header('Location: index.php?route=common/login');
    exit;
}

// Check token
$token = isset($_GET['token']) ? $_GET['token'] : '';
if (empty($token) || !isset($session->data['token']) || $token != $session->data['token']) {
    // Generate new token
    $session->data['token'] = md5(mt_rand());
    $token = $session->data['token'];
}

// Redirect to dashboard with token
header('Location: index.php?route=common/dashboard&token=' . $token);
exit;
?>

