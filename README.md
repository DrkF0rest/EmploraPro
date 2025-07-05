# EmploraPro

> [!NOTE]
>EmploraPro adalah aplikasi web sederhana yang dikembangkan sebagai proyek pertama saya dalam belajar pengembangan web. Aplikasi ini dirancang dengan fokus pada penerapan fitur CRUD (Create, Read, Update, Delete) serta antarmuka pengguna (UI) dan pengalaman pengguna (UX) yang baik.

---

## Feature

- **Fungsi CRUD Lengkap**<br>Pengguna dapat dengan mudah menambah, melihat, mengedit, dan menghapus data.
- **Desain UI/UX Intuitif**<br>Antarmuka dirancang agar mudah digunakan dan nyaman untuk dinavigasi.
- **Tata Letak Responsif**<br>Tampilan menyesuaikan berbagai ukuran layar, baik di desktop maupun perangkat mobile.

---

### How To Install

#### Local Desktop Environtment
1. Salin semua file dari folder **Application** ke direktori hosting Anda (misal: XAMPP/htdocs).
2. Buat database dengan nama **sistem_karyawan**.
3. Impor file database **(Sistem_Karyawan.sql)** ke dalam database menggunakan phpMyAdmin atau alat serupa.
4. Jalankan aplikasi melalui browser: **http://[domain-anda]/[nama-folder]**
5. Login **Username: admin Password: admin**

#### Docker
 
1. Clone repository:
    ```bash
    git clone https://github.com/username/sistem-karyawan.git
    cd sistem-karyawan 
    ```
    <br>
2. Jalankan container:
    ```bash
    docker-compose up -d --build
    ```
    Tunggu hingga semua service berjalan 
    <br>
> [!IMPORTANT]
> 3. Impor file database **(Sistem_Karyawan.sql)** ke dalam database menggunakan phpMyAdmin
> - Jika anda ingin membuat password pada database edit pada config.php
> - Jika anda menggunakan docker tambahkan juga pada docker-compose.yaml

  4. Login **Username: admin Password: admin**

#### Akses Aplikasi

>[!TIP]
>Aplikasi  :	**http://[domain-anda]**<br>phpmyadmin  :	**http:// [domain-anda]:8080**	user: root, password: (kosong/disesuaikan)

---
## Made With

![My Skills](https://skillicons.dev/icons?i=php,js,mysql,html,css,docker)
