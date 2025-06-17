<?php
include 'config.php';

if (isLoggedIn() && !isAdmin() && isset($_SESSION['log_app_id'])) {
    $log_app_id = $_SESSION['log_app_id'];
    $conn->query("UPDATE log_app SET logout_time = NOW() WHERE id = $log_app_id");
}

session_unset();
session_destroy();


redirect('login.php');
?>