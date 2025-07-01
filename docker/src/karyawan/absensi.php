<?php
include '../config.php';

if (!isLoggedIn() || isAdmin()) {
    redirect('../login.php');
}

$user_id = $_SESSION['user_id'];
$today = date('Y-m-d');

$query = "SELECT * FROM absensi WHERE karyawan_id = $user_id AND tanggal = '$today'";
$absensi_hari_ini = $conn->query($query)->fetch_assoc();

// Check-in
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['check_in'])) {
    if (!$absensi_hari_ini) {
        $jam_masuk = date('H:i:s');
        $conn->query("INSERT INTO absensi (karyawan_id, tanggal, jam_masuk, status) 
                     VALUES ($user_id, '$today', '$jam_masuk', 'hadir')");
        $activity = "Check-in pada " . date('H:i', strtotime($jam_masuk));
        $conn->query("INSERT INTO log_activity (user_id, activity) VALUES ($user_id, '$activity')");
        
        $_SESSION['success'] = 'Check-in berhasil!';
        redirect('absensi.php');
    } else {
        $_SESSION['error'] = 'Anda sudah check-in hari ini';
        redirect('absensi.php');
    }
}

// Check-out 
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['check_out'])) {
    if ($absensi_hari_ini && !$absensi_hari_ini['jam_pulang']) {
        $jam_pulang = date('H:i:s');
        $conn->query("UPDATE absensi SET jam_pulang = '$jam_pulang' 
                     WHERE karyawan_id = $user_id AND tanggal = '$today'");
        
        // Catat log activity
        $activity = "Check-out pada " . date('H:i', strtotime($jam_pulang));
        $conn->query("INSERT INTO log_activity (user_id, activity) VALUES ($user_id, '$activity')");
        
        $_SESSION['success'] = 'Check-out berhasil!';
        redirect('absensi.php');
    } else {
        $_SESSION['error'] = $absensi_hari_ini ? 'Anda sudah check-out' : 'Anda belum check-in';
        redirect('absensi.php');
    }
}

$query = "SELECT * FROM absensi 
          WHERE karyawan_id = $user_id AND DATE_FORMAT(tanggal, '%Y-%m') = '$today'
          ORDER BY tanggal DESC";
$absensi = $conn->query($query);

$today = date('Y-m-d');
$query = "SELECT id, jam_masuk, jam_pulang FROM absensi 
          WHERE karyawan_id = $user_id AND tanggal = '$today'";
$today_absensi = $conn->query($query)->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absensi - Sistem Karyawan</title>
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
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert error"><?= $_SESSION['error'] ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert success"><?= $_SESSION['success'] ?></div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>
        
        <div class="card">
            <div class="card-header">
                <h2>Absensi Hari Ini</h2>
            </div>
            
            <div class="card-body">
                <?php if (isset($_SESSION['error'])): ?>
                <div class="alert error"><?= $_SESSION['error'] ?></div>
                <?php unset($_SESSION['error']); ?>
                <?php endif; ?>
                
                <?php if (isset($_SESSION['success'])): ?>
                <div class="alert success"><?= $_SESSION['success'] ?></div>
                <?php unset($_SESSION['success']); ?>
                <?php endif; ?>
                
                <div class="absensi-actions">
                <?php if (!$absensi_hari_ini): ?>
                    <form method="POST">
                    <button type="submit" name="check_in">
                        <i class="fas fa-fingerprint"></i> Check-in
                    </button>
                    </form>
                
                <?php elseif ($absensi_hari_ini && !$absensi_hari_ini['jam_pulang']): ?>
                    <div class="flex gap-md">
                    <span class="checked-in">
                        <i class="fas fa-check-circle"></i> Check-in: <?= date('H:i', strtotime($absensi_hari_ini['jam_masuk'])) ?>
                    </span>
                    <form method="POST">
                        <button type="submit" name="check_out" class="btn-outline">
                        <i class="fas fa-sign-out-alt"></i> Check-out
                        </button>
                    </form>
                    </div>
                
                <?php else: ?>
                    <div class="checked-out">
                    <i class="fas fa-clipboard-check"></i> 
                    Anda sudah check-in (<?= date('H:i', strtotime($absensi_hari_ini['jam_masuk'])) ?>) 
                    dan check-out (<?= date('H:i', strtotime($absensi_hari_ini['jam_pulang'])) ?>)
                    </div>
                <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>