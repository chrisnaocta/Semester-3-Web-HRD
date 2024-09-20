<?php
session_start();
require 'config.php';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $idjab = $_POST['idjab'];
    $jabatan = $_POST['jabatan'];

    $stmt = $conn->prepare("INSERT INTO jabatan (idjab, jabatan) VALUES (?,?)");
    $stmt->bind_param("ss", $idjab, $jabatan);

    if($stmt->execute()){
        $_SESSION['message'] = ['type' => 'success','text'=> 'Data jabatan berhasil ditambahkan.'];
    }else{
        $_SESSION['message'] = ['type' => 'error','text'=> 'Terjadi kesalahan saat menambahkan data.'];
    }

    $stmt->close();
    header('Location: jabatan.php');
    exit();
}else{
    header("Location: jabatan.php");
    exit();
}
?>