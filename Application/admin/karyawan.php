<?php 
include '../config.php';

if (!isLoggedIn() || !isAdmin()) {
    redirect('../login.php');
}

$query = "SELECT * FROM users WHERE role = 'karyawan' ORDER BY created_at DESC";
$karyawan = $conn->query($query);

$user_id = $_SESSION['user_id'];
$activity = "Melihat daftar karyawan";
$conn->query("INSERT INTO log_activity (user_id, activity) VALUES ($user_id, '$activity')");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Karyawan - EmploraPro</title>
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
        <div class="card-header">
            <h2>Data Karyawan</h2>
        </div>
            
            <?php if (isset($_GET['success'])): ?>
                <div class="alert success"><?= $_GET['success'] ?></div>
                <?php endif; ?>
                
        <div class="card">
            <a href="tambah_karyawan.php" class="btn">Tambah Karyawan</a>
            <?php while ($row = $karyawan->fetch_assoc()): ?>
            <a href="cuti_detail.php?id=<?= $row['id'] ?>" class="btn-outline">Lihat Riwayat Cuti</a>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Nama Lengkap</th>
                        <th>Tanggal Dibuat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                    <tbody>
                            <tr>
                                <td><?= $row['id'] ?></td>
                                <td><?= $row['username'] ?></td>
                                <td><?= $row['nama_lengkap'] ?></td>
                                <td><?= date('d M Y', strtotime($row['created_at'])) ?></td>
                                <div class="act-btn">    
                                    <td>
                                        <a href="edit_karyawan.php?id=<?= $row['id'] ?>" class="btn">Edit</a>
                                        <a href="hapus_karyawan.php?id=<?= $row['id'] ?>" class="btn-outline" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                                    </td>
                                </div>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>