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

//Ambil data dari tabel pegawai
$result = $conn->query(
    "SELECT * FROM pegawai
            LEFT JOIN 
                departemen ON pegawai.iddep = departemen.iddep
            LEFT JOIN 
                jabatan ON pegawai.idjab = jabatan.idjab");

//Buat PDF
$pdf = new FPDF();
$pdf -> AddPage('L', [450, 210]);

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
$pdf ->Cell(10, 10,'Id',1,0,'C');
$pdf ->Cell(25, 10, 'Nama', 1, 0, 'C');
$pdf ->Cell(45, 10, 'Departemen', 1, 0, 'C');
$pdf ->Cell(45, 10, 'Jabatan', 1, 0, 'C');
$pdf ->Cell(45, 10, 'Alamat', 1, 0, 'C');
$pdf ->Cell(27, 10, 'Telepon', 1, 0, 'C');
$pdf ->Cell(45, 10, 'Email', 1, 0, 'C');
$pdf ->Cell(20, 10, 'Gaji', 1, 0, 'C');
$pdf ->Cell(17, 10, 'Status', 1, 0, 'C');
$pdf ->Cell(25, 10, 'Gender', 1, 0, 'C');
$pdf ->Cell(25, 10, 'Status Kerja', 1, 0, 'C');
$pdf ->Cell(25, 10, 'Cuti', 1, 0, 'C');
$pdf ->Cell(25, 10, 'Pendidikan', 1, 0, 'C');
$pdf ->Cell(27, 10, 'Tanggal Kerja', 1, 1, 'C');

//Tambahkan data tabel
$pdf->SetFont('Arial', '', 10);
$no = 1;

while ($row = $result->fetch_assoc()) {
    // Simpan posisi awal untuk Y
    $yStart = $pdf->GetY();

    // Buat sel untuk No, Id, Nama, dan Departemen dengan tinggi yang sama
    $pdf->Cell(7, 10, $no++, 1, 0, 'C');
    $pdf->Cell(10, 10, $row['idpeg'], 1, 0, 'C');
    $pdf->Cell(25, 10, $row['nama'], 1, 0, 'L');
    $pdf->Cell(45, 10, $row['departemen'], 1, 0, 'L');

    // Simpan posisi sebelum MultiCell untuk jabatan
    $x = $pdf->GetX();
    $y = $pdf->GetY();

    // Ambil jabatan dan gunakan MultiCell
    $jabatan = $row['jabatan'];
    $pdf->MultiCell(45, 10, $jabatan, 1, 'L');

    // Hitung tinggi yang digunakan oleh MultiCell
    $height = $pdf->GetY() - $y; // Hitung tinggi yang digunakan

    // Kembalikan posisi ke kolom berikutnya
    $pdf->SetXY($x + 45, $y); // Kembalikan X ke posisi yang tepat

    // Buat sel untuk alamat, telepon, email, gaji, dan kolom lainnya dengan tinggi yang konsisten
    // Hitung tinggi maksimum untuk semua sel
    $maxHeight = max($height, 10); // Atur tinggi minimum 10 untuk konsistensi

    // Buat semua sel dengan tinggi yang sama
    // Pastikan semua sel di kolom lainnya mengikuti tinggi jabatan
    $pdf->Cell(45, $maxHeight, $row['alamat'], 1, 0, 'L');
    $pdf->Cell(27, $maxHeight, $row['telepon'], 1, 0, 'L');
    $pdf->Cell(45, $maxHeight, $row['email'], 1, 0, 'L');
    $pdf->Cell(20, $maxHeight, $row['gaji'], 1, 0, 'L');
    $pdf->Cell(17, $maxHeight, $row['status'], 1, 0, 'L');
    $pdf->Cell(25, $maxHeight, $row['jkelamin'], 1, 0, 'L');
    $pdf->Cell(25, $maxHeight, $row['skerja'], 1, 0, 'L');
    $pdf->Cell(25, $maxHeight, $row['cuti'], 1, 0, 'L');
    $pdf->Cell(25, $maxHeight, $row['jenjangpendidikan'], 1, 0, 'L');
    $pdf->Cell(27, $maxHeight, $row['tglkerja'], 1, 1, 'L');

    // Kembalikan posisi Y ke Y Start untuk iterasi berikutnya
    $pdf->SetY(max($yStart, $pdf->GetY()));
}

//Output PDF
$pdf -> Output('I', "Daftar_usaha.pdf");
?>