<?php
session_start();
require 'config.php';
require 'login_session.php';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $iddep = $_POST['iddep'];

    // Cek apakah ada jabatan yang masih terkait dengan departemen ini
    $checkStmt = $conn->prepare("SELECT COUNT(*) FROM jabatan WHERE iddep = ?");
    $checkStmt->bind_param("s", $iddep);
    $checkStmt->execute();
    $checkStmt->bind_result($count);
    $checkStmt->fetch();
    $checkStmt->close();

    if ($count > 0) {
        echo "Tidak bisa menghapus departemen yang masih memiliki jabatan terkait.";
    } else {
    // Lanjutkan proses penghapusan departemen
    $stmt = $conn->prepare("DELETE FROM departemen WHERE iddep = ?");
    $stmt->bind_param("s", $iddep);

    if($stmt->execute()){
        echo "Success: Data departemen berhasil dihapus.";
    } else {
        echo "Error: Terjadi kesalahan saat menghapus data.";
    }
    $stmt->close();}
}else{
    echo"Error: Invalid request.";
}
?>