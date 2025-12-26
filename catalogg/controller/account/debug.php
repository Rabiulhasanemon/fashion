<?php
class ControllerAccountDebug extends Controller {
    public function index() {
        // Enable error reporting
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        ini_set('log_errors', 1);
        
        $debug_info = array();
        
        // Test database connection
        try {
            $test_query = $this->db->query("SELECT 1");
            $debug_info['database'] = 'Connected';
        } catch (Exception $e) {
            $debug_info['database'] = 'Error: ' . $e->getMessage();
        }
        
        // Test customer model
        try {
            $this->load->model('account/customer');
            $debug_info['customer_model'] = 'Loaded';
        } catch (Exception $e) {
            $debug_info['customer_model'] = 'Error: ' . $e->getMessage();
        }
        
        // Test customer object
        try {
            $customer_id = $this->customer->getId();
            $debug_info['customer_object'] = 'Available (ID: ' . ($customer_id ? $customer_id : 'Not logged in') . ')';
        } catch (Exception $e) {
            $debug_info['customer_object'] = 'Error: ' . $e->getMessage();
        }
        
        // Test POST data
        $debug_info['post_data'] = isset($_POST) && !empty($_POST) ? $_POST : 'No POST data';
        $debug_info['request_method'] = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'Unknown';
        $debug_info['request_post'] = isset($this->request->post) ? $this->request->post : 'No request->post';
        
        // Test login validation
        try {
            if (isset($this->request->post['username']) && isset($this->request->post['password'])) {
                $this->load->model('account/customer');
                $username = $this->request->post['username'];
                $login_attempts = $this->model_account_customer->getLoginAttempts($username);
                $debug_info['login_attempts'] = $login_attempts ? print_r($login_attempts, true) : 'No login attempts';
            } else {
                $debug_info['login_attempts'] = 'No username/password in POST';
            }
        } catch (Exception $e) {
            $debug_info['login_attempts'] = 'Error: ' . $e->getMessage();
        }
        
        // Test session
        try {
            $debug_info['session'] = isset($this->session) ? 'Available' : 'Not available';
        } catch (Exception $e) {
            $debug_info['session'] = 'Error: ' . $e->getMessage();
        }
        
        // Test language
        try {
            $this->load->language('account/login');
            $debug_info['language'] = 'Loaded';
        } catch (Exception $e) {
            $debug_info['language'] = 'Error: ' . $e->getMessage();
        }
        
        // Test config
        try {
            $config_url = $this->config->get('config_url');
            $config_url_str = is_array($config_url) ? print_r($config_url, true) : (string)$config_url;
            $debug_info['config'] = 'Available (URL: ' . ($config_url_str ? $config_url_str : 'Not set') . ')';
        } catch (Exception $e) {
            $debug_info['config'] = 'Error: ' . $e->getMessage();
        }
        
        // Output debug info
        header('Content-Type: text/html; charset=utf-8');
        echo '<!DOCTYPE html><html><head><title>Debug Information</title>';
        echo '<style>body{font-family:Arial;padding:20px;background:#f5f5f5;}';
        echo '.debug-box{background:white;padding:20px;margin:10px 0;border-radius:5px;box-shadow:0 2px 5px rgba(0,0,0,0.1);}';
        echo 'h1{color:#333;} .item{margin:10px 0;padding:10px;background:#f9f9f9;border-left:3px solid #007bff;}';
        echo '.error{color:red;font-weight:bold;}</style></head><body>';
        echo '<div class="debug-box"><h1>Account Debug Information</h1>';
        
        foreach ($debug_info as $key => $value) {
            $value_str = is_array($value) ? print_r($value, true) : (string)$value;
            $class = (is_string($value_str) && strpos($value_str, 'Error') !== false) ? 'error' : '';
            echo '<div class="item ' . $class . '"><strong>' . htmlspecialchars($key) . ':</strong> ' . htmlspecialchars($value_str) . '</div>';
        }
        
        echo '</div>';
        echo '<div class="debug-box"><h2>Test Login</h2>';
        echo '<form method="POST" action="index.php?route=account/login">';
        echo '<input type="text" name="username" placeholder="Email/Phone" required><br><br>';
        echo '<input type="password" name="password" placeholder="Password" required><br><br>';
        echo '<button type="submit">Test Login</button>';
        echo '</form></div>';
        
        echo '<div class="debug-box"><h2>PHP Error Log (Last 20 lines)</h2>';
        $error_log = ini_get('error_log');
        if ($error_log && file_exists($error_log)) {
            $lines = file($error_log);
            $last_lines = array_slice($lines, -20);
            echo '<pre>' . htmlspecialchars(implode('', $last_lines)) . '</pre>';
        } else {
            echo '<p>Error log not found or not configured.</p>';
        }
        echo '</div>';
        
        echo '</body></html>';
        exit;
    }
}

