<?php 
include '../config.php';

if (!isLoggedIn() || !isAdmin()) {
    redirect('../login.php');
}

$query = "SELECT COUNT(*) as total FROM users WHERE role = 'karyawan'";
$result = $conn->query($query);
$total_karyawan = $result->fetch_assoc()['total'];

$query = "SELECT COUNT(*) as total FROM cuti WHERE status = 'pending'";
$result = $conn->query($query);
$total_cuti_pending = $result->fetch_assoc()['total'];

$query = "SELECT l.*, u.username FROM log_activity l JOIN users u ON l.user_id = u.id ORDER BY l.created_at DESC LIMIT 5";
$log_activities = $conn->query($query);

$query = "SELECT c.*, u.username, u.nama_lengkap FROM cuti c JOIN users u ON c.karyawan_id = u.id WHERE c.status = 'pending' ORDER BY c.created_at DESC LIMIT 5";
$cuti_terbaru = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - EmploraPro</title>
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
        <h2>Admin Dashboard</h2>
        
        <div class="dashboard-stats">
            <div class="stat-card">
                <h3><?= $total_karyawan ?></h3>
                <p>Total Karyawan</p>
            </div>
            
            <div class="stat-card">
                <h3><?= $total_cuti_pending ?></h3>
                <p>Cuti Perlu Persetujuan</p>
            </div>
        </div>
        
        <div class="grid-2-col">
            <div class="card">
                <div class="card-header">
                    <h3>Log Activity Terbaru</h3>
                    <a href="log_activity.php" class="btn-outline">Lihat Semua</a>
                </div>
                
                <table class="table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Aktivitas</th>
                            <th>Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($log = $log_activities->fetch_assoc()): ?>
                        <tr>
                            <td><?= $log['username'] ?></td>
                            <td><?= $log['activity'] ?></td>
                            <td><?= date('d M Y H:i', strtotime($log['created_at'])) ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
            
        <div class="card">
            <div class="card-header">
                <h3>Pengajuan Cuti Terbaru</h3>
            </div>
            <table class="table">
                <div class="table-responsive">
                <thead>
                    <tr>
                        <th>Karyawan</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($cuti = $cuti_terbaru->fetch_assoc()): ?>
                        <tr>
                            <td><?= $cuti['nama_lengkap'] ?></td>
                            <td><?= date('d M Y', strtotime($cuti['tanggal_mulai'])) ?> - <?= date('d M Y', strtotime($cuti['tanggal_selesai'])) ?></td>
                            <td><?= ucfirst($cuti['status']) ?></td>
                            <td>
                                <a href="approve_cuti.php?id=<?= $cuti['id'] ?>&status=approved" class="btn">Setujui</a>
                                <a href="approve_cuti.php?id=<?= $cuti['id'] ?>&status=rejected" class="btn-outline">Tolak</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </div>
            </table>
        </div>
    </div>
</body>
</html>