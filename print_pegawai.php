<?php
session_start();
require 'config.php';
require 'login_session.php';
require 'fpdf/fpdf.php';

//Ambil data nama usaha dan alamat dari database
$stmt = $conn->prepare("SELECT nama, alamat, notelepon FROM namausaha LIMIT 1");
$stmt->execute();
$stmt->bind_result($namaUsaha, $alamatUsaha, $noTelepon);
$stmt->fetch();
$stmt->close();

// Ambil data dari GET
$id = isset($_GET['id']) ? $_GET['id'] : '';

//Ambil data dari tabel pegawai
$stmt1 = $conn->prepare(
    "SELECT * FROM pegawai
            LEFT JOIN 
                departemen ON pegawai.iddep = departemen.iddep
            LEFT JOIN 
                jabatan ON pegawai.idjab = jabatan.idjab
            WHERE idpeg = ?");

$stmt1->bind_param('s', $id);
$stmt1->execute();
$result = $stmt1->get_result();
$row1 = $result->fetch_assoc(); // Fetch the result as associative array
$stmt1->close();

//Buat PDF
$pdf = new FPDF();
$pdf -> AddPage('P', 'A4');

// Tambahkan logo di sisi kiri dan nama perusahaan serta alamat di sisi kanan
$logoFile = 'logo/logo.png'; // Path ke file logo
$logoWidth = 30; // Lebar logo
$logoHeight = 30; // Tinggi logo

// Logo
$pdf->Image($logoFile, 10, 10, $logoWidth, $logoHeight);

// Nama Perusahaan dan Alamat
$pdf->SetXY(10, 10); // Set posisi X dan Y setelah logo
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, $namaUsaha, 0, 1, 'C');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, $alamatUsaha, 0, 1, 'C');
$pdf->Cell(0, 10, 'Telepon: '.$noTelepon, 0, 1, 'C');

// Garis pembatas di bawah alamat
$pdf->Ln(1);
$pdf->SetDrawColor(0, 0, 0); // Warna hitam
$pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY()); // Garis dari kiri ke kanan
$pdf->Ln(0.8);
$pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY()); // Garis dari kiri ke kanan
$pdf->Ln(5);

// Tambahkan jenis surat dengan garis bawah
$pdf->SetFont('Arial', 'B', 18);
$pdf->Cell(0, 10, 'Data Pegawai', 0, 1, 'C');
$pdf->SetDrawColor(0, 0, 0); // Warna hitam
$pdf->Line(80, $pdf->GetY(), 130, $pdf->GetY()); // Garis bawah judul Data Pegawai
$pdf->Cell(0, 10, 'Id: '. $row1['idpeg'], 0, 1, 'C');
$pdf->Ln(3);

// NIK dan Nama Pegawai
$pdf->SetFont('Arial', 'B', 11);
// Set lebar kolom untuk label dan nilai
$labelWidth = 25; // Lebar untuk label seperti 'ID' dan 'Nama'
$valueWidth = 30; // Lebar untuk nilai seperti ID Pegawai dan Nama Pegawai

// Id Pegawai
$pdf->Cell($labelWidth, 10, 'Id', 0, 0, 'L'); // Kolom label ID
$pdf->Cell($labelWidth, 10, ': ' . $row1['idpeg'], 0, 1, 'L'); 

// Nama
$pdf->Cell($labelWidth, 10, 'Nama', 0, 0, 'L'); // Kolom label Nama
$pdf->Cell($labelWidth, 10, ': '. $row1['nama'], 0, 1, 'L'); 
$pdf->Ln(2);

// Foto
$pdf->Cell($labelWidth, 10, 'Foto', 0, 0, 'L'); // Kolom label foto
$pdf->Cell($labelWidth, 10, ':', 0, 0, 'L'); 
$pdf->Cell($valueWidth, 10, "", 0, 1, 'L'); // Kolom nilai Nama
$pdf->Ln(2);

$photoPath = 'foto_peg/' . $row1['foto']; // Make sure the path is correct
// Check if the file exists and display the image
if (file_exists($photoPath) && !empty($row1['foto'])) {
    // Add the photo to the PDF (with X, Y, width, height)
    $pdf->Image($photoPath, $pdf->GetX()+29, $pdf->GetY()-10, 30, 30); // Adjust the width and height as needed
    $pdf->Ln(22); // Move the cursor below the image after it's placed
} else {
    // If no image exists, display a placeholder text or blank cell
    $pdf->Cell(30, 30, 'No photo available', 1, 1, 'L'); // Placeholder text
}
$pdf->Ln(2); // Add space after the image or placeholder

// Departemen
$pdf->Cell($labelWidth, 10, 'Departemen', 0, 0, 'L'); // Kolom label Departemen
$pdf->Cell($labelWidth, 10, ': '. $row1['departemen'], 0, 1, 'L'); 
$pdf->Ln(2);

// Jabatan
$pdf->Cell($labelWidth, 10, 'Jabatan', 0, 0, 'L'); // Kolom label Jabatan
$pdf->Cell($labelWidth, 10, ': '. $row1['jabatan'], 0, 1, 'L'); 
$pdf->Ln(2);

// alamat
$pdf->Cell($labelWidth, 10, 'Alamat', 0, 0, 'L'); // Kolom label alamat
$pdf->Cell($labelWidth, 10, ': '. $row1['alamat'], 0, 1, 'L'); 
$pdf->Ln(2);

// telepon
$pdf->Cell($labelWidth, 10, 'Telepon', 0, 0, 'L'); // Kolom label telepon
$pdf->Cell($labelWidth, 10, ': '. $row1['telepon'], 0, 1, 'L'); 
$pdf->Ln(2);

// email
$pdf->Cell($labelWidth, 10, 'Email', 0, 0, 'L'); // Kolom label email
$pdf->Cell($labelWidth, 10, ': '. $row1['email'], 0, 1, 'L'); 
$pdf->Ln(2);

// gaji
$pdf->Cell($labelWidth, 10, 'Gaji:', 0, 0, 'L'); // Kolom label
$pdf->Cell($valueWidth, 10, ': Rp ' . number_format($row1['gaji'], 0, ',', '.'), 0, 1, 'L'); // Format gaji ke Rupiah
$pdf->Ln(2);

// status
$pdf->Cell($labelWidth, 10, 'Status', 0, 0, 'L'); // Kolom label status
$pdf->Cell($labelWidth, 10, ': '. $row1['status'] . ' menikah', 0, 1, 'L'); 
$pdf->Ln(2);

// gender
$pdf->Cell($labelWidth, 10, 'Gender', 0, 0, 'L'); // Kolom label gender
$pdf->Cell($labelWidth, 10, ': '. $row1['jkelamin'], 0, 1, 'L'); 
$pdf->Ln(2);

// status kerja
$pdf->Cell($labelWidth, 10, 'Status kerja', 0, 0, 'L'); // Kolom label status kerja
$pdf->Cell($labelWidth, 10, ': '. $row1['skerja'], 0, 1, 'L'); 
$pdf->Ln(2);

// cuti
$pdf->Cell($labelWidth, 10, 'Cuti', 0, 0, 'L'); // Kolom label cuti
$pdf->Cell($labelWidth, 10, ': '. $row1['cuti']. ' Hari', 0, 1, 'L'); 
$pdf->Ln(2);

// pendidikan
$pdf->Cell($labelWidth, 10, 'Pendidikan', 0, 0, 'L'); // Kolom label pendidikan
$pdf->Cell($labelWidth, 10, ': '. $row1['jenjangpendidikan'], 0, 1, 'L'); 
$pdf->Ln(2);

// tanggal kerja
// $pdf->Cell($labelWidth, 10, 'Tanggal kerja', 0, 0, 'L'); // Kolom label tanggal kerja
// $pdf->Cell($labelWidth, 10, ': '. $row1['tglkerja'], 0, 1, 'L'); 
// $pdf->Ln(2);

// Konversi tanggal dari database ke format DateTime
$tanggalKerja = new DateTime($row1['tglkerja']);

// Format tanggal menjadi 'l, d F Y' (hari, tanggal, bulan, tahun)
$formattedTanggalKerja = $tanggalKerja->format('l, d F Y');

// // Cetak hasil ke PDF
$pdf->Cell($labelWidth, 10, 'Tanggal kerja', 0, 0, 'L'); // Kolom label tanggal kerja
$pdf->Cell($labelWidth, 10, ': ' . $formattedTanggalKerja, 0, 1, 'L'); // Kolom nilai tanggal kerja
$pdf->Ln(2);


//Output PDF
$pdf -> Output('I', "Daftar_usaha.pdf");
?>