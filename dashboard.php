<?php
session_start();
require 'login_session.php';

// Mendapatkan informasi pengguna
$user = $_SESSION['user'];
include('head.php');
include('sidebar.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard HRD</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fontawesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="/LAT_HRD/CSS/dashboard.css">
</head>
<body>
    <!-- Content -->
    <div class="content-wrapper">
    <!-- Informasi penting HRD -->
    <div class="row">
        <div class="col-md-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-users"></i> Total Pegawai</h5>
                    <p class="card-text">150 Pegawai</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-trophy"></i> Penghargaan Bulan Ini</h5>
                    <p class="card-text">5 Penghargaan</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-exclamation-triangle"></i> Peringatan Aktif</h5>
                    <p class="card-text">3 Peringatan</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-white bg-danger">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-plane-departure"></i> Cuti yang Berjalan</h5>
                    <p class="card-text">10 Pegawai</p>
                </div>
            </div>
        </div>
    </div>

    <div class="additional-content">
        <img src="/LAT_HRD/Foto/hrd_gambar.jpg" alt="Welcome Image" class="img-fluid w-100" style="height: auto; object-fit: cover;">
    </div>
    <!-- Tambahkan konten lainnya di sini -->
    </div>
    
    <!-- Include Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>

<?php
include('footer.php');
?>