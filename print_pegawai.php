<?php
session_start();
require 'config.php';
require 'login_session.php';
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

//Ambil data dari tabel namausaha
$result = $conn->query("SELECT * FROM pegawai");

//Buat PDF
$pdf = new FPDF();
$pdf -> AddPage('L', [340, 210]);

//Tambahkan kop dokumen
$pdf -> SetFont('Arial','B', 16);
$pdf -> Cell(0, 10, $namaUsaha, 0, 1, 'C');
$pdf -> SetFont('Arial','', 12);
$pdf -> Cell(0, 10, $alamatUsaha, 0, 1, 'C');
$pdf -> Ln(10);
$pdf -> SetFont('Arial','B',14);
$pdf ->Cell(0, 10, 'Daftar Usaha', 0, 1, 'L');
$pdf -> Ln(2);

//Tambahkan header tabel
$pdf -> SetFont('Arial','B',11);
$pdf ->Cell(7, 10,'No',1,0,'C');
$pdf ->Cell(20, 10,'Id',1,0,'C');
$pdf ->Cell(25, 10, 'Nama', 1, 0, 'C');
$pdf ->Cell(45, 10, 'Alamat', 1, 0, 'C');
$pdf ->Cell(25, 10, 'Telepon', 1, 0, 'C');
$pdf ->Cell(40, 10, 'Email', 1, 0, 'C');
$pdf ->Cell(20, 10, 'Gaji', 1, 0, 'C');
$pdf ->Cell(17, 10, 'Status', 1, 0, 'C');
$pdf ->Cell(25, 10, 'Gender', 1, 0, 'C');
$pdf ->Cell(25, 10, 'Status Kerja', 1, 0, 'C');
$pdf ->Cell(25, 10, 'Cuti', 1, 0, 'C');
$pdf ->Cell(25, 10, 'Pendidikan', 1, 0, 'C');
$pdf ->Cell(27, 10, 'Tanggal Kerja', 1, 1, 'C');

//Tambahkan data tabel
$pdf ->SetFont('Arial','',10);
$no = 1;
while ($row = $result->fetch_assoc()){
    $pdf ->Cell(7, 10, $no++, 1, 0, 'C');
    $pdf ->Cell(20, 10, $row['idpeg'], 1, 0, 'C');
    $pdf ->Cell(25, 10, $row['nama'], 1, 0, ':L');
    $pdf ->Cell(45, 10, $row['alamat'], 1, 0, 'L');
    $pdf ->Cell(25, 10, $row['telepon'], 1, 0, 'L');
    $pdf ->Cell(40, 10, $row['email'], 1, 0, 'L');
    $pdf ->Cell(20, 10, $row['gaji'], 1, 0, 'L');
    $pdf ->Cell(17, 10, $row['status'], 1, 0, 'L');
    $pdf ->Cell(25, 10, $row['jkelamin'], 1, 0, 'L');
    $pdf ->Cell(25, 10, $row['skerja'], 1, 0, 'L');
    $pdf ->Cell(25, 10, $row['cuti'], 1, 0, 'L');
    $pdf ->Cell(25, 10, $row['jenjangpendidikan'], 1, 0, 'L');
    $pdf ->Cell(27, 10, $row['tglkerja'], 1, 1, 'L');
}

//Output PDF
$pdf -> Output('I', "Daftar_usaha.pdf");
?>