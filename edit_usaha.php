<?php
session_start();
require 'config.php';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    // Ambil input dari form
    $idusaha = $_POST['idusaha'];
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $notelepon = $_POST['notelepon'];
    $fax = $_POST['fax'];
    $email = $_POST['email'];
    $npwp = $_POST['npwp'];
    $bank = $_POST['bank'];
    $noaccount = $_POST['noaccount'];
    $atasnama = $_POST['atasnama'];
    $pimpinan = $_POST['pimpinan'];

    // Prepare the SQL update statement
    $stmt = $conn->prepare("UPDATE namausaha SET nama=?, alamat=?, notelepon=?, fax=?, email=?, npwp=?, bank=?, noaccount=?, atasnama=?, pimpinan=? WHERE idusaha=?");
    $stmt->bind_param('sssssssssss', $nama, $alamat, $notelepon, $fax, $email, $npwp, $bank, $noaccount, $atasnama, $pimpinan, $idusaha);

    // Execute the statement
    if ($stmt->execute()) {
        $_SESSION['message'] = ['type' => 'success','text'=> 'Data usaha berhasil diperbarui.'];
        // Redirect or show a success message
    } else {
        $_SESSION['message'] = ['type' => 'error','text'=> 'Terjadi kesalahan saat memperarui data.'];
    }

    $stmt->close();
    header('Location: namausaha.php');
    exit();
    }else{
    header("Location: namausaha.php");
    exit();
}
?>