<?php
// Start session dan sambungkan ke database
session_start();
require 'config.php';
require 'login_session.php';

// Cek apakah data dikirim melalui metode POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $id_cuti = $_POST['id_cuti'];
    $idpeg = $_POST['idpeg'];
    $tanggal = $_POST['tanggal'];    
    $daritgl = $_POST['daritgl'];
    $lamacuti = $_POST['lamacuti'];
    $alasan = $_POST['alasan'];
    $ditetapkan = $_POST['ditetapkan'];
    $pembuat_surat = $_POST['pembuat_surat'];
    
    // Validasi data input
    if (empty($idpeg) || empty($tanggal) || empty($alasan)) {
        $_SESSION['message'] = 'Semua field harus diisi!';
        header('Location: cuti.php');
        exit();
    }

    // Cek apakah idpeg ada di tabel pegawai
    $stmt = $conn->prepare("SELECT cuti FROM pegawai WHERE idpeg = ?");
    $stmt->bind_param('s', $idpeg);
    $stmt->execute();
    $stmt->bind_result($cuti);
    $stmt->fetch();
    $stmt->close();

    if (!$cuti) {
        $_SESSION['message'] = 'Error! ID Pegawai tidak ditemukan!';
        header('Location: cuti.php');
        exit();
    }

    // Cek jika jatah cuti cukup
    if ($cuti < $lamacuti) {
        $_SESSION['message'] = ['type' => 'error','text'=> 'Jatah cuti tidak cukup.'];
        header('Location: cuti.php');
        exit();
    }


    $sampaitgl = date_create($daritgl);
    for ($hari=0; $hari<$lamacuti; // Use date_add() function to add date object
            date_add($sampaitgl, date_interval_create_from_date_string("1 days"))) {
        if ($sampaitgl->format('l') != "Saturday" && $sampaitgl->format('l') != "Sunday") {
            $hari++;
        } 
    }

    $cuti_akhir = $cuti - $lamacuti;
    $stmt = $conn->prepare("UPDATE pegawai SET cuti = ? WHERE idpeg = ?;");
    $stmt->bind_param('is', $cuti_akhir, $idpeg);

    if ($stmt->execute()) {
        $_SESSION['message'] = 'Cuti berhasil ditambahkan!';
    } else {
        $_SESSION['message'] = 'Gagal menambahkan cuti: ' . $conn->error;
        $stmt->close();
        header('Location: cuti.php');
        exit();
    }

    $sampaitgl = date_format(date_add($sampaitgl, date_interval_create_from_date_string("-1 days")), "Y-m-d");

    // Insert data ke database jika idpeg valid
    $stmt = $conn->prepare("INSERT INTO cuti (id_cuti, idpeg, tanggal, daritgl, sampaitgl, lamacuti, alasan, ditetapkan, pembuat_surat) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param('sssssisss', $id_cuti, $idpeg, $tanggal, $daritgl, $sampaitgl, $lamacuti, $alasan, $ditetapkan, $pembuat_surat);

    // Eksekusi query dan cek apakah berhasil
    if ($stmt->execute()) {
        $_SESSION['message'] = 'Cuti berhasil ditambahkan!';
    } else {
        $_SESSION['message'] = 'Gagal menambahkan cuti: ' . $conn->error;
    }

    $stmt->close();
    $conn->close();

    // Redirect kembali ke halaman cuti
    header('Location: cuti.php');
    exit();
} else {
    // Jika bukan melalui POST, kembalikan ke halaman utama
    header('Location: cuti.php');
    exit();
}
