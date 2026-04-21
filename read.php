<?php
require_once(__DIR__."/config.php");
setCSRF_token();
if (isset($_GET['action']) && $_GET['action'] === 'get_content' && is_authenticated()) {
    $file = $_GET['file'] ?? '';
    $rel_path = $_GET['path'] ?? '';
    $full_path = realpath(ROOT_PATH . DS . $rel_path . DS . $file);

    if ($full_path && strpos($full_path, ROOT_PATH) === 0 && is_file($full_path)) {
        echo file_get_contents($full_path);
    } else {
        echo "";
    }
    exit;
}else{
  header("location: index.php");
 exit; 
}
