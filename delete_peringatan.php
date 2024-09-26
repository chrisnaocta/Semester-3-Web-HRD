<?php
session_start();
require 'config.php';
require 'login_session.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_peringatan = $_POST['id_peringatan'];

    $stmt = $conn->prepare("DELETE FROM peringatan WHERE id_peringatan = ?");
    $stmt->bind_param("s", $id_peringatan);

    if ($stmt->execute()) {
        echo "Success: Data peringatan berhasil dihapus.";
    } else {
        echo "Error: Terjadi kesalahan saat menghapus data.";
    }
    $stmt->close();
} else {
    echo "Error: Invalid request.";
}
?>
