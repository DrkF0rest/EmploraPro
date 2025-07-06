<?php 
include '../config.php';

if (!isLoggedIn() || isAdmin()) {
    redirect('../login.php');
}

$user_id = $_SESSION['user_id'];

$query = "SELECT * FROM users WHERE id = $user_id";
$karyawan = $conn->query($query)->fetch_assoc();

$query = "SELECT * FROM cuti WHERE karyawan_id = $user_id ORDER BY created_at DESC LIMIT 1";
$cuti_terakhir = $conn->query($query)->fetch_assoc();

$bulan_ini = date('Y-m');
$query = "SELECT COUNT(*) as hadir FROM absensi WHERE karyawan_id = $user_id AND status = 'hadir' AND DATE_FORMAT(tanggal, '%Y-%m') = '$bulan_ini'";
$hadir_bulan_ini = $conn->query($query)->fetch_assoc()['hadir'];

$query = "SELECT l.*, u.username FROM log_activity l JOIN users u ON l.user_id = u.id WHERE l.user_id = $user_id ORDER BY l.created_at DESC LIMIT 5";
$log_activities = $conn->query($query);

$conn->query("INSERT INTO log_app (user_id, login_time) VALUES ($user_id, NOW())");
$_SESSION['log_app_id'] = $conn->insert_id;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Karyawan Dashboard - EmploraPro</title>
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
                <a href="cuti.php"><i class="fas fa-calendar-day"></i> Cuti</a>
                <a href="absensi.php"><i class="fas fa-clipboard-list"></i> Absensi</a>
                <a href="profile.php"><i class="fas fa-user-circle"></i> Profile</a>
                <a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
    </nav>

    <div class="container">
        <h2>Selamat datang, <?= $karyawan['nama_lengkap'] ?>!</h2>
        
        <div class="dashboard-stats">
            <div class="stat-card">
                <h3><?= $hadir_bulan_ini ?></h3>
                <p>Hadir Bulan Ini</p>
            </div>
            
            <?php if ($cuti_terakhir): ?>
            <div class="stat-card">
                <h3><?= ucfirst($cuti_terakhir['status']) ?></h3>
                <p>Status Cuti Terakhir</p>
            </div>
            <?php endif; ?>
        </div>
        
        <div class="grid-2-col">
            <div class="card">
                <div class="card-header">
                    <h3>Informasi Cuti</h3>
                    <a href="cuti.php" class="btn-outline">Lihat Semua</a>
                </div>
                
                <div class="cutiLog">
                    <?php if ($cuti_terakhir): ?>
                    <p>
                        <strong>Tanggal:</strong> <?= date('d M Y', strtotime($cuti_terakhir['tanggal_mulai'])) ?> - <?= date('d M Y', strtotime($cuti_terakhir['tanggal_selesai'])) ?>
                    </p>
                    <p>
                        <strong>Alasan:</strong> <?= $cuti_terakhir['alasan'] ?>
                    </p>
                    <?php else: ?>
                    <p>Anda belum pernah mengajukan cuti</p>
                    <?php endif; ?>
                </div>
                
                <a href="cuti.php" class="btn">Ajukan Cuti Baru</a>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h3>Absensi Terakhir</h3>
                    <a href="absensi.php" class="btn-outline">Lihat Semua</a>
                </div>
                
                <?php 
                $query = "SELECT * FROM absensi WHERE karyawan_id = $user_id ORDER BY tanggal DESC LIMIT 1";
                $absensi_terakhir = $conn->query($query)->fetch_assoc();
                ?>
                
                <?php if ($absensi_terakhir): ?>
                <p>
                    <strong>Tanggal:</strong> <?= date('d M Y', strtotime($absensi_terakhir['tanggal'])) ?>
                </p>
                <p>
                    <strong>Status:</strong> <?= ucfirst($absensi_terakhir['status']) ?>
                </p>
                <?php if ($absensi_terakhir['status'] == 'hadir'): ?>
                <p>
                    <strong>Jam Masuk:</strong> <?= date('H:i', strtotime($absensi_terakhir['jam_masuk'])) ?>
                </p>
                <p>
                    <strong>Jam Pulang:</strong> <?= $absensi_terakhir['jam_pulang'] ? date('H:i', strtotime($absensi_terakhir['jam_pulang'])) : 'Belum' ?>
                </p>
                <?php endif; ?>
                <?php else: ?>
                <p>Belum ada data absensi</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="grid-2-col">
            <div class="card">
                <div class="card-header">
                    <h3>Log Activity Terbaru</h3>
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

    </div>
</body>
</html>