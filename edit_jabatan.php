<?php
session_start();
require 'config.php';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $idjab = $_POST['idjab'];
    $jabatan = $_POST['jabatan'];
    $iddep = $_POST ['departemen'];

    $stmt = $conn->prepare("UPDATE jabatan SET jabatan = ? WHERE idjab = ?");
    $stmt->bind_param("ss", $jabatan, $idjab);

    if($stmt->execute()){
        // Jika berhasil, simpan relasi ke tabel departemen_jabatan
        $stmt_relasi = $conn->prepare("UPDATE departemen_jabatan SET iddep=? WHERE idjab = ?");
        $stmt_relasi->bind_param("ss", $iddep, $idjab);
        $stmt_relasi->execute();
        $stmt_relasi->close();
        
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