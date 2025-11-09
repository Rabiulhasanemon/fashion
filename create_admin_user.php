<?php
// Create a new admin user with full permissions (assigned to Administrator group)
// Usage (optional custom creds): create_admin_user.php?u=username&p=password
// SECURITY: Delete this file after successful run.

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('config.php');

header('Content-Type: text/html; charset=utf-8');
echo '<h2>Create Admin User</h2>';

// Read desired username/password from query or use defaults
$new_username = isset($_GET['u']) && $_GET['u'] !== '' ? preg_replace('/[^a-zA-Z0-9_\-]/','', $_GET['u']) : 'flashadmin';
$new_password_plain = isset($_GET['p']) && $_GET['p'] !== '' ? $_GET['p'] : 'Flash@2025!';

try {
    $db = new mysqli(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);
    if ($db->connect_error) throw new Exception('DB connection failed: ' . $db->connect_error);
    echo '<p style="color:green">✓ Connected to database</p>';

    $prefix = DB_PREFIX;

    // Ensure user table exists and primary key is AUTO_INCREMENT; fix any id=0
    $exists = $db->query("SHOW TABLES LIKE '{$prefix}user'");
    if (!$exists || $exists->num_rows === 0) {
        $sqlCreate = "CREATE TABLE `{$prefix}user` (
            `user_id` int(11) NOT NULL AUTO_INCREMENT,
            `user_group_id` int(11) NOT NULL,
            `username` varchar(20) NOT NULL,
            `password` varchar(40) NOT NULL,
            `salt` varchar(9) NOT NULL,
            `firstname` varchar(32) NOT NULL,
            `lastname` varchar(32) NOT NULL,
            `email` varchar(96) NOT NULL,
            `image` varchar(255) NOT NULL,
            `code` varchar(40) NOT NULL,
            `ip` varchar(40) NOT NULL,
            `status` tinyint(1) NOT NULL,
            `date_added` datetime NOT NULL,
            PRIMARY KEY (`user_id`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";
        if (!$db->query($sqlCreate)) throw new Exception('Failed to create user table: ' . $db->error);
        echo '<p style="color:green">✓ User table created</p>';
    }

    // Ensure user_id is auto_increment
    $col = $db->query("SHOW COLUMNS FROM `{$prefix}user` LIKE 'user_id'");
    if ($col && $row = $col->fetch_assoc()) {
        if (strpos($row['Extra'], 'auto_increment') === false) {
            if (!$db->query("ALTER TABLE `{$prefix}user` MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT")) {
                throw new Exception('Failed to set AUTO_INCREMENT on user_id: ' . $db->error);
            }
            echo '<p style="color:green">✓ Fixed AUTO_INCREMENT on user_id</p>';
        }
    }

    // Fix any record with user_id=0 and reset AUTO_INCREMENT
    $hasZero = $db->query("SELECT user_id FROM `{$prefix}user` WHERE user_id = 0");
    if ($hasZero && $hasZero->num_rows > 0) {
        $maxRow = $db->query("SELECT IFNULL(MAX(user_id),0) AS m FROM `{$prefix}user`")->fetch_assoc();
        $newId = (int)$maxRow['m'] + 1;
        if (!$db->query("UPDATE `{$prefix}user` SET user_id = {$newId} WHERE user_id = 0")) {
            throw new Exception('Failed to adjust user_id=0: ' . $db->error);
        }
        $db->query("ALTER TABLE `{$prefix}user` AUTO_INCREMENT = " . ($newId + 1));
        echo '<p style="color:green">✓ Adjusted existing user with ID 0 to ' . ($newId) . ' and reset AUTO_INCREMENT</p>';
    }

    // Ensure user_group table has Administrator (id=1)
    $res = $db->query("SELECT user_group_id FROM `{$prefix}user_group` WHERE user_group_id = 1");
    if (!$res || $res->num_rows === 0) {
        // Create a permissive Administrator group with broad permissions
        $permissions = array(
            'access' => array(
                'common/dashboard','common/column_left','common/menu','common/header','common/footer','common/login','common/logout',
                'extension/module','extension/extension','extension/installer','extension/modification',
                'module/*','catalog/*','design/*','user/*','sale/*','marketing/*','localisation/*','setting/*'
            ),
            'modify' => array(
                'common/dashboard','extension/module','extension/extension','extension/installer','extension/modification',
                'module/*','catalog/*','design/*','user/*','sale/*','marketing/*','localisation/*','setting/*'
            )
        );
        $perm_serialized = $db->real_escape_string(serialize($permissions));
        $db->query("INSERT INTO `{$prefix}user_group` SET user_group_id=1, name='Administrator', permission='{$perm_serialized}'");
        echo '<p style="color:green">✓ Administrator group created</p>';
    } else {
        echo '<p style="color:green">✓ Administrator group exists</p>';
    }

    // If user exists, update password; else insert new user
    $safe_username = $db->real_escape_string($new_username);
    $check = $db->query("SELECT user_id FROM `{$prefix}user` WHERE username = '{$safe_username}'");

    // OpenCart 1.5-style salted SHA1
    $salt = substr(md5(uniqid(rand(), true)), 0, 9);
    $password_hash = sha1($salt . sha1($salt . sha1($new_password_plain)));

    if ($check && $check->num_rows > 0) {
        $row = $check->fetch_assoc();
        $user_id = (int)$row['user_id'];
        $sql = "UPDATE `{$prefix}user` SET password='{$password_hash}', salt='{$salt}', status=1, user_group_id=1 WHERE user_id={$user_id}";
        if (!$db->query($sql)) throw new Exception('Failed to update user: ' . $db->error);
        echo '<p style="color:green">✓ Updated existing user: <strong>' . htmlspecialchars($new_username) . '</strong></p>';
    } else {
        $now = date('Y-m-d H:i:s');
        $sql = "INSERT INTO `{$prefix}user` SET ".
               "user_group_id=1, username='{$safe_username}', password='{$password_hash}', salt='{$salt}', ".
               "firstname='Admin', lastname='User', email='admin+" . $db->real_escape_string($new_username) . "@localhost', image='', code='', ip='127.0.0.1', status=1, date_added='{$now}'";
        if (!$db->query($sql)) throw new Exception('Failed to insert user: ' . $db->error);
        echo '<p style="color:green">✓ Created new admin user: <strong>' . htmlspecialchars($new_username) . '</strong></p>';
    }

    echo '<div style="margin-top:10px;padding:10px;border:1px solid #ddd">';
    echo '<p><strong>Login URL:</strong> <a href="' . htmlspecialchars(HTTP_SERVER) . 'admin/" target="_blank">' . htmlspecialchars(HTTP_SERVER) . 'admin/</a></p>';
    echo '<p><strong>Username:</strong> ' . htmlspecialchars($new_username) . '</p>';
    echo '<p><strong>Password:</strong> ' . htmlspecialchars($new_password_plain) . '</p>';
    echo '<p style="color:#b91c1c"><strong>Important:</strong> After logging in and confirming access, delete this file: <code>create_admin_user.php</code></p>';
    echo '</div>';

} catch (Exception $e) {
    echo '<p style="color:red">✗ Error: ' . htmlspecialchars($e->getMessage()) . '</p>';
}

?>


