<?php
include '../config.php';

if (!isLoggedIn() || isAdmin()) {
    redirect('../login.php');
}

$user_id = $_SESSION['user_id'];
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['ajukan_cuti'])) {
    $tanggal_mulai = $_POST['tanggal_mulai'];
    $tanggal_selesai = $_POST['tanggal_selesai'];
    $alasan = $conn->real_escape_string($_POST['alasan']);
    
    if (strtotime($tanggal_selesai) < strtotime($tanggal_mulai)) {
        $error = 'Tanggal selesai harus setelah tanggal mulai';
    } else {
        $query = "INSERT INTO cuti (karyawan_id, tanggal_mulai, tanggal_selesai, alasan) 
                 VALUES ($user_id, '$tanggal_mulai', '$tanggal_selesai', '$alasan')";
        
        if ($conn->query($query)) {
            $activity = "Mengajukan cuti dari $tanggal_mulai sampai $tanggal_selesai";
            $conn->query("INSERT INTO log_activity (user_id, activity) VALUES ($user_id, '$activity')");
            
            $success = 'Pengajuan cuti berhasil dikirim!';
        } else {
            $error = 'Gagal mengajukan cuti: ' . $conn->error;
        }
    }
}

$query = "SELECT * FROM cuti WHERE karyawan_id = $user_id ORDER BY created_at DESC";
$daftar_cuti = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengajuan Cuti - Sistem Karyawan</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="../assets/js/script.js" defer></script>
</head>
<body>
    <nav class="navbar">
        <h1>Sistem Karyawan</h1>
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
        <div class="card">
            <div class="card-header">
                <h2>Pengajuan Cuti</h2>
            </div>
            
            <?php if ($error): ?>
                <div class="alert error"><?= $error ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert success"><?= $success ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label for="tanggal_mulai">Tanggal Mulai</label>
                    <input type="date" id="tanggal_mulai" name="tanggal_mulai" required>
                </div>
                
                <div class="form-group">
                    <label for="tanggal_selesai">Tanggal Selesai</label>
                    <input type="date" id="tanggal_selesai" name="tanggal_selesai" required>
                </div>
                
                <div class="form-group">
                    <label for="alasan">Alasan Cuti</label>
                    <textarea id="alasan" name="alasan" rows="3" required></textarea>
                </div>
                
                <button type="submit" name="ajukan_cuti" class="btn">Ajukan Cuti</button>
            </form>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h3>Riwayat Cuti</h3>
            </div>
            
            <table class="table">
                <thead>
                    <tr>
                        <th>Tanggal Mulai</th>
                        <th>Tanggal Selesai</th>
                        <th>Alasan</th>
                        <th>Status</th>
                        <th>Tanggal Pengajuan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($cuti = $daftar_cuti->fetch_assoc()): ?>
                    <tr>
                        <td><?= date('d M Y', strtotime($cuti['tanggal_mulai'])) ?></td>
                        <td><?= date('d M Y', strtotime($cuti['tanggal_selesai'])) ?></td>
                        <td><?= $cuti['alasan'] ?></td>
                        <td>
                            <span class="status-badge <?= $cuti['status'] ?>">
                                <?= ucfirst($cuti['status']) ?>
                            </span>
                        </td>
                        <td><?= date('d M Y H:i', strtotime($cuti['created_at'])) ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>