<?php
session_start();
require 'config.php';
require 'login_session.php';
require 'fpdf/fpdf.php';

// Ambil data nama usaha dan alamat dari database
$stmt = $conn->prepare("SELECT nama, alamat, notelepon FROM namausaha LIMIT 1");
$stmt->execute();
$stmt->bind_result($namaUsaha, $alamatUsaha, $noTelepon);
$stmt->fetch();
$stmt->close();

// Ambil data dari GET
$id = isset($_GET['id']) ? $_GET['id'] : '';

// Ambil data dari tabel peringatan dan pegawai
$stmt2 = $conn->prepare("SELECT peringatan.id_peringatan, peringatan.idpeg, peringatan.ditetapkan, peringatan.tanggal, peringatan.alasan, peringatan.pembuat_surat, pegawai.nama 
                         FROM peringatan
                         JOIN pegawai ON peringatan.idpeg = pegawai.idpeg
                         WHERE peringatan.id_peringatan = ? LIMIT 1");
$stmt2->bind_param('s', $id);
$stmt2->execute();
$stmt2->bind_result($id_peringatan, $idpeg, $ditetapkan, $tanggal, $alasan, $pembuat_surat, $namaPegawai);
$stmt2->fetch();
$stmt2->close();

// Buat PDF
$pdf = new FPDF();
$pdf->AddPage();

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
$pdf->Cell(0, 10, 'Surat Peringatan', 0, 1, 'C');
$pdf->SetDrawColor(0, 0, 0); // Warna hitam
$pdf->Line(80, $pdf->GetY(), 130, $pdf->GetY()); // Garis bawah judul Surat Peringatan
$pdf->Ln(2);

// Nomor surat dengan id_peringatan
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(0, 10, 'Nomor : ' . $id_peringatan, 0, 1, 'C');
$pdf->Ln(1);

// Isi surat
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(0, 10, 'Surat peringatan ini ditujukan kepada :', 0, 1, 'L');
$pdf->Ln(1);

// NIK dan Nama Pegawai
$pdf->SetFont('Arial', 'B', 11);
// Set lebar kolom untuk label dan nilai
$labelWidth = 14; // Lebar untuk label seperti 'NIK' dan 'Nama'
$valueWidth = 80; // Lebar untuk nilai seperti ID Pegawai dan Nama Pegawai

// NIK
$pdf->Cell($labelWidth, 10, 'NIK', 0, 0, 'L'); // Kolom label NIK
$pdf->Cell($labelWidth, 10, ':', 0, 0, 'L'); 
$pdf->Cell($valueWidth, 10, $idpeg, 0, 1, 'L'); // Kolom nilai NIK

// Nama
$pdf->Cell($labelWidth, 10, 'Nama', 0, 0, 'L'); // Kolom label Nama
$pdf->Cell($labelWidth, 10, ':', 0, 0, 'L'); 
$pdf->Cell($valueWidth, 10, $namaPegawai, 0, 1, 'L'); // Kolom nilai Nama
$pdf->Ln(2);

// Alasan peringatan dengan pengaturan spasi 1.25
$pdf->SetFont('Arial', '', 11);
$lineHeight = 6.0 * 1.25; // Line height untuk spasi 1.25
$pdf->MultiCell(0, $lineHeight, $alasan, 0, 'L'); // MultiCell untuk teks dengan spasi 1.25
$pdf->Ln(2);

// Tanggal dan lokasi (Jakarta), rata kanan dengan center alignment
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(310, 10, $ditetapkan . ', ' . date('d F Y', strtotime($tanggal)), 0, 1, 'C');
$pdf->Ln(1);

// Hormat kami, pembuat surat, dan posisinya di-center dan rata kanan
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(310, 10, 'Diterbitkan oleh,', 0, 1, 'C');
$pdf->Ln(10);
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(310, 10, $pembuat_surat, 0, 1, 'C');
$pdf->SetDrawColor(0, 0, 0); // Warna hitam
$pdf->Line(130, $pdf->GetY(), 200, $pdf->GetY()); // Garis bawah 
$pdf->Ln(1);

// HRD dan nama perusahaan
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(310, 10, 'HRD '. $namaUsaha, 0, 1, 'C');

// Output PDF
$pdf->Output('I', 'surat_sp.pdf');
?>
