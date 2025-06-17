<?php
include '../config.php';

if (!isLoggedIn() || !isAdmin()) {
    redirect('../login.php');
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $conn->real_escape_string($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $nama_lengkap = $conn->real_escape_string($_POST['nama_lengkap']);
    $jabatan = $conn->real_escape_string($_POST['jabatan']);
    $departemen = $conn->real_escape_string($_POST['departemen']);
    $tanggal_masuk = $_POST['tanggal_masuk'];
    
    $check = $conn->query("SELECT id FROM users WHERE username = '$username'");
    
    if ($check->num_rows > 0) {
        $error = 'Username sudah digunakan!';
    } else {
        $conn->begin_transaction();
        
        try {
            $conn->query("INSERT INTO users (username, password, role, nama_lengkap) 
                         VALUES ('$username', '$password', 'karyawan', '$nama_lengkap')");
            
            $user_id = $conn->insert_id;
            
            $conn->query("INSERT INTO karyawan (user_id, jabatan, departemen, tanggal_masuk) 
                         VALUES ($user_id, '$jabatan', '$departemen', '$tanggal_masuk')");
            
            $admin_id = $_SESSION['user_id'];
            $activity = "Menambah karyawan baru: $nama_lengkap";
            $conn->query("INSERT INTO log_activity (user_id, activity) VALUES ($admin_id, '$activity')");
            
            $conn->commit();
            $success = 'Karyawan berhasil ditambahkan!';
        } catch (Exception $e) {
            $conn->rollback();
            $error = 'Gagal menambahkan karyawan: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Karyawan - EmploraPro</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="../assets/js/script.js" defer></script>
</head>
<body>
    <nav class="navbar">
        <h1>EmploraPro</h1>
        <button class="burger-btn" id="burgerBtn">
            <span class="burger-line"></span>
            <span class="burger-line"></span>
            <span class="burger-line"></span>
        </button>
        <div class="nav-links" id="navLinks">
            <a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
            <a href="karyawan.php"><i class="fas fa-users"></i> Karyawan</a>
            <a href="log_activity.php"><i class="fas fa-history"></i> Logs</a>
            <a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </nav>

    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2>Tambah Karyawan Baru</h2>
                <a href="karyawan.php" class="btn-outline">Kembali</a>
            </div>
            
            <?php if ($error): ?>
                <div class="alert error"><?= $error ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert success"><?= $success ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <div class="form-group">
                    <label for="nama_lengkap">Nama Lengkap</label>
                    <input type="text" id="nama_lengkap" name="nama_lengkap" required>
                </div>
                
                <div class="form-group">
                    <label for="jabatan">Jabatan</label>
                    <input type="text" id="jabatan" name="jabatan" required>
                </div>
                
                <div class="form-group">
                    <label for="departemen">Departemen</label>
                    <input type="text" id="departemen" name="departemen" required>
                </div>
                
                <div class="form-group">
                    <label for="tanggal_masuk">Tanggal Masuk</label>
                    <input type="date" id="tanggal_masuk" name="tanggal_masuk" required>
                </div>
                
                <button type="submit" class="btn">Simpan</button>
            </form>
        </div>
    </div>
</body>
</html>