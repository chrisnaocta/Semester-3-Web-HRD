<?php
session_start();
require 'config.php';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $idjab = $_POST['idjab'];

    $stmt = $conn->prepare("DELETE FROM jabatan WHERE idjab = ?");
    $stmt->bind_param("s", $idjab);

    if($stmt->execute()){
        echo"Success: Data jabatan berhasil dihapus.";
    }else{
        echo "Error: Terjadi kesalahan saat menghapus data.";
    }
    $stmt->close();
}else{
    echo"Error: Invalid request.";
}
?>