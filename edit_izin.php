<?php
session_start();
require 'config.php';
require 'login_session.php';

// Cek apakah metode request adalah POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Mengambil data dari form
    $id_izin = isset($_POST['id_izin']) ? $_POST['id_izin'] : null;
    $idpeg = isset($_POST['idpeg']) ? $_POST['idpeg'] : null;
    $tanggal = isset($_POST['tanggal']) ? $_POST['tanggal'] : null;
    $jam = isset($_POST['jam']) ? $_POST['jam'] : null;
    $alasan = isset($_POST['alasan']) ? $_POST['alasan'] : null;
    $ditetapkan = isset($_POST['ditetapkan']) ? $_POST['ditetapkan'] : null;
    $pembuat_surat = isset($_POST['pembuat_surat']) ? $_POST['pembuat_surat'] : null;


    // Validasi jika semua data ada
    if ($id_izin && $idpeg && $tanggal && $jam && $alasan && $ditetapkan && $pembuat_surat) {
        // Query untuk memperbarui data izin
        $stmt = $conn->prepare("UPDATE izin SET idpeg = ?, tanggal = ?, jam = ?, alasan = ?, ditetapkan = ?, pembuat_surat = ? WHERE id_izin = ?");
        $stmt->bind_param("sssssss", $idpeg, $tanggal, $jam, $alasan, $ditetapkan, $pembuat_surat, $id_izin);

        // Mengeksekusi query dan memberikan feedback kepada user
        if ($stmt->execute()) {
            $_SESSION['message'] = ['type' => 'success', 'text' => 'Data izin berhasil diperbarui.'];
        } else {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'Terjadi kesalahan saat memperbarui data izin.'];
        }

        $stmt->close();
    } else {
        $_SESSION['message'] = ['type' => 'error', 'text' => 'Data yang dikirim tidak lengkap.'];
    }

    // Redirect kembali ke halaman izin setelah proses selesai
    header("Location: izin.php");
    exit();
} else {
    // Jika request bukan POST, redirect ke halaman izin
    header("Location: izin.php");
    exit();
}
?>
