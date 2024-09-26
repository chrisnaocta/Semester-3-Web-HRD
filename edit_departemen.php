<?php
session_start();
require 'config.php';
require 'login_session.php';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $iddep = $_POST['iddep'];
    $departemen = $_POST['departemen'];

    $stmt = $conn->prepare("UPDATE departemen SET departemen = ? WHERE iddep = ?");
    $stmt->bind_param("ss", $departemen, $iddep);

    if($stmt->execute()){
        $_SESSION['message'] = ['type' => 'success','text'=> 'Data departemen berhasil diperbarui.'];
    }else{
        $_SESSION['message'] = ['type' => 'error','text'=> 'Terjadi kesalahan saat memperarui data.'];
    }

    $stmt->close();
    header('Location: departemen.php');
    exit();
}else{
    header("Location: departemen.php");
    exit();
}
?>