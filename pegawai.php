<?php
session_start();
require 'config.php';
require 'login_session.php';

// Ambil data dari tabel namausaha
$pegawai = $conn->query("SELECT * FROM pegawai");

$iduser = $_SESSION['iduser'];

// Ambil data user dari database
$stmt = $conn->prepare("SELECT username, foto FROM login WHERE iduser = ?");
$stmt->bind_param("i", $iduser);
$stmt->execute();
$stmt->bind_result($username, $foto);
$stmt->fetch();
$stmt->close();

// Ambil data nama usaha dan alamat dari database
$stmt = $conn->prepare("SELECT nama, alamat FROM namausaha LIMIT 1");
$stmt->execute();
$stmt->bind_result($namaUsaha, $alamatUsaha);
$stmt->fetch();
$stmt->close();

// Dapatkan nomor urut terbaru untuk idusaha baru
$stmt = $conn->query("SELECT idpeg FROM pegawai ORDER BY idpeg DESC LIMIT 1");
$latestidpeg= $stmt->fetch_assoc();
$urut = 1;
if ($latestidpeg) {
    $latestNumber = (int) substr($latestidpeg['idpeg'], 1);
    $urut = $latestNumber + 1;
}
$newidpegawai = 'P' . str_pad($urut, 3, '0', STR_PAD_LEFT);


// Simpan pesan ke variabel dan hapus dari session
$message = null;
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}
?>

<!-- Bootstrap 5 source -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<?php require 'head.php'; ?>
<div class="wrapper">
    <header>
        <h4 style="text-align:center;"><?php echo htmlspecialchars($namaUsaha ?? ''); ?></h4>
        <p style="text-align:center;"><?php echo htmlspecialchars($alamatUsaha ?? ''); ?></p>
    </header>

    <?php include 'sidebar.php'; ?>
    <div class="content" id="content">
        <div class="container-fluid mt-3" style="margin-left:15px">
            <div class="row">
                <div class="col-md-12 d-flex justify-content-between align-items-center">
                    <h4>Kepegawaian</h4>
                    <div>
                        <button type="button" class="btn btn-primary mb-3 mr-2" data-bs-toggle="modal" data-bs-target="#addPegawaiModal"><i class='fas fa-plus'></i> Add </button>
                        <button type="button" class="btn btn-secondary mb-3" id="printButton"><i class='fas fa-print'></i> Print</button>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table style="border: 3px;" class="table table-striped table-bordered table-hover">    
                            <thead class="text-center table-info" >
                                <tr>
                                    <th style="width: 1px;">No</th> 
                                    <th style="width: 1%;">Id</th>
                                    <th>Nama</th>
                                    <th>Alamat</th>
                                    <th>Telepon</th>
                                    <th>Email</th>
                                    <th>Gaji</th>
                                    <th>Status</th>
                                    <th>Gender</th>
                                    <th>Status Kerja</th>
                                    <th>Cuti</th>
                                    <th>Pendidikan</th>
                                    <th>Tanggal Kerja</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                <?php
                                    if ($pegawai && $pegawai->num_rows > 0) {
                                    $no = 1;
                                    foreach($pegawai as $row): ?>
                                        <tr>
                                            <td> <?php echo $no++; ?> </td>
                                            <td> <?php echo $row["idpeg"]; ?> </td>
                                            <td> <?php echo $row["nama"]; ?> </td>
                                            <td> <?php echo $row["alamat"]; ?> </td>
                                            <td> <?php echo $row["telepon"]; ?> </td>
                                            <td> <?php echo $row["email"]; ?> </td>
                                            <td> <?php echo $row["gaji"]; ?> </td>
                                            <td> <?php echo $row["status"]; ?> </td>
                                            <td> <?php echo $row["jkelamin"]; ?> </td>
                                            <td> <?php echo $row["skerja"]; ?> </td>
                                            <td> <?php echo $row["cuti"]; ?> </td>
                                            <td> <?php echo $row["jenjangpendidikan"]; ?> </td>
                                            <td> <?php echo $row["tglkerja"]; ?> </td>
                                            <td>
                                                <div class="d-flex justify-content-center">
                                                    <button class='btn btn-warning btn-sm edit-btn mr-1' 
                                                        data-bs-toggle='modal' 
                                                        data-bs-target='#editpegawai'
                                                        data-idpeg='<?php echo htmlspecialchars($row['idpeg']); ?>'
                                                        data-iddep='<?php echo htmlspecialchars($row['iddep']); ?>'
                                                        data-idjab='<?php echo htmlspecialchars($row['idjab']); ?>'
                                                        data-nama='<?php echo htmlspecialchars($row['nama']); ?>'
                                                        data-alamat='<?php echo htmlspecialchars($row['alamat']); ?>'
                                                        data-telepon='<?php echo htmlspecialchars($row['telepon']); ?>'
                                                        data-email='<?php echo htmlspecialchars($row['email']); ?>'
                                                        data-gaji='<?php echo htmlspecialchars($row['gaji']); ?>' 
                                                        data-status='<?php echo htmlspecialchars($row['status']); ?>' 
                                                        data-jkelamin='<?php echo htmlspecialchars($row['jkelamin']); ?>'
                                                        data-skerja='<?php echo htmlspecialchars($row['skerja']); ?>' 
                                                        data-cuti='<?php echo htmlspecialchars($row['cuti']); ?>'
                                                        data-jenjangpendidikan='<?php echo htmlspecialchars($row['jenjangpendidikan']); ?>' 
                                                        data-tglkerja='<?php echo htmlspecialchars($row['tglkerja']); ?>'> 
                                                        <i class='fas fa-edit'></i> Edit
                                                    </button>
                                                    <button class="btn btn-danger btn-sm delete-btn"
                                                                    data-id="<?php echo htmlspecialchars($row['idpeg']); ?>">
                                                                <i class="fas fa-trash"></i> Delete
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach ?>
                                        <?php } else { ?>
                                            <tr><td colspan="6" class="text-center">No data found</td></tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php require 'footer.php'; ?>
</div>

<!-- Modal Add Pegawai -->
<div class="modal fade" id="addPegawaiModal" tabindex="-1" aria-labelledby="addPegawaiModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addPegawaiModalLabel">Add Pegawai</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="add_pegawai.php" method="post">
                    <div class="mb-3">
                    <label for="idpeg" class="form-label">Id pegawai</label>
                        <input type="text" class="form-control" id="idpeg" name="idpeg" value="<?php echo htmlspecialchars($newidpegawai); ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="add_iddep" class="form-label">Departemen</label>
                        <select class="form-control" id="add_iddep" name="iddep" onchange="updateIdDep()" required>
                            <option value="" disabled selected>Pilih Departemen</option>
                            <option value="D001">Direksi</option>
                            <option value="D002">Manajemen Puncak</option>
                            <option value="D003">Keuangan</option>
                            <option value="D004">SDM</option>
                            <option value="D005">Pemasaran</option>
                            <option value="D006">Operasional</option>
                            <option value="D007">Penjualan</option>
                            <option value="D008">Teknologi Informasi</option>
                            <option value="D009">Riset dan Pengembangan</option>
                            <option value="D010">Hubungan Masyarakat</option>
                            <option value="D011">Kualitas</option>
                            <option value="D012">Pengadaan</option>
                            <option value="D013">Layanan Pelanggan</option>
                            <option value="D014">Hukum</option>
                        </select>
                        <!-- Hidden input to store the iddep value -->
                        <input type="hidden" id="hidden_iddep" name="hidden_iddep" />
                    </div>
                    <script>
                        function updateIdDep() {
                            // Get the selected value from the dropdown
                            var selectedValue = document.getElementById("add_iddep").value;
                            
                            // Set the hidden input value to the selected value (iddep)
                            document.getElementById("hidden_iddep").value = selectedValue;
                        }
                    </script>

                    <div class="mb-3">
                        <label for="add_idjab" class="form-label">Jabatan</label>
                        <select class="form-control" id="add_idjab" name="idjab" onchange="updateIdJab()" required>
                            <option value="" disabled selected>Pilih Jabatan</option>
                            <option value="J001">Direktur Utama (CEO)</option>
                            <option value="J002">Direktur Keuangan (CFO)</option>
                            <option value="J003">Direktur Operasional (COO)</option>
                            <option value="J004">Direktur Pemasaran (CMO)</option>
                            <option value="J005">Direktur Sumber Daya Manusia (CHRO)</option>
                            <option value="J006">Manajer Keuangan</option>
                            <option value="J007">Manajer Operasional</option>
                            <option value="J008">Manajer Pemasaran</option>
                            <option value="J009">Manajer SDM</option>
                            <option value="J010">Manajer IT</option>
                            <option value="J011">Manajer Penjualan</option>
                            <option value="J012">Akuntan</option>
                            <option value="J013">Staff Pemasaran</option>
                            <option value="J014">Staf SDM</option>
                            <option value="J015">Staf IT</option>
                            <option value="J016">Staf IT</option>
                            <option value="J017">Staf Penjualan</option>
                            <option value="J018">Asisten Administrasi</option>
                            <option value="J019">Customer Service</option>
                            <option value="J020">Staff Kebersihan</option>
                            <option value="J021">Driver</option>
                        </select>
                        <!-- Hidden input to store the iddep value -->
                        <input type="hidden" id="hidden_iddep" name="hidden_iddep" />
                    </div>
                    
                    <script>
                        function updateIdJab() {
                            // Get the selected value from the dropdown
                            var selectedValue = document.getElementById("add_idjab").value;
                            
                            // Set the hidden input value to the selected value (iddep)
                            document.getElementById("hidden_idjab").value = selectedValue;
                        }
                    </script>

                    <div class="mb-3">
                        <label for="add_nama" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="add_nama" name="nama" required>
                    </div>
                    <div class="mb-3">
                        <label for="add_alamat" class="form-label">Alamat</label>
                        <input type="text" class="form-control" id="add_alamat" name="alamat"required>
                    </div>
                    <div class="mb-3">
                        <label for="add_telepon" class="form-label">Telepon</label>
                        <input type="text" class="form-control" id="add_telepon" minlength="10" maxlength="12" name="telepon"required>
                    </div>
                    <div class="mb-3">
                        <label for="add_email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="add_email" name="email"required>
                    </div>
                    <div class="mb-3">
                        <label for="add_gaji" class="form-label">Gaji</label>
                        <input type="text" class="form-control" id="add_gaji" minlength="7" maxlength="9" name="gaji"required>
                    </div>
                    <div class="mb-3">
                        <label for="add_status" class="form-label">Status</label>
                        <select class="form-control" id="add_status" name="status" required>
                            <option value="" disabled selected>Pilih Status Menikah</option>
                            <option>Menikah</option>
                            <option>Belum</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="add_jkelamin" class="form-label">Jenis Kelamin</label>
                        <select class="form-control" id="add_jkelamin" name="jkelamin" required>
                            <option value="" disabled selected>Jenis Kelamin</option>
                            <option>Pria</option>
                            <option>Wanita</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="add_bank" class="form-label">Status</label>
                        <select class="form-control" id="add_skerja" name="skerja" required>
                            <option value="" disabled selected>Pilih Status Bekerja</option>
                            <option>Tetap</option>
                            <option>Magang</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="add_cuti" class="form-label">Cuti</label>
                        <input type="text" class="form-control" id="add_cuti" minlength="1" maxlength="3" name="cuti"required>
                    </div>
                    <div class="mb-3">
                        <label for="add_pendidikan" class="form-label">Pendidikan</label>
                        <input type="text" class="form-control" id="add_pendidikan" name="jenjangpendidikan" required>
                    </div>
                    <div class="mb-3">
                        <label for="add_tglkerja" class="form-label">Tanggal Kerja</label>
                        <input type="date" class="form-control" id="add_tglkerja" name="tglkerja" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Include jQuery first -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Include Bootstrap and DataTables -->
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>


<script>
    // Function to prevent entering numbers in input
    document.querySelectorAll('input[id="add_nama"]').forEach(function(inputField) {
        inputField.addEventListener('input', function(e) {
            // Remove any non-letter characters (numbers, symbols, etc.) as the user types
            this.value = this.value.replace(/[^a-zA-Z\s]/g, '');
        });
    });

    document.querySelectorAll('input[id="add_telepon"], input[id="add_gaji"], input[id="add_cuti"]').forEach(function(inputField) {
        inputField.addEventListener('input', function(e) {
            // Replace any non-digit characters with an empty string
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    });
</script>

<!-- Modal Edit Pegawai -->
<div class="modal fade" id="editpegawai" tabindex="-1" aria-labelledby="editPegawaiModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPegawaiModalLabel">Add Usaha</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="edit_pegawai.php" method="post" data-*>
                    <div class="mb-3">
                        <label for="edit_idpeg" class="form-label">Id pegawai</label>
                        <input type="text" class="form-control" id="edit_idpeg" name="idpeg"readonly>
                    </div>
                    <div class="mb-3">
                        <label for="edit_iddep" class="form-label">Departemen</label>
                        <select class="form-control" id="edit_iddep" name="iddep" required>
                            <option value="" disabled selected>Pilih Departemen</option>
                            <option value="D001">Direksi</option>
                            <option value="D002">Manajemen Puncak</option>
                            <option value="D003">Keuangan</option>
                            <option value="D004">SDM</option>
                            <option value="D005">Pemasaran</option>
                            <option value="D006">Operasional</option>
                            <option value="D007">Penjualan</option>
                            <option value="D008">Teknologi Informasi</option>
                            <option value="D009">Riset dan Pengembangan</option>
                            <option value="D010">Hubungan Masyarakat</option>
                            <option value="D011">Kualitas</option>
                            <option value="D012">Pengadaan</option>
                            <option value="D013">Layanan Pelanggan</option>
                            <option value="D014">Hukum</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="edit_idjab" class="form-label">Jabatan</label>
                        <select class="form-control" id="edit_idjab" name="idjab" required>
                            <option value="" disabled selected>Pilih Jabatan</option>
                            <option value="J001">Direktur Utama (CEO)</option>
                            <option value="J002">Direktur Keuangan (CFO)</option>
                            <option value="J003">Direktur Operasional (COO)</option>
                            <option value="J004">Direktur Pemasaran (CMO)</option>
                            <option value="J005">Direktur Sumber Daya Manusia (CHRO)</option>
                            <option value="J006">Manajer Keuangan</option>
                            <option value="J007">Manajer Operasional</option>
                            <option value="J008">Manajer Pemasaran</option>
                            <option value="J009">Manajer SDM</option>
                            <option value="J010">Manajer IT</option>
                            <option value="J011">Manajer Penjualan</option>
                            <option value="J012">Akuntan</option>
                            <option value="J013">Staff Pemasaran</option>
                            <option value="J014">Staf SDM</option>
                            <option value="J015">Staf IT</option>
                            <option value="J016">Staf IT</option>
                            <option value="J017">Staf Penjualan</option>
                            <option value="J018">Asisten Administrasi</option>
                            <option value="J019">Customer Service</option>
                            <option value="J020">Staff Kebersihan</option>
                            <option value="J021">Driver</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="edit_nama" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="edit_nama" name="nama" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_alamat" class="form-label">Alamat</label>
                        <input type="text" class="form-control" id="edit_alamat" name="alamat"required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_telepon" class="form-label">Telepon</label>
                        <input type="text" class="form-control" id="edit_telepon" minlength="10" maxlength="12" name="telepon"required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="edit_email" name="email"required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_gaji" class="form-label">Gaji</label>
                        <input type="text" class="form-control" id="edit_gaji" minlength="7" maxlength="9" name="gaji"required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_status" class="form-label">Status</label>
                        <select class="form-control" id="edit_status" name="status" required>
                            <option value="" disabled selected>Pilih Status Menikah</option>
                            <option>Menikah</option>
                            <option>Belum</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_jkelamin" class="form-label">Jenis Kelamin</label>
                        <select class="form-control" id="edit_jkelamin" name="jkelamin" required>
                            <option value="" disabled selected>Jenis Kelamin</option>
                            <option>Pria</option>
                            <option>Wanita</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_bank" class="form-label">Status</label>
                        <select class="form-control" id="edit_skerja" name="skerja" required>
                            <option value="" disabled selected>Pilih Status Bekerja</option>
                            <option>Tetap</option>
                            <option>Magang</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_cuti" class="form-label">Cuti</label>
                        <input type="text" class="form-control" id="edit_cuti" minlength="1" maxlength="3" name="cuti"required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_jenjangpendidikan" class="form-label">Pendidikan</label>
                        <input type="text" class="form-control" id="edit_jenjangpendidikan" name="jenjangpendidikan" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_tglkerja" class="form-label">Tanggal Kerja</label>
                        <input type="date" class="form-control" id="edit_tglkerja" name="tglkerja" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Function to prevent entering numbers in input
    document.querySelectorAll('input[id="edit_pimpinan"] , input[id="edit_atasnama"]').forEach(function(inputField) {
        inputField.addEventListener('input', function(e) {
            // Remove any non-letter characters (numbers, symbols, etc.) as the user types
            this.value = this.value.replace(/[^a-zA-Z\s]/g, '');
        });
    });

    document.querySelectorAll('input[id="edit_notelepon"], input[id="edit_fax"], input[id="edit_npwp"], input[id="edit_noaccount"]').forEach(function(inputField) {
        inputField.addEventListener('input', function(e) {
            // Replace any non-digit characters with an empty string
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    });
</script>

<script>
    // Show message if it exists in the session
    <?php if ($message): ?>
        Swal.fire({
            title: '<?php echo $message['type'] === 'success' ? 'Success!' : 'Error!'; ?>',
            text: '<?php echo $message['text']; ?>',
            icon: '<?php echo $message['type'] === 'success' ? 'success' : 'error'; ?>'
        });
    <?php endif; ?>     

    document.addEventListener('DOMContentLoaded', function () {
        // Add event listener to all edit buttons
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', function () {
                // Get data attributes from the button
                const idpeg = this.getAttribute('data-idpeg');
                const iddep = this.getAttribute('data-iddep');
                const idjab = this.getAttribute('data-idjab');
                const nama = this.getAttribute('data-nama');
                const alamat = this.getAttribute('data-alamat');
                const telepon = this.getAttribute('data-telepon');
                const email = this.getAttribute('data-email');
                const gaji = this.getAttribute('data-gaji');
                const status = this.getAttribute('data-status');
                const jkelamin = this.getAttribute('data-jkelamin'); // Assuming you have a data attribute for this
                const skerja = this.getAttribute('data-skerja'); // Assuming you have a data attribute for this
                const cuti = this.getAttribute('data-cuti'); // Assuming you have a data attribute for this
                const jenjangpendidikan = this.getAttribute('data-jenjangpendidikan'); // Assuming you have a data attribute for this
                const tglkerja = this.getAttribute('data-tglkerja'); // Assuming you have a data attribute for this

                // Set values in the modal
                document.getElementById('edit_idpeg').value = idpeg;
                document.getElementById('edit_iddep').value = iddep;
                document.getElementById('edit_idjab').value = idjab;
                document.getElementById('edit_nama').value = nama;
                document.getElementById('edit_alamat').value = alamat;
                document.getElementById('edit_telepon').value = telepon;
                document.getElementById('edit_email').value = email;
                document.getElementById('edit_gaji').value = gaji;
                document.getElementById('edit_status').value = status;
                document.getElementById('edit_jkelamin').value = jkelamin;
                document.getElementById('edit_skerja').value = skerja;
                document.getElementById('edit_cuti').value = cuti;
                document.getElementById('edit_jenjangpendidikan').value = jenjangpendidikan;
                document.getElementById('edit_tglkerja').value = tglkerja;
            });
        });
    });

    // Handle delete button click
    $(document).on('click', '.delete-btn', function() {
        var idpeg = $(this).data('id');
        Swal.fire({
            title: 'Are you sure?',
            text: 'Apa benar data tersebut dihapus',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'delete_pegawai.php',
                    type: 'POST',
                    data: { idpeg: idpeg },
                    success: function(response) {
                        console.log(response); // Debugging
                        if (response.includes('Success')) {
                            Swal.fire(
                                'Deleted!',
                                'Data berhasil dihapus.',
                                'success'
                            ).then(function() {
                                location.reload();
                            });
                        } else {
                            Swal.fire(
                                'Error!',
                                response,
                                'error'
                            );
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseText); // Debugging
                        Swal.fire(
                            'Error!',
                            'An error occurred: ' + error,
                            'error'
                        );
                    }
                });
            }
        });
    });

    // Print ke PDF        
    $(document).ready(function() {
        // Handle print button click
        $('#printButton').click(function() {
            window.open('print_pegawai.php', '_blank');
        });
    });
</script>