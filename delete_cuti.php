<?php
session_start();
require 'config.php';
require 'login_session.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_cuti = $_POST['id_cuti'];

    $stmt = $conn->prepare("SELECT idpeg, lamacuti FROM cuti WHERE id_cuti = ?");
    $stmt->bind_param("s", $id_cuti);
    $stmt->execute();
    $stmt->bind_result($idpeg, $lamacuti);
    $stmt->fetch();
    $stmt->close();

    $stmt = $conn->prepare("SELECT cuti FROM pegawai WHERE idpeg = ?");
    $stmt->bind_param("s", $idpeg);
    $stmt->execute();
    $stmt->bind_result($cuti);
    $stmt->fetch();
    $stmt->close();

    $cuti_akhir = $cuti + $lamacuti;
    $stmt = $conn->prepare("UPDATE pegawai SET cuti = ? WHERE idpeg = ?");
    $stmt->bind_param("is", $cuti_akhir, $idpeg);
    $stmt->execute();
    $stmt->close();

    $stmt = $conn->prepare("DELETE FROM cuti WHERE id_cuti = ?");
    $stmt->bind_param("s", $id_cuti);

    if ($stmt->execute()) {
        echo "Success: Data cuti berhasil dihapus.";
    } else {
        echo "Error: Terjadi kesalahan saat menghapus data.";
    }
    $stmt->close();
} else {
    echo "Error: Invalid request.";
}
?>
