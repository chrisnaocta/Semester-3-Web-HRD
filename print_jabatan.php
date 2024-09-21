<?php
session_start();
require 'config.php';
require 'fpdf/fpdf.php';

if (!isset($_SESSION['iduser'])) {
    header("Location: login.php");
    exit();
}

//Ambil data nama usaha dan alamat dari database
$stmt = $conn->prepare("SELECT nama, alamat FROM namausaha LIMIT 1");
$stmt->execute();
$stmt->bind_result($namaUsaha, $alamatUsaha);
$stmt->fetch();
$stmt->close();

//Ambil data dari tabel jabatan
$result = $conn->query("SELECT * FROM jabatan");

//Buat PDF
$pdf = new FPDF();
$pdf -> AddPage();

//Tambahkan kop dokumen
$pdf -> SetFont('Arial','B', 16);
$pdf -> Cell(0, 10, $namaUsaha, 0, 1, 'C');
$pdf -> SetFont('Arial','', 12);
$pdf -> Cell(0, 10, $alamatUsaha, 0, 1, 'C');
$pdf -> Ln(10);
$pdf -> SetFont('Arial','B',14);
$pdf ->Cell(0, 10, 'Daftar Jabatan', 0, 1, 'L');
$pdf -> Ln(2);

//Tambahkan header tabel
$pdf -> SetFont('Arial','B',12);
$pdf ->Cell(10, 10,'No',1,0,'C');
$pdf ->Cell(40, 10,'Kode Jabatan',1,0,'C');
$pdf ->Cell(140, 10, 'Jabatan', 1, 1, 'C');

//Tambahkan data tabel
$pdf ->SetFont('Arial','',12);
$no = 1;
while ($row = $result->fetch_assoc()){
    $pdf ->Cell(10, 10, $no++, 1, 0, 'C');
    $pdf ->Cell(40, 10, $row['idjab'], 1, 0, 'C');
    $pdf ->Cell(140, 10, $row['jabatan'], 1, 1, 'L');
}

//Output PDF
$pdf -> Output('I', "Daftar_jabatan.pdf");
?>