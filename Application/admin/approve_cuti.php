<?php
include '../config.php';

if (!isLoggedIn() || !isAdmin()) {
    redirect('../login.php');
}

if (!isset($_GET['id']) || !isset($_GET['status'])) {
    redirect('dashboard.php');
}

$id = (int)$_GET['id'];
$status = $_GET['status'] == 'approved' ? 'approved' : 'rejected';

$query = "SELECT c.*, u.nama_lengkap, u.username 
          FROM cuti c 
          JOIN users u ON c.karyawan_id = u.id 
          WHERE c.id = $id";
$cuti = $conn->query($query)->fetch_assoc();

if (!$cuti) {
    redirect('dashboard.php');
}

$update_query = "UPDATE cuti SET status = '$status' WHERE id = $id";
if ($conn->query($update_query)) {
    $admin_id = $_SESSION['user_id'];
    $activity = "Mengubah status cuti {$cuti['nama_lengkap']} (ID: $id) menjadi $status";
    $conn->query("INSERT INTO log_activity (user_id, activity) VALUES ($admin_id, '$activity')");
    
    $_SESSION['success'] = "Status cuti {$cuti['nama_lengkap']} berhasil diubah menjadi " . ucfirst($status);
} else {
    $_SESSION['error'] = "Gagal mengubah status cuti: " . $conn->error;
}

$redirect_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'dashboard.php';
redirect($redirect_url);
?>