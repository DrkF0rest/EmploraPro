<?php
include '../config.php';

if (!isLoggedIn() || !isAdmin()) {
    redirect('../login.php');
}

$filter_user = isset($_GET['user']) ? (int)$_GET['user'] : '';
$filter_tanggal = isset($_GET['tanggal']) ? $_GET['tanggal'] : '';

$query = "SELECT l.*, u.username, u.nama_lengkap 
          FROM log_activity l 
          JOIN users u ON l.user_id = u.id 
          WHERE 1=1";

if ($filter_user) {
    $query .= " AND l.user_id = $filter_user";
}

if ($filter_tanggal) {
    $query .= " AND DATE(l.created_at) = '$filter_tanggal'";
}

$query .= " ORDER BY l.created_at DESC";

$log_activities = $conn->query($query);

$users = $conn->query("SELECT id, username, nama_lengkap FROM users ORDER BY nama_lengkap");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Activity - EmploraPro</title>
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
                <h2>Log Activity</h2>
            </div>
            <form method="GET" class="filter-form">
                <div class="form-group">
                    <label for="user">Filter User</label>
                    <select id="user" name="user">
                        <option value="">Semua User</option>
                        <?php while ($user = $users->fetch_assoc()): ?>
                        <option value="<?= $user['id'] ?>" <?= $filter_user == $user['id'] ? 'selected' : '' ?>>
                            <?= $user['nama_lengkap'] ?> (<?= $user['username'] ?>)
                        </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="tanggal">Filter Tanggal</label>
                    <input type="date" id="tanggal" name="tanggal" value="<?= $filter_tanggal ?>">
                </div>
                
                <button type="submit">Filter</button>
                <a href="log_activity.php" class="btn-outline">Reset</a>
            </form>
            
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
                        <td>
                            <strong><?= $log['nama_lengkap'] ?></strong><br>
                            <small><?= $log['username'] ?></small>
                        </td>
                        <td><?= $log['activity'] ?></td>
                        <td><?= date('d M Y H:i', strtotime($log['created_at'])) ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>