<?php
session_start();

// Koneksi database
$host = 'localhost';
$user = 'root';
// Jika menggunakan docker, pastikan password sesuai dengan yang ada di docker-compose.yaml
$pass = '';
$db   = 'sistem_karyawan';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

function redirect($url) {
    header("Location: $url");
    exit();
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] == 'admin';
}

function getUserData() {
    global $conn;
    if (!isLoggedIn()) return null;
    
    $user_id = $_SESSION['user_id'];
    $query = "SELECT * FROM users WHERE id = $user_id";
    $result = $conn->query($query);
    
    return $result->fetch_assoc();
}
?>