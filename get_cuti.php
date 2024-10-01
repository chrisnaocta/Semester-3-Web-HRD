<?php
session_start();
require 'config.php';
require 'login_session.php';

if (isset($_POST['idpeg'])) {
    $idpeg = $_POST['idpeg'];

    // Pastikan $idpeg benar-benar ada dan merupakan string
    if (!empty($idpeg)) {
        $query = "SELECT cuti
        FROM pegawai
        WHERE idpeg = ?";
        
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $idpeg); 
        $stmt->execute();
        $result = $stmt->get_result();

        // Tambahkan opsi jabatan ke dropdown
        echo "'placeholder','Max=". htmlspecialchars($result['cuti']) ."'";
        // $stmt->close();
    } else {
        echo "<option value=''>Tidak ada jabatan tersedia</option>";
    }
}
?>