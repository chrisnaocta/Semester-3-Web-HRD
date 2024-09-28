<?php
session_start();
require 'config.php';
require 'login_session.php';

// Cek apakah metode request adalah POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Mengambil data dari form
    $id_penghargaan = isset($_POST['id_penghargaan']) ? $_POST['id_penghargaan'] : null;
    $idpeg = isset($_POST['idpeg']) ? $_POST['idpeg'] : null;
    $tanggal = isset($_POST['tanggal']) ? $_POST['tanggal'] : null;
    $alasan = isset($_POST['alasan']) ? $_POST['alasan'] : null;
    $ditetapkan = isset($_POST['ditetapkan']) ? $_POST['ditetapkan'] : null;
    $pembuat_surat = isset($_POST['pembuat_surat']) ? $_POST['pembuat_surat'] : null;


    // Validasi jika semua data ada
    if ($id_penghargaan && $idpeg && $tanggal && $alasan && $ditetapkan && $pembuat_surat) {
        // Query untuk memperbarui data penghargaan
        $stmt = $conn->prepare("UPDATE penghargaan SET idpeg = ?, tanggal = ?, alasan = ?, ditetapkan = ?, pembuat_surat = ? WHERE id_penghargaan = ?");
        $stmt->bind_param("ssssss", $idpeg, $tanggal, $alasan, $ditetapkan, $pembuat_surat, $id_penghargaan);

        // Mengeksekusi query dan memberikan feedback kepada user
        if ($stmt->execute()) {
            $_SESSION['message'] = ['type' => 'success', 'text' => 'Data penghargaan berhasil diperbarui.'];
        } else {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'Terjadi kesalahan saat memperbarui data penghargaan.'];
        }

        $stmt->close();
    } else {
        $_SESSION['message'] = ['type' => 'error', 'text' => 'Data yang dikirim tidak lengkap.'];
    }

    // Redirect kembali ke halaman penghargaan setelah proses selesai
    header("Location: penghargaan.php");
    exit();
} else {
    // Jika request bukan POST, redirect ke halaman penghargaan
    header("Location: penghargaan.php");
    exit();
}
?>
