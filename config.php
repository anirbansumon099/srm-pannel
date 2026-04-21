<?php
if (session_status() === PHP_SESSION_NONE) {
    if (!@session_start()) {
        $custom_session_dir = __DIR__ . '/.sessions';
        
        if (!is_dir($custom_session_dir)) {
            mkdir($custom_session_dir, 0777, true);
            // সিকিউরিটির জন্য .htaccess ফাইল তৈরি (যাতে কেউ সেশন ফাইল না দেখতে পারে)
            file_put_contents($custom_session_dir . '/.htaccess', "Deny from all");
        }
    
        session_save_path($custom_session_dir);
        session_start();
    }
}




#==============[APP NAME]================#
define('APP_NAME', 'File Manager PRO');
#===========[DIRECTORY_SEPARATOR=========#
define('DS', DIRECTORY_SEPARATOR);
#========[ROOT PATH & REAL PPATH=========#
define('ROOT_PATH', realpath(__DIR__ . DS . '../../..')); 
define('PANEL_REAL_PATH', realpath(__DIR__));
define('APP_DIR_NAME', basename(__DIR__)); #==========[ACCESS CREDENTIALS]=======#
define('PASS_FILE', __DIR__ . DS . '.cp-core-an.access_config.php');

#==============[TIME ZZONE===============#

date_default_timezone_set('Asia/Dhaka');


#========[FUNCTION SECTIONS]=============#

function setCSRF_token(){
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
}

function verify_csrf_token($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

function is_authenticated() {
    if (!isset($_SESSION['auth']) || $_SESSION['auth'] !== true) {
        return false;
    }


    $current_user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
    $current_ip = $_SERVER['REMOTE_ADDR'] ?? '';

    if (!isset($_SESSION['user_agent']) || !isset($_SESSION['user_ip'])) {

        return false;
    }

    if ($_SESSION['user_agent'] !== $current_user_agent || $_SESSION['user_ip'] !== $current_ip) {
        session_unset();
        session_destroy();
        return false;
    }

    if (!isset($_SESSION['last_regeneration'])) {
        $_SESSION['last_regeneration'] = time();
    } elseif (time() - $_SESSION['last_regeneration'] > 1800) { // ৩০ মিনিট পর পর
        session_regenerate_id(true);
        $_SESSION['last_regeneration'] = time();
    }

    return true;
}




function logout() {
    $_SESSION = array();
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 42000, '/');
    }
    session_destroy();
    header("Location: index.php");
    exit;
}





