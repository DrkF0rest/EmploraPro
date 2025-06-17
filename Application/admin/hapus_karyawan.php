<?php
include '../config.php';

if (!isLoggedIn() || !isAdmin()) {
    redirect('../login.php');
}

if (!isset($_GET['id'])) {
    redirect('karyawan.php');
}

$id = (int)$_GET['id'];

$query = "SELECT nama_lengkap FROM users WHERE id = $id";
$karyawan = $conn->query($query)->fetch_assoc();

if ($karyawan) {
    $conn->begin_transaction();
    
    try {
        $conn->query("DELETE FROM users WHERE id = $id");
        
        $admin_id = $_SESSION['user_id'];
        $activity = "Menghapus karyawan: " . $karyawan['nama_lengkap'];
        $conn->query("INSERT INTO log_activity (user_id, activity) VALUES ($admin_id, '$activity')");
        
        $conn->commit();
        $_SESSION['success'] = 'Karyawan berhasil dihapus!';
    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['error'] = 'Gagal menghapus karyawan: ' . $e->getMessage();
    }
}

redirect('karyawan.php');
?>