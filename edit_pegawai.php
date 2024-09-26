<?php
session_start();
require 'config.php';
require 'login_session.php';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    // Ambil input dari form
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

    // Prepare the SQL update statement
    $stmt = $conn->prepare("UPDATE pegawai
    SET iddep=?, idjab=?, nama=?, alamat=?, telepon=?, email=?, gaji=?, status=?, jkelamin=?, skerja=?,
    cuti=?, jenjangpendidikan=?, tglkerja=? WHERE idpeg=?");
    $stmt->bind_param('ssssssisssssss', $iddep, $idjab, $nama, $alamat, $telepon, $email,
    $gaji, $status, $jkelamin, $skerja, $cuti, $jenjangpendidikan, $tglkerja, $idpeg);

    // Execute the statement
    if ($stmt->execute()) {
        $_SESSION['message'] = ['type' => 'success','text'=> 'Data usaha berhasil diperbarui.'];
        // Redirect or show a success message
    } else {
        $_SESSION['message'] = ['type' => 'error','text'=> 'Terjadi kesalahan saat memperarui data.'];
    }

    $stmt->close();
    header('Location: pegawai.php');
    exit();
    }else{
    header("Location: pegawai.php");
    exit();
}
?>