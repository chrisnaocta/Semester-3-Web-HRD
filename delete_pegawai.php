<?php
session_start();
require 'config.php';
require 'login_session.php';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $idpeg = $_POST['idpeg'];

    $stmt = $conn->prepare("DELETE FROM pegawai WHERE idpeg = ?");
    $stmt->bind_param("s", $idpeg);

    if($stmt->execute()){
        echo"Success: Data usaha berhasil dihapus.";
    }else{
        echo "Error: Terjadi kesalahan saat menghapus data.";
    }
    $stmt->close();
}else{
    echo"Error: Invalid request.";
}
?>