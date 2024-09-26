<?php
session_start();
require 'config.php';
require 'login_session.php';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $idjab = $_POST['idjab'];
    $jabatan = $_POST['jabatan'];
    $iddep = $_POST ['departemen'];

    $stmt = $conn->prepare("UPDATE jabatan SET jabatan = ?, iddep=? WHERE idjab = ?");
    $stmt->bind_param("sss", $jabatan, $iddep, $idjab);

    if($stmt->execute()){
        $_SESSION['message'] = ['type' => 'success','text'=> 'Data jabatan berhasil diperbarui dan Relasi jabatan dengan departemen berhasil diperbarui.'];
    }else{
        $_SESSION['message'] = ['type' => 'error','text'=> 'Terjadi kesalahan saat memperarui data.'];
    }

    $stmt->close();
    header('Location: jabatan.php');
    exit();
}else{
    header("Location: jabatan.php");
    exit();
}
?>