<?php
session_start();
require 'config.php';
require 'login_session.php';

if (isset($_POST['iddep'])) {
    $iddep = $_POST['iddep'];

    // Pastikan $iddep benar-benar ada dan merupakan string
    if (!empty($iddep)) {
        // Query untuk mendapatkan jabatan berdasarkan departemen
        $query = "SELECT jabatan.idjab, jabatan.jabatan 
                  FROM departemen_jabatan
                  JOIN jabatan ON departemen_jabatan.idjab = jabatan.idjab
                  WHERE departemen_jabatan.iddep = ?";
        
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $iddep); 
        $stmt->execute();
        $result = $stmt->get_result();

        // Tambahkan opsi jabatan ke dropdown
        echo "<option value=''>Pilih Jabatan</option>";
        while ($jabatan = $result->fetch_assoc()) {
            echo "<option value='" . $jabatan['idjab'] . "'>" . htmlspecialchars($jabatan['jabatan']) . "</option>";
        }
        // $stmt->close();
    } else {
        echo "<option value=''>Tidak ada jabatan tersedia</option>";
    }
}
?>