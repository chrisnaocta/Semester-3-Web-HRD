<?php
session_start();
require 'config.php';
require 'login_session.php';
require 'fpdf/fpdf.php';

//Ambil data nama usaha dan alamat dari database
$stmt = $conn->prepare("SELECT nama, alamat FROM namausaha LIMIT 1");
$stmt->execute();
$stmt->bind_result($namaUsaha, $alamatUsaha);
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
$stmt1->close();

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
$pdf ->Cell(40, 10,'Foto',1,0,'C');
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

// Tambahkan data tabel
$pdf->SetFont('Arial', '', 10);
$no = 1;

while ($row = $result->fetch_assoc()) {
    // Simpan posisi awal untuk Y
    $yStart = $pdf->GetY();
    
    // Tentukan tinggi default per baris
    $cellHeight = 10;

    // Simpan posisi awal X untuk mengembalikan posisi setelah MultiCell
    $xStart = $pdf->GetX();

    // Buat semua sel dalam satu baris dengan tinggi minimal default
    $pdf->Cell(7, $cellHeight+20, $no++, 1, 0, 'C');
    $pdf->Cell(10, $cellHeight+20, $row['idpeg'], 1, 0, 'C');

    // Add employee photo (assumed photo path is stored in 'foto' column)
    $photoPath = 'foto_peg/' . $row['foto']; // Make sure this path is correct
    if (file_exists($photoPath)) {
        $pdf->Image($photoPath, $pdf->GetX(), $yStart, 20, 20); // Use yStart for Y position
    } else {
        // Optional: You could handle missing images
        $pdf->Cell(20, 20, '', 1, 0, 'L'); // Placeholder if the image does not exist
    }

    $pdf->Cell(25, $cellHeight+20, $row['nama'], 1, 0, 'L');
    $pdf->Cell(45, $cellHeight+20, $row['departemen'], 1, 0, 'L');

    // Ambil posisi sebelum MultiCell untuk jabatan
    $xJabatan = $pdf->GetX();
    $yJabatan = $pdf->GetY();

    // MultiCell untuk jabatan
    $jabatan = $row['jabatan'];
    $pdf->MultiCell(45, $cellHeight, $jabatan, 1, 'L'); // Gunakan tinggi per baris 5 untuk MultiCell

    // Hitung tinggi yang digunakan oleh MultiCell
    $heightJabatan = $pdf->GetY() - $yJabatan;

    // Kembalikan posisi X ke kolom berikutnya setelah jabatan
    $pdf->SetXY($xJabatan + 45, $yJabatan);

    // Tentukan tinggi maksimum untuk baris saat ini
    $maxHeight = max($cellHeight, $heightJabatan + 20); // Corrected here

    // Buat sel di kolom lainnya mengikuti tinggi maksimum
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

    // Kembalikan posisi Y untuk iterasi berikutnya
    $pdf->SetY($yStart + $maxHeight);
}


//Output PDF
$pdf -> Output('I', "Daftar_usaha.pdf");
?>