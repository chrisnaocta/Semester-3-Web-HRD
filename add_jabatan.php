<?php
session_start();
require 'config.php';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $idjab = $_POST['idjab'];
    $jabatan = $_POST['jabatan'];
    $iddep = $_POST ['departemen'];

    $stmt = $conn->prepare("INSERT INTO jabatan (idjab, jabatan) VALUES (?,?)");
    $stmt->bind_param("ss", $idjab, $jabatan);

    if($stmt->execute()){
        // Jika berhasil, simpan relasi ke tabel departemen_jabatan
        $stmt_relasi = $conn->prepare("INSERT INTO departemen_jabatan (iddep, idjab) VALUES (?, ?)");
        $stmt_relasi->bind_param("ss", $iddep, $idjab);
        $stmt_relasi->execute();
        $stmt_relasi->close();

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