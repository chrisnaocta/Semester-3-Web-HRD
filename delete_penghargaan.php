<?php
session_start();
require 'config.php';
require 'login_session.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_penghargaan = $_POST['id_penghargaan'];

    $stmt = $conn->prepare("DELETE FROM penghargaan WHERE id_penghargaan = ?");
    $stmt->bind_param("s", $id_penghargaan);

    if ($stmt->execute()) {
        echo "Success: Data penghargaan berhasil dihapus.";
    } else {
        echo "Error: Terjadi kesalahan saat menghapus data.";
    }
    $stmt->close();
} else {
    echo "Error: Invalid request.";
}
?>
