<?php
session_start();
require 'config.php';
require 'login_session.php';

// Cek apakah metode request adalah POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Mengambil data dari form
    $id_peringatan = isset($_POST['id_peringatan']) ? $_POST['id_peringatan'] : null;
    $idpeg = isset($_POST['idpeg']) ? $_POST['idpeg'] : null;
    $tanggal = isset($_POST['tanggal']) ? $_POST['tanggal'] : null;
    $alasan = isset($_POST['alasan']) ? $_POST['alasan'] : null;
    $ditetapkan = isset($_POST['ditetapkan']) ? $_POST['ditetapkan'] : null;
    $pembuat_surat = isset($_POST['pembuat_surat']) ? $_POST['pembuat_surat'] : null;


    // Validasi jika semua data ada
    if ($id_peringatan && $idpeg && $tanggal && $alasan && $ditetapkan && $pembuat_surat) {
        // Query untuk memperbarui data peringatan
        $stmt = $conn->prepare("UPDATE peringatan SET idpeg = ?, tanggal = ?, alasan = ?, ditetapkan = ?, pembuat_surat = ? WHERE id_peringatan = ?");
        $stmt->bind_param("ssssss", $idpeg, $tanggal, $alasan, $ditetapkan, $pembuat_surat, $id_peringatan);

        // Mengeksekusi query dan memberikan feedback kepada user
        if ($stmt->execute()) {
            $_SESSION['message'] = ['type' => 'success', 'text' => 'Data peringatan berhasil diperbarui.'];
        } else {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'Terjadi kesalahan saat memperbarui data peringatan.'];
        }

        $stmt->close();
    } else {
        $_SESSION['message'] = ['type' => 'error', 'text' => 'Data yang dikirim tidak lengkap.'];
    }

    // Redirect kembali ke halaman peringatan setelah proses selesai
    header("Location: peringatan.php");
    exit();
} else {
    // Jika request bukan POST, redirect ke halaman peringatan
    header("Location: peringatan.php");
    exit();
}
?>
