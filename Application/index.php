<?php
include 'config.php';

if (isLoggedIn()) {
    redirect(isAdmin() ? 'admin/dashboard.php' : 'karyawan/dashboard.php');
} else {
    redirect('login.php');
}
?>