<?php
include '../config.php';

if (!isLoggedIn() || isAdmin()) {
    redirect('../login.php');
}

$user_id = $_SESSION['user_id'];

// Ambil data log app
$query = "SELECT * FROM log_app WHERE user_id = $user_id ORDER BY login_time DESC";
$log_apps = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Aplikasi - EmploraPro</title>
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
        <div class="card">
            <div class="card-header">
                <h2>Riwayat Login/Logout</h2>
            </div>
            
            <table class="table">
                <thead>
                    <tr>
                        <th>Login Time</th>
                        <th>Logout Time</th>
                        <th>Durasi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($log = $log_apps->fetch_assoc()): ?>
                    <tr>
                        <td><?= date('d M Y H:i:s', strtotime($log['login_time'])) ?></td>
                        <td>
                            <?= $log['logout_time'] ? date('d M Y H:i:s', strtotime($log['logout_time'])) : 'Masih Login' ?>
                        </td>
                        <td>
                            <?php if ($log['logout_time']): 
                                $diff = strtotime($log['logout_time']) - strtotime($log['login_time']);
                                echo gmdate('H:i:s', $diff);
                            endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>