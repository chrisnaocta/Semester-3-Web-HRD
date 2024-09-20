<?php
session_start();
require 'config.php';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $idjab = $_POST['idjab'];
    $jabatan = $_POST['jabatan'];

    $stmt = $conn->prepare("UPDATE jabatan SET jabatan = ? WHERE idjab = ?");
    $stmt->bind_param("ss", $jabatan, $idjab);

    if($stmt->execute()){
        $_SESSION['message'] = ['type' => 'success','text'=> 'Data jabatan berhasil diperbarui.'];
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