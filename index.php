<?php
session_start();
require 'config.php';
require 'login_session.php';

$iduser = $_SESSION['iduser'];

// Ambil data user dari database
$stmt = $conn->prepare("SELECT username, foto FROM login WHERE iduser = ?");
$stmt->bind_param("i", $iduser);
$stmt->execute();   
$stmt->bind_result($username, $foto);
$stmt->fetch();
$stmt->close();

// Ambil data nama usaha dan alamat dari database
$stmt = $conn->prepare("SELECT nama, alamat FROM namausaha LIMIT 1");
$stmt->execute();
$stmt->bind_result($namaUsaha, $alamatUsaha);
$stmt->fetch();
$stmt->close();

// Ambil banyak pegawai dari database
$result = $conn->query("SELECT COUNT(*) as count FROM pegawai");
$row = $result->fetch_assoc();
$jmlhpeg = $row['count'];

// Ambil tgl awal dan akhir
$month = intval(date('m'));
$year = intval(date('Y'));
if ($month < 12) {
    $endmonth = $month+1;
    $endyear = $year;
} else {
    $endmonth = 1;
    $endyear = $year=+1;
}
$startdate = "$year-$month-01";
$enddate = "$endyear-$endmonth-01";

// Ambil jumlah penghargaan bulan ini berdasarkan tanggal surat
$sql = "SELECT COUNT(*) as count FROM penghargaan WHERE tanggal >= '$startdate' AND tanggal < '$enddate'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$jmlhpeng = $row['count'];

// Ambil jumlah peringatan bulan ini berdasarkan tanggal surat
$sql = "SELECT COUNT(*) as count FROM peringatan WHERE tanggal >= '$startdate' AND tanggal < '$enddate'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$jmlhper = $row['count'];

// Ambil jumlah cuti bulan ini berdasarkan tanggal surat
$sql = "SELECT COUNT(*) as count FROM cuti WHERE tanggal >= '$startdate' AND tanggal < '$enddate'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$jmlhcut = $row['count'];

?>

<?php require 'head.php'; ?>
<div class="wrapper">
    <header>
        <h4 style="text-align:center;"><?php echo htmlspecialchars($namaUsaha ?? ''); ?></h4>
        <p style="text-align:center;"><?php echo htmlspecialchars($alamatUsaha ?? ''); ?></p>
    </header>


    <?php include 'sidebar.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index</title>
    <link rel="stylesheet" href="/LAT_HRD/CSS/index.css">
</head>
<body>
    
    <div class="content" id="content">
            <div class="container-fluid mt-3">
                <div class="cards-container">
                    <!-- Card 1: Total Pegawai -->
                    <div class="card card-tipe">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="card-title">Total Pegawai</h5>
                                    <h4><p><?php echo "$jmlhpeg";?></p></h4>
                            </div>
                            <div class="card-icon-wrapper">
                                <i class="fas fa-users card-icon"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Penghargaan -->
                <div class="card card-stok">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5>Penghargaan</h5>
                                <h4><p><?php echo "$jmlhpeng";?></p></h4>
                            </div>
                            <div class="card-icon-wrapper">
                                <i class="fas fa-award card-icon"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Peringatan -->
                <div class="card card-merek">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5>Peringatan</h5>
                                <h4><p><?php echo "$jmlhper";?></p></h4>
                            </div>
                            <div class="card-icon-wrapper">
                                <i class="fas fa-exclamation-triangle card-icon"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 4: Cuti -->
                <div class="card card-polis">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5>Cuti</h5>
                                <h4><p><?php echo "$jmlhcut";?></p></h4>
                            </div>
                            <div class="card-icon-wrapper">
                                <i class="fas fa-calendar-alt card-icon"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Full-width card for Aplikasi Kepegawaian -->
            <div class="full-width-card">`
                <div class="card w-100">
                    <div class="card-header"><strong>Aplikasi Kepegawaian</strong></div>
                    <img src="/LAT_HRD/gambar/hrd_gambar.jpg" class="img-fluid" style="display:block; margin:auto;">
                </div>
            </div>
        </div>
    </div>

    <?php require 'footer.php'; ?>
</div>

</body>
</html>