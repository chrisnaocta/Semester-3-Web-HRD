<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplikasi HRD</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fontawesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="/LAT_HRD/CSS/sidebar.css">
</head>
<body>
<!-- Sidebar -->

<div class="sidebar">
        <div class="user-info">
            <!-- Menggunakan path relatif untuk foto pengguna -->
            <img src="/LAT_HRD/Foto/orang_1.jpg" alt="User Photo">
            <p><?php echo htmlspecialchars($user['username']); ?></p>
        </div>

        <h4>Menu</h4>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link active" href="#"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#"><i class="fas fa-briefcase"></i> Nama Perusahaan</a>
                <!--Ganti ikon -->
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#"><i class="fas fa-building"></i> Departemen</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#"><i class="fas fa-user-tie"></i> Jabatan</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#"><i class="fas fa-users"></i> Kepegawaian</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#"><i class="fas fa-exclamation-triangle"></i> Peringatan</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#"><i class="fas fa-trophy"></i> Penghargaan</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#"><i class="fas fa-calendar-alt"></i> Izin</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#"><i class="fas fa-plane-departure"></i> Cuti</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="#" data-toggle="collapse" data-target="#laporanSubMenu">
                    <i class="fas fa-file-alt"></i> Laporan
                </a>
                <ul id="laporanSubMenu" class="submenu collapse">
                    <li><a class="nav-link" href="#">Cetak Pegawai</a></li>
                    <li><a class="nav-link" href="#">Cetak Penghargaan</a></li>
                    <li><a class="nav-link" href="#">Cetak Peringatan</a></li>
                    <li><a class="nav-link" href="#">Cetak Izin</a></li>
                    <li><a class="nav-link" href="#">Cetak Cuti</a></li>
                </ul>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="#"><i class="fas fa-user-cog"></i> Pengaturan User</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="logout.php" onclick="return confirm('Apakah Anda yakin ingin logout?');">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </li>
        </ul>
    </div>
</body>
</html>