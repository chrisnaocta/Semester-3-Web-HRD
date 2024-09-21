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
        <a href="profile.php">
        <img src="foto/<?php echo htmlspecialchars($foto); ?>" alt="User Photo" class="user-photo">
        <p class="user-name"><?php echo htmlspecialchars($username); ?></p>
        </a>
    </div>
    <ul>
        <li><a href="index.php"><span><i class="fas fa-home"></i> Home</span></a></li>
        <li><a href="profile.php"><span><i class="fas fa-user"></i> Profile</span></a></li>
        <li><a href="namausaha.php"><span><i class="fas fa-building"></i> Identitas Usaha</span></a></li>
        <li>
            <a href="#" class="menu-toggle"><span><i class="fas fa-users"></i> Master</span><i class="fas fa-chevron-right arrow"></i></a>
            <ul class="sub-menu">
                <li><a href="departemen.php"><span>Departemen</span></a></li>
                <li><a href="jabatan.php"><span>Jabatan</span></a></li>
                <li><a href="pegawai.php"><span>Kepegawaian</span></a></li>
            </ul>
        </li>
        <li>
            <a href="#" class="menu-toggle"><span><i class="fas fa-exchange-alt"></i> Transaksi</span><i class="fas fa-chevron-right arrow"></i></a>
            <ul class="sub-menu">
                <li><a href="Peringatan.php"><span>Peringatan</span></a></li>
                <li><a href="#"><span>Penghargaan</span></a></li>
                <li><a href="#"><span>Izin</span></a></li>
                <li><a href="#"><span>Cuti</span></a></li>
            </ul>
        </li>
        <li>
            <a href="#" class="menu-toggle"><span><i class="fas fa-chart-line"></i> Report</span><i class="fas fa-chevron-right arrow"></i></a>
            <ul class="sub-menu">
                <li><a href="print_pembiayaan.php"><span>Pegawai</span></a></li>
                <li><a href="print_proyek.php"><span>Peringatan</span></a></li>
                <li><a href="print_ajuster.php"><span>Penghargaan</span></a></li>
                <li><a href="print_kategori.php"><span>Izin</span></a></li>
                <li><a href="print_tipe.php"><span>Cuti</span></a></li>
            </ul>
        </li>
        <li><a href="logout.php"><span><i class="fas fa-sign-out-alt"></i> Logout</span></a></li>
    </ul>
    <div class="toggle-sidebar">
        <i class="fas fa-bars"></i>
    </div>
</div>

</body>
</html>