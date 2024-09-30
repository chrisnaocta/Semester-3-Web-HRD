<?php
session_start();
require 'config.php';
require 'login_session.php';

// Cek apakah metode request adalah POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Mengambil data dari form
    $id_cuti = isset($_POST['id_cuti']) ? $_POST['id_cuti'] : null;
    $idpeg = isset($_POST['idpeg']) ? $_POST['idpeg'] : null;
    $tanggal = isset($_POST['tanggal']) ? $_POST['tanggal'] : null;
    $daritgl = isset($_POST['daritgl']) ? $_POST['daritgl'] : null;
    $lamacuti = isset($_POST['lamacuti']) ? $_POST['lamacuti'] : null;
    $alasan = isset($_POST['alasan']) ? $_POST['alasan'] : null;
    $ditetapkan = isset($_POST['ditetapkan']) ? $_POST['ditetapkan'] : null;
    $pembuat_surat = isset($_POST['pembuat_surat']) ? $_POST['pembuat_surat'] : null;


    // Validasi jika semua data ada
    if ($id_cuti && $idpeg && $tanggal && $daritgl && $lamacuti && $alasan && $ditetapkan && $pembuat_surat) {
        // Query untuk memperbarui data cuti

        // Mengembalikan jatah cuti yang lama
        $stmt = $conn->prepare("SELECT idpeg, lamacuti FROM cuti Where id_cuti = ?");
        $stmt->bind_param("s", $id_cuti);
        $stmt->execute();
        $stmt->bind_result($idpeg_awal, $lamacuti_awal);
        $stmt->fetch();
        $stmt->close();

        $stmt = $conn->prepare("SELECT cuti FROM pegawai WHERE idpeg = ?");
        $stmt->bind_param("s", $idpeg_awal);
        $stmt->execute();
        $stmt->bind_result($cuti);
        $stmt->fetch();
        $stmt->close();

        $cuti_updated = $cuti + $lamacuti_awal;
        $stmt = $conn->prepare("UPDATE pegawai SET cuti = ? WHERE idpeg = ?");
        $stmt->bind_param("is", $cuti_updated, $idpeg_awal);
        $stmt->execute();
        $stmt->close();

        // Cek apakah idpeg ada di tabel pegawai
        $stmt = $conn->prepare("SELECT cuti FROM pegawai WHERE idpeg = ?");
        $stmt->bind_param('s', $idpeg);
        $stmt->execute();
        $stmt->bind_result($cuti);
        $stmt->fetch();
        $stmt->close();

        if (!$cuti) {
            $_SESSION['message'] = 'Error! ID Pegawai tidak ditemukan!';
            header('Location: cuti.php');
            exit();
        }

        // Cek jika jatah cuti cukup
        if ($cuti < $lamacuti) {
            $_SESSION['message'] = ['type' => 'error','text'=> 'Jatah cuti tidak cukup.'];
            header('Location: cuti.php');
            exit();
        }

        $sampaitgl = date_create($daritgl);
        for ($hari=0; $hari<$lamacuti; // Use date_add() function to add date object
                date_add($sampaitgl, date_interval_create_from_date_string("1 days"))) {
            if ($sampaitgl->format('l') != "Saturday" && $sampaitgl->format('l') != "Sunday") {
                $hari++;
            } 
        }

        $cuti_akhir = $cuti - $lamacuti;
        $stmt = $conn->prepare("UPDATE pegawai SET cuti = ? WHERE idpeg = ?;");
        $stmt->bind_param('is', $cuti_akhir, $idpeg);
        if ($stmt->execute()) {
            $_SESSION['message'] = 'Cuti berhasil ditambahkan!';
        } else {
            $_SESSION['message'] = 'Gagal menambahkan cuti: ' . $conn->error;
            $stmt->close();
            header('Location: cuti.php');
            exit();
        }
        $stmt->close();

        $sampaitgl = date_format(date_add($sampaitgl, date_interval_create_from_date_string("-1 days")), "Y-m-d");

        // Memperbarui data cuti
        $stmt = $conn->prepare("UPDATE cuti SET idpeg = ?, tanggal = ?, daritgl = ?, sampaitgl = ?, lamacuti = ?, alasan = ?, ditetapkan = ?, pembuat_surat = ? WHERE id_cuti = ?");
        $stmt->bind_param("ssssissss", $idpeg, $tanggal, $daritgl, $sampaitgl, $lamacuti, $alasan, $ditetapkan, $pembuat_surat, $id_cuti);

        // Mengeksekusi query dan memberikan feedback kepada user
        if ($stmt->execute()) {
            $_SESSION['message'] = ['type' => 'success', 'text' => 'Data cuti berhasil diperbarui.'];
        } else {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'Terjadi kesalahan saat memperbarui data cuti.'];
        }

        $stmt->close();
    } else {
        $_SESSION['message'] = ['type' => 'error', 'text' => 'Data yang dikirim tidak lengkap.'];
    }

    // Redirect kembali ke halaman cuti setelah proses selesai
    header("Location: cuti.php");
    exit();
} else {
    // Jika request bukan POST, redirect ke halaman cuti
    header("Location: cuti.php");
    exit();
}
?>
