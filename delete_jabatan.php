<?php
session_start();
require 'config.php';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $idjab = $_POST['idjab'];

     // First, delete from departemen_jabatan
     $stmt_relasi = $conn->prepare("DELETE FROM departemen_jabatan WHERE idjab = ?");
     $stmt_relasi->bind_param("s", $idjab);

     
    if($stmt_relasi->execute()){
         // If successful, now delete from jabatan
         $stmt = $conn->prepare("DELETE FROM jabatan WHERE idjab = ?");
         $stmt->bind_param("s", $idjab);

         // Execute the delete statement for jabatan
         if ($stmt->execute()) {
            // Successfully deleted jabatan
            $_SESSION['message'] = ['type' => 'success', 'text' => 'Data jabatan berhasil dihapus.'];
        } else {
            // Error while deleting jabatan
            $_SESSION['message'] = ['type' => 'error', 'text' => 'Terjadi kesalahan saat menghapus data jabatan.'];
        }
        $stmt->close();
    }else{
        echo "Error: Terjadi kesalahan saat menghapus data.";
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