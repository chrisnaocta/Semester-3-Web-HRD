<?php
session_start();
require 'config.php';
require 'login_session.php';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $iddep = $_POST['iddep'];
    $departemen = $_POST['departemen'];

    $stmt = $conn->prepare("INSERT INTO departemen (iddep, departemen) VALUES (?,?)");
    $stmt->bind_param("ss", $iddep, $departemen);

    if($stmt->execute()){
        $_SESSION['message'] = ['type' => 'success','text'=> 'Data departemen berhasil ditambahkan.'];
    }else{
        $_SESSION['message'] = ['type' => 'error','text'=> 'Terjadi kesalahan saat menambahkan data.'];
    }

    $stmt->close();
    header('Location: departemen.php');
    exit();
}else{
    header("Location: departemen.php");
    exit();
}
?>