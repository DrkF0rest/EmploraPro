<?php
include '../config.php';

if (!isLoggedIn() || isAdmin()) {
    redirect('../login.php');
}

$user_id = $_SESSION['user_id'];
$error = '';
$success = '';

// Ambil data user
$query = "SELECT * FROM users WHERE id = $user_id";
$user = $conn->query($query)->fetch_assoc();

// Proses update profil
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    $nama_lengkap = $conn->real_escape_string($_POST['nama_lengkap']);
    
    // Update data
    $query = "UPDATE users SET nama_lengkap = '$nama_lengkap' WHERE id = $user_id";
    
    if ($conn->query($query)) {
        $success = 'Profil berhasil diupdate!';
    } else {
        $error = 'Gagal mengupdate profil: ' . $conn->error;
    }
}

// Proses update password
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_password'])) {
    $password_lama = $_POST['password_lama'];
    $password_baru = $_POST['password_baru'];
    $konfirmasi_password = $_POST['konfirmasi_password'];
    
    // Validasi password
    if (!password_verify($password_lama, $user['password'])) {
        $error = 'Password lama salah!';
    } elseif ($password_baru != $konfirmasi_password) {
        $error = 'Password baru dan konfirmasi password tidak sama!';
    } else {
        // Update password
        $password_hash = password_hash($password_baru, PASSWORD_DEFAULT);
        $query = "UPDATE users SET password = '$password_hash' WHERE id = $user_id";
        
        if ($conn->query($query)) {
            $success = 'Password berhasil diubah!';
        } else {
            $error = 'Gagal mengubah password: ' . $conn->error;
        }
    }
}

// Proses upload foto profil
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['upload_foto'])) {
    if (isset($_FILES['foto_profil']) && $_FILES['foto_profil']['error'] == UPLOAD_ERR_OK) {
        $file = $_FILES['foto_profil'];
        
        // Validasi file
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $max_size = 2 * 1024 * 1024; // 2MB
        
        if (!in_array($file['type'], $allowed_types)) {
            $error = 'Hanya file JPG, PNG, atau GIF yang diizinkan!';
        } elseif ($file['size'] > $max_size) {
            $error = 'Ukuran file maksimal 2MB!';
        } else {
            // Generate nama file unik
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = 'profile_' . $user_id . '_' . time() . '.' . $ext;
            $upload_path = '../assets/images/profiles/' . $filename;
            
            if (move_uploaded_file($file['tmp_name'], $upload_path)) {
                // Hapus foto lama jika ada
                if ($user['profile_picture']) {
                    $old_file = '../assets/images/profiles/' . basename($user['profile_picture']);
                    if (file_exists($old_file)) {
                        unlink($old_file);
                    }
                }
                
                // Update database
                $query = "UPDATE users SET profile_picture = '$filename' WHERE id = $user_id";
                
                if ($conn->query($query)) {
                    $success = 'Foto profil berhasil diupload!';
                    // Refresh data user
                    $user = $conn->query("SELECT * FROM users WHERE id = $user_id")->fetch_assoc();
                } else {
                    $error = 'Gagal menyimpan informasi foto profil!';
                }
            } else {
                $error = 'Gagal mengupload file!';
            }
        }
    } else {
        $error = 'Silakan pilih file foto profil!';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil - EmploraPro</title>
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
                <h2>Profil Saya</h2>
            </div>
            
            <?php if ($error): ?>
                <div class="alert error"><?= $error ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert success"><?= $success ?></div>
            <?php endif; ?>
            
            <div class="profile-section">
                <div class="profile-picture">
                    <?php if ($user['profile_picture']): ?>
                        <img src="../assets/images/profiles/<?= $user['profile_picture'] ?>" alt="Foto Profil">
                    <?php else: ?>
                        <div class="default-avatar">
                            <?= strtoupper(substr($user['nama_lengkap'], 0, 1)) ?>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" enctype="multipart/form-data" class="upload-form">
                        <input type="file" name="foto_profil" id="foto_profil" accept="image/*" required>
                        <label for="foto_profil">Pilih Foto</label>
                        <span class="file-name">Belum ada file dipilih</span>
                        <button type="submit" name="upload_foto" class="btn">Upload Foto</button>
                    </form>
                </div>
                
                <div class="profile-info">
                    <form method="POST">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" id="username" value="<?= $user['username'] ?>" readonly>
                        </div>
                        
                        <div class="form-group">
                            <label for="nama_lengkap">Nama Lengkap</label>
                            <input type="text" id="nama_lengkap" name="nama_lengkap" value="<?= $user['nama_lengkap'] ?>" required>
                        </div>
                        
                        <button type="submit" name="update_profile" class="btn">Simpan Perubahan</button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h3>Ubah Password</h3>
            </div>
            
            <form method="POST">
                <div class="form-group">
                    <label for="password_lama">Password Lama</label>
                    <input type="password" id="password_lama" name="password_lama" required>
                </div>
                
                <div class="form-group">
                    <label for="password_baru">Password Baru</label>
                    <input type="password" id="password_baru" name="password_baru" required>
                </div>
                
                <div class="form-group">
                    <label for="konfirmasi_password">Konfirmasi Password Baru</label>
                    <input type="password" id="konfirmasi_password" name="konfirmasi_password" required>
                </div>
                
                <button type="submit" name="update_password" class="btn">Ubah Password</button>
            </form>
        </div>
    </div>
</body>
</html>