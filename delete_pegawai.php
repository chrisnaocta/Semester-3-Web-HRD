<?php
session_start();
require 'config.php';
require 'login_session.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idpeg = $_POST['idpeg'];

    // Prepare a statement to get the filename of the photo
    $stmt = $conn->prepare("SELECT foto FROM pegawai WHERE idpeg = ?");
    $stmt->bind_param("s", $idpeg);
    $stmt->execute();
    $stmt->bind_result($foto);
    $stmt->fetch();
    $stmt->close();

    // Path to the photo
    $fotoPath = 'foto_peg/' . $foto;

    // Prepare the delete statement
    $stmt = $conn->prepare("DELETE FROM pegawai WHERE idpeg = ?");
    $stmt->bind_param("s", $idpeg);

    if ($stmt->execute()) {
        // Check if the file exists and delete it
        if (file_exists($fotoPath)) {
            unlink($fotoPath); // Deletes the photo file
        }
        echo "Success: Data pegawai berhasil dihapus dan foto juga telah dihapus.";
        header('Location: pegawai.php');
    } else {
        echo "Error: Terjadi kesalahan saat menghapus data.";
    }
    $stmt->close();
} else {
    echo "Error: Invalid request.";
}
?>
