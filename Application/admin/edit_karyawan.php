<?php
include '../config.php';

if (!isLoggedIn() || !isAdmin()) {
    redirect('../login.php');
}

if (!isset($_GET['id'])) {
    redirect('karyawan.php');
}

$id = (int)$_GET['id'];
$error = '';
$success = '';

$query = "SELECT u.*, k.jabatan, k.departemen, k.tanggal_masuk 
          FROM users u 
          JOIN karyawan k ON u.id = k.user_id 
          WHERE u.id = $id AND u.role = 'karyawan'";
$karyawan = $conn->query($query)->fetch_assoc();

if (!$karyawan) {
    redirect('karyawan.php');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $conn->real_escape_string($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $nama_lengkap = $conn->real_escape_string($_POST['nama_lengkap']);
    $jabatan = $conn->real_escape_string($_POST['jabatan']);
    $departemen = $conn->real_escape_string($_POST['departemen']);
    $tanggal_masuk = $_POST['tanggal_masuk'];
    
    $conn->begin_transaction();
    
    try {
        $conn->query("UPDATE users SET 
                     nama_lengkap = '$nama_lengkap' 
                     WHERE id = $id");

        $conn->query("UPDATE karyawan SET 
                     jabatan = '$jabatan', 
                     departemen = '$departemen', 
                     tanggal_masuk = '$tanggal_masuk'
                     WHERE user_id = $id");
        
        $admin_id = $_SESSION['user_id'];
        $activity = "Mengupdate data karyawan: $nama_lengkap";
        $conn->query("INSERT INTO log_activity (user_id, activity) VALUES ($admin_id, '$activity')");
        
        $conn->commit();
        $success = 'Data karyawan berhasil diupdate!';
        
        $karyawan = $conn->query($query)->fetch_assoc();
    } catch (Exception $e) {
        $conn->rollback();
        $error = 'Gagal mengupdate karyawan: ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Karyawan - EmploraPro</title>
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
                <h2>Edit Data Karyawan</h2>
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
                    <input type="text" id="username" value="<?= $karyawan['username'] ?>" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Kosongkan jika tidak mengubah password">
                </div>
                
                <div class="form-group">
                    <label for="nama_lengkap">Nama Lengkap</label>
                    <input type="text" id="nama_lengkap" name="nama_lengkap" value="<?= $karyawan['nama_lengkap'] ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="jabatan">Jabatan</label>
                    <input type="text" id="jabatan" name="jabatan" value="<?= $karyawan['jabatan'] ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="departemen">Departemen</label>
                    <input type="text" id="departemen" name="departemen" value="<?= $karyawan['departemen'] ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="tanggal_masuk">Tanggal Masuk</label>
                    <input type="date" id="tanggal_masuk" name="tanggal_masuk" value="<?= $karyawan['tanggal_masuk'] ?>" required>
                </div>
                
                <button type="submit" class="btn">Simpan Perubahan</button>
            </form>
        </div>
    </div>
</body>
</html>