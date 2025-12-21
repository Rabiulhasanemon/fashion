<?php
class ControllerAccountTestRegister extends Controller {
    public function index() {
        // Enable error reporting
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        
        header('Content-Type: text/html; charset=utf-8');
        echo '<!DOCTYPE html><html><head><title>Registration Test</title>';
        echo '<style>body{font-family:Arial;padding:20px;background:#f5f5f5;}';
        echo '.test-box{background:white;padding:20px;margin:10px 0;border-radius:5px;box-shadow:0 2px 5px rgba(0,0,0,0.1);}';
        echo 'h1{color:#333;} .success{color:green;} .error{color:red;font-weight:bold;}</style></head><body>';
        echo '<div class="test-box"><h1>Registration Test</h1>';
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = isset($_POST['email']) ? $_POST['email'] : '';
            $telephone = isset($_POST['telephone']) ? $_POST['telephone'] : '';
            
            echo '<h2>Testing Registration for:</h2>';
            echo '<p>Email: ' . htmlspecialchars($email) . '</p>';
            echo '<p>Telephone: ' . htmlspecialchars($telephone) . '</p>';
            
            // Test duplicate checks
            $this->load->model('account/customer');
            
            echo '<h3>Duplicate Checks:</h3>';
            try {
                $email_check = $this->model_account_customer->getTotalCustomersByEmail($email);
                echo '<p>Email exists: ' . ($email_check > 0 ? '<span class="error">YES (' . $email_check . ')</span>' : '<span class="success">NO</span>') . '</p>';
            } catch (Exception $e) {
                echo '<p class="error">Email check error: ' . htmlspecialchars($e->getMessage()) . '</p>';
            }
            
            try {
                $telephone_check = $this->model_account_customer->getTotalCustomersByTelephone($telephone);
                echo '<p>Telephone exists: ' . ($telephone_check > 0 ? '<span class="error">YES (' . $telephone_check . ')</span>' : '<span class="success">NO</span>') . '</p>';
            } catch (Exception $e) {
                echo '<p class="error">Telephone check error: ' . htmlspecialchars($e->getMessage()) . '</p>';
            }
            
            // Test database connection
            echo '<h3>Database Test:</h3>';
            try {
                $test_query = $this->db->query("SELECT 1 as test");
                echo '<p class="success">Database connection: OK</p>';
            } catch (Exception $e) {
                echo '<p class="error">Database error: ' . htmlspecialchars($e->getMessage()) . '</p>';
            }
            
            // Test customer table structure
            echo '<h3>Table Structure:</h3>';
            try {
                $structure = $this->db->query("SHOW CREATE TABLE " . DB_PREFIX . "customer");
                if ($structure && isset($structure->row)) {
                    echo '<p class="success">Customer table exists</p>';
                    echo '<pre style="max-height:200px;overflow:auto;background:#f9f9f9;padding:10px;">' . htmlspecialchars($structure->row['Create Table']) . '</pre>';
                }
            } catch (Exception $e) {
                echo '<p class="error">Structure check error: ' . htmlspecialchars($e->getMessage()) . '</p>';
            }
        } else {
            echo '<form method="POST">';
            echo '<p><label>Email: <input type="email" name="email" required></label></p>';
            echo '<p><label>Telephone: <input type="tel" name="telephone" required></label></p>';
            echo '<p><button type="submit">Test Registration</button></p>';
            echo '</form>';
        }
        
        echo '</div></body></html>';
        exit;
    }
}



