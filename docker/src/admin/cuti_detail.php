<?php
include '../config.php';

if (!isLoggedIn() || !isAdmin()) {
    redirect('../login.php');
}

$karyawan_id = (int)$_GET['id'];

$karyawan = $conn->query("SELECT * FROM users WHERE id = $karyawan_id")->fetch_assoc();

$cuti = $conn->query("SELECT * FROM cuti WHERE karyawan_id = $karyawan_id ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuti Detail - EmploraPro</title>
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
        <h2>Riwayat Cuti: <?= $karyawan['nama_lengkap'] ?></h2>
        
        <div class="card">
            <table class="table">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Alasan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($c = $cuti->fetch_assoc()): ?>
                    <tr>
                        <td><?= date('d M Y', strtotime($c['tanggal_mulai'])) ?> - <?= date('d M Y', strtotime($c['tanggal_selesai'])) ?></td>
                        <td><?= $c['alasan'] ?></td>
                        <td><span class="status-badge <?= $c['status'] ?>"><?= ucfirst($c['status']) ?></span></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        
        <a href="karyawan.php" class="btn">Kembali ke Daftar Karyawan</a>
    </div>
</body>
</html>