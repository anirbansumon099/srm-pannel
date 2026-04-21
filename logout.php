<?php
require_once(__DIR__."/config.php");
setCSRF_token();

if (isset($_GET['signin'])) {
    logout();
}