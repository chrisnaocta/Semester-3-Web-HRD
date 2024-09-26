<?php
session_start();
require 'config.php';
require 'login_session.php';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $idjab = $_POST['idjab'];

     // First, delete from departemen_jabatan
     $stmt_relasi = $conn->prepare("DELETE FROM jabatan WHERE idjab = ?");
     $stmt_relasi->bind_param("s", $idjab);

     
    if($stmt_relasi->execute()){

        $_SESSION['message'] = ['type' => 'success', 'text' => 'Data jabatan berhasil dihapus.'];
    }else{
        $_SESSION['message'] = ['type' => 'error','text'=> 'Terjadi kesalahan saat menghapus data.'];
    }

    // Close the relation statement
    $stmt_relasi->close();
    header('Location: jabatan.php');
    exit();
}else{
    echo"Error: Invalid request.";
    header('Location: jabatan.php');
    exit();
}
?>