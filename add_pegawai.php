<?php
session_start();
require 'config.php';
require 'login_session.php';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    // Retrieve POST data
    $idpeg = $_POST['idpeg'];
    $iddep = $_POST['iddep'];
    $idjab = $_POST['idjab'];
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $telepon = $_POST['telepon'];
    $email = $_POST['email'];
    $gaji = $_POST['gaji'];
    $status = $_POST['status'];
    $jkelamin = $_POST['jkelamin'];
    $skerja = $_POST['skerja'];
    $cuti = $_POST['cuti'];
    $jenjangpendidikan = $_POST['jenjangpendidikan'];
    $tglkerja = $_POST['tglkerja'];
    $created_at = date('Y-m-d H:i:s'); // Using current timestamp for created_at

    // Prepare the SQL statement
    $stmt = $conn->prepare("INSERT INTO pegawai (idpeg ,iddep, idjab,nama, alamat, telepon, email, gaji, status, jkelamin, skerja, cuti, jenjangpendidikan, tglkerja, created_at)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    // Bind parameters
    $stmt->bind_param("sssssssisssisss", $idpeg, $iddep, $idjab, $nama, $alamat, $telepon, $email, $gaji, $status, $jkelamin, $skerja, $cuti, $jenjangpendidikan, $tglkerja, $created_at);

    // Execute the statement
    if ($stmt->execute()) {
        $_SESSION['message'] = ['type' => 'success', 'text' => 'Data usaha berhasil ditambahkan.'];
        header('Location: pegawai.php');
    } else {
        $_SESSION['message'] = ['type' => 'error', 'text' => 'Terjadi kesalahan saat menambahkan data.'];
    }

    // Close the statement
    $stmt->close();
    $conn->close();
    } else {
    header('Location: pegawai.php');
}
?>