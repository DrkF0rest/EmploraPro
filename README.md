<h1 align="center">EmploraPro</h1>

---
> [!NOTE]
>EmploraPro adalah aplikasi web sederhana yang dikembangkan sebagai proyek pertama saya dalam belajar pengembangan web. Aplikasi ini dirancang dengan fokus pada penerapan fitur CRUD (Create, Read, Update, Delete) serta antarmuka pengguna (UI) dan pengalaman pengguna (UX) yang baik.

---

<h3>✨ Feature</h3>

- **Fungsi CRUD Lengkap**<br>Pengguna dapat dengan mudah menambah, melihat, mengedit, dan menghapus data.
- **Desain UI/UX Intuitif**<br>Antarmuka dirancang agar mudah digunakan dan nyaman untuk dinavigasi.
- **Tata Letak Responsif**<br>Tampilan menyesuaikan berbagai ukuran layar, baik di desktop maupun perangkat mobile.

---

## How To Install

#### Local Desktop Environtment
- Salin semua file dari folder **Application** ke direktori hosting Anda (misal: XAMPP/htdocs).
- Buat database dengan nama **sistem_karyawan**.
- Impor file database **(Sistem_Karyawan.sql)** ke dalam database menggunakan phpMyAdmin atau alat serupa.
- Jalankan aplikasi melalui browser: **http://[domain-anda]/[nama-folder]**
- Login **Username: admin Password: admin**

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

> [!TIP]
> Jika anda ingin membuat password edit pada config.php
> Jika anda menggunakan docker tambahkan juga pada docker-compose.yaml

#### Akses Aplikasi

Aplikasi  :	**http://[domain-anda]**
phpmyadmin  :	**http:// [domain-anda]:8080**	user: root, password: (kosong/disesuaikan)

---
<h3 align="left">Made With</h3>

<div align="left">
  <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/php/php-original.svg" height="20" alt="php logo"  />
  <img width="12" />
  <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/html5/html5-original.svg" height="20" alt="html5 logo"  />
  <img width="12" />
  <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/css3/css3-original.svg" height="20" alt="css3 logo"  />
  <img width="12" />
  <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/javascript/javascript-original.svg" height="20" alt="javascript logo"  />
</div>
