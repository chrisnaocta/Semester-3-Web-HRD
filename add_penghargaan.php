<?php
// Start session dan sambungkan ke database
session_start();
require 'config.php';
require 'login_session.php';

// Cek apakah data dikirim melalui metode POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $id_penghargaan = $_POST['id_penghargaan'];
    $idpeg = $_POST['idpeg'];
    $tanggal = $_POST['tanggal'];    
    $alasan = $_POST['alasan'];
    $ditetapkan = $_POST['ditetapkan'];
    $pembuat_surat = $_POST['pembuat_surat'];
    
    // Validasi data input
    if (empty($idpeg) || empty($tanggal) || empty($alasan)) {
        $_SESSION['message'] = 'Semua field harus diisi!';
        header('Location: penghargaan.php');
        exit();
    }

    // Cek apakah idpeg ada di tabel pegawai
    $cek_pegawai = $conn->prepare("SELECT idpeg FROM pegawai WHERE idpeg = ?");
    $cek_pegawai->bind_param('s', $idpeg);
    $cek_pegawai->execute();
    $cek_pegawai->store_result();

    if ($cek_pegawai->num_rows == 0) {
        $_SESSION['message'] = 'ID Pegawai tidak ditemukan!';
        header('Location: penghargaan.php');
        exit();
    }

    // Insert data ke database jika idpeg valid
    $stmt = $conn->prepare("INSERT INTO penghargaan (id_penghargaan, idpeg, tanggal, alasan, ditetapkan, pembuat_surat) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param('ssssss', $id_penghargaan, $idpeg, $tanggal, $alasan, $ditetapkan, $pembuat_surat);

    // Eksekusi query dan cek apakah berhasil
    if ($stmt->execute()) {
        $_SESSION['message'] = 'penghargaan berhasil ditambahkan!';
    } else {
        $_SESSION['message'] = 'Gagal menambahkan penghargaan: ' . $conn->error;
    }

    $stmt->close();
    $conn->close();

    // Redirect kembali ke halaman penghargaan
    header('Location: penghargaan.php');
    exit();
} else {
    // Jika bukan melalui POST, kembalikan ke halaman utama
    header('Location: penghargaan.php');
    exit();
}
