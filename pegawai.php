<?php
session_start();
require 'config.php';
require 'login_session.php';

// Ambil data dari tabel pegawai
$pegawai = $conn->query(
"SELECT * FROM pegawai
        LEFT JOIN 
            departemen ON pegawai.iddep = departemen.iddep
        LEFT JOIN 
            jabatan ON pegawai.idjab = jabatan.idjab");

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

<link rel="stylesheet" href="/Lat_HRD/CSS/pegawai.css">

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
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table id="pegawaiTable" style="border: 3px;" class="table table-striped table-bordered table-hover">    
                            <thead class="text-center table-info" >
                                <tr>
                                    <th style="width: 0,5px;">No</th>
                                    <th>Foto</th> 
                                    <th style="width: 1%;">Id</th>
                                    <th>Nama</th>
                                    <th>Departemen</th>
                                    <th>Jabatan</th>
                                    <th>Alamat</th>
                                    <th>Telepon</th>
                                    <th>Email</th>
                                    <th>Gaji</th>
                                    <th>Status Menikah</th>
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
                                            <td> <img src="foto_peg/<?php echo htmlspecialchars($row['foto']);?>"
                                            alt="User Photo" class="user-photo"></td>
                                            <td> <?php echo $row["idpeg"]; ?> </td>
                                            <td> <?php echo $row["nama"]; ?> </td>
                                            <td> <?php echo $row["departemen"]; ?> </td>
                                            <td> <?php echo $row["jabatan"]; ?> </td>
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
                                                        data-tglkerja='<?php echo htmlspecialchars($row['tglkerja']); ?>'
                                                        data-profile='<?php echo htmlspecialchars($row['foto']); ?>'> 
                                                        <i class='fas fa-edit'></i> 
                                                    </button>
                                                    <button type="button" class='btn btn-success btn-sm print-btn' id="printButton"
                                                        data-id="<?php echo htmlspecialchars($row['idpeg']); ?>">
                                                        <i class='fas fa-print'>
                                                        </i>
                                                    </button>
                                                    <button class="btn btn-danger btn-sm delete-btn"
                                                                    data-id="<?php echo htmlspecialchars($row['idpeg']); ?>">
                                                                <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach ?>
                                        <?php } else { ?>
                                            <tr><td colspan="15" class="text-center">No data found</td></tr>
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
                <form action="add_pegawai.php" method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                    <label for="idpeg" class="form-label">Id pegawai</label>
                        <input type="text" class="form-control" id="idpeg" name="idpeg" value="<?php echo htmlspecialchars($newidpegawai); ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="add_iddep" class="form-label">Departemen</label>
                        <select class="form-control" id="add_iddep" name="iddep" required>
                        <option value="">Pilih Departemen</option>
                            <?php
                            // Ambil data departemen dari database
                            $departemenResult = $conn->query("SELECT iddep, departemen FROM departemen");
                            while ($departemen = $departemenResult->fetch_assoc()) {
                                echo "<option value='" .
                                htmlspecialchars($departemen['iddep']) . "'>" .
                                htmlspecialchars($departemen['departemen']) .
                                "</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="add_idjab" class="form-label">Jabatan</label>
                        <select class="form-control" id="add_idjab" name="idjab" required>
                            <option value="">Pilih Jabatan</option>
                        </select>
                    </div>

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
                            <option>Sudah</option>
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
                            <option>Kontrak</option>
                            <option>Magang</option>
                            <option>Pensiun</option>
                            <option>PHK</option>
                            <option>Keluar</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="add_cuti" class="form-label">Cuti</label>
                        <input type="text" class="form-control" id="add_cuti" minlength="1" maxlength="3" name="cuti"required>
                    </div>
                    <div class="mb-3">
                        <label for="add_jenjangpendidikan" class="form-label">Pendidikan</label>
                        <select class="form-control" id="add_jenjangpendidikan" name="jenjangpendidikan" required>
                            <option value="" disabled selected>Pilih Jenjang Pendidikan</option>
                            <option>SMA</option>
                            <option>SMK</option>
                            <option>D3</option>
                            <option>S1</option>
                            <option>S2</option>
                            <option>S3</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="add_tglkerja" class="form-label">Tanggal Kerja</label>
                        <input type="date" class="form-control" id="add_tglkerja" name="tglkerja" required>
                    </div>
                    <div class="mb-3">
                        <label for="profile-picture">Profile picture</label> <br>
                        <input type="file" class="form-control" name="profile_peg" id="add_profile" accept=".jpg, .jpeg, .png, .jfif" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- AJAX Untuk Pilih Jabatan -->
<script>
$(document).ready(function() {
    $('#add_iddep').change(function() {
        var iddep = $(this).val();
        if (iddep !== "") {
            $.ajax({
                url: 'get_jabatan.php',
                method: 'POST',
                data: {iddep: iddep},
                beforeSend: function() {
                    $('#add_idjab').html('<option>Loading...</option>');
                },
                success: function(response) {
                    $('#add_idjab').html(response);
                },
                error: function() {
                    $('#add_idjab').html('<option>Error loading jabatan</option>');
                }
            });
        } else {
            $('#add_idjab').html('<option value="">Pilih Jabatan</option>');
        }
    });
});
</script>

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
                <h5 class="modal-title" id="editPegawaiModalLabel">Edit Pegawai</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="edit_pegawai.php" method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="edit_idpeg" class="form-label">Id pegawai</label>
                        <input type="text" class="form-control" id="edit_idpeg" name="idpeg"readonly>
                    </div>
                    <div class="mb-3">
                        <label for="edit_iddep" class="form-label">Departemen</label>
                        <select class="form-control" id="edit_iddep" name="iddep" required>
                        <option value="">Pilih Departemen</option>
                            <?php
                            // Ambil data departemen dari database
                            $departemenResult = $conn->query("SELECT iddep, departemen FROM departemen");
                            while ($departemen = $departemenResult->fetch_assoc()) {
                                echo "<option value='" .
                                htmlspecialchars($departemen['iddep']) . "'>" .
                                htmlspecialchars($departemen['departemen']) .
                                "</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="edit_idjab" class="form-label">Jabatan</label>
                        <select class="form-control" id="edit_idjab" name="idjab" required>
                            <option value="">Pilih Jabatan</option>
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
                            <option>Sudah</option>
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
                            <option>Kontrak</option>
                            <option>Magang</option>
                            <option>Pensiun</option>
                            <option>PHK</option>
                            <option>Keluar</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_cuti" class="form-label">Cuti</label>
                        <input type="text" class="form-control" id="edit_cuti" minlength="1" maxlength="3" name="cuti"required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_jenjangpendidikan" class="form-label">Pendidikan</label>
                        <select class="form-control" id="edit_jenjangpendidikan" name="jenjangpendidikan" required>
                            <option value="" disabled selected>Pilih Jenjang Pendidikan</option>
                            <option>SMA</option>
                            <option>SMK</option>
                            <option>D3</option>
                            <option>S1</option>
                            <option>S2</option>
                            <option>S3</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_tglkerja" class="form-label">Tanggal Kerja</label>
                        <input type="date" class="form-control" id="edit_tglkerja" name="tglkerja" required>
                    </div>
                    <!-- <div class="mb-3">
                        <label for="profile-picture">Profile picture</label> <br>
                        <input type="file" class="form-control" name="profile_peg" id="edit_profile" accept=".jpg, .jpeg, .png, .jfif" required>
                    </div> -->
                    <div class="mb-3">
                        <label for="profile-picture">Profile picture</label> <br>
                        <!-- Display current profile picture if exists -->
                        <img id="current_profile_picture" src="" alt="Current Profile Picture" class="mb-2" style="max-width: 100px;">
                        <input type="file" class="form-control" name="profile_peg" id="edit_profile" accept=".jpg, .jpeg, .png, .jfif">
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- AJAX Untuk Pilih Jabatan -->
<script>
$(document).ready(function() {
    $('#edit_iddep').change(function() {
        var iddep = $(this).val();
        if (iddep !== "") {
            $.ajax({
                url: 'get_jabatan.php',
                method: 'POST',
                data: {iddep: iddep},
                beforeSend: function() {
                    $('#edit_idjab').html('<option>Loading...</option>');
                },
                success: function(response) {
                    $('#edit_idjab').html(response);
                },
                error: function() {
                    $('#edit_idjab').html('<option>Error loading jabatan</option>');
                }
            });
        } else {
            $('#edit_idjab').html('<option value="">Pilih Jabatan</option>');
        }
    });
});
</script>

<script>
    // Function to prevent entering numbers in input
    document.querySelectorAll('input[id="edit_nama"]').forEach(function(inputField) {
        inputField.addEventListener('input', function(e) {
            // Remove any non-letter characters (numbers, symbols, etc.) as the user types
            this.value = this.value.replace(/[^a-zA-Z\s]/g, '');
        });
    });
    
    document.querySelectorAll('input[id="edit_telepon"], input[id="edit_gaji"], input[id="edit_cuti"]').forEach(function(inputField) {
        inputField.addEventListener('input', function(e) {
            // Replace any non-digit characters with an empty string
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    });
</script>

<!-- Bootstrap 5 source -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Include jQuery first -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Include Bootstrap and DataTables -->
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

<script>
    //Pagination
    $(document).ready(function() {
        // Adjust DataTables' scrolling to avoid overlapping with the footer
        function adjustTableHeight() {
            var footerHeight = $('footer').outerHeight();
            var tableHeight = 'calc(90vh - 290px - ' + footerHeight + 'px)';

            $('#pegawaiTable').DataTable().destroy();
            $('#pegawaiTable').DataTable({
                "pagingType": "simple_numbers",
                "scrollY": tableHeight,
                "scrollCollapse": true,
                "paging": true,
                "pageLength": 5,
                "dom": 'tip',
            });
        }

        // Call the function to adjust table height initially
        adjustTableHeight();

        // Adjust table height on window resize
        $(window).resize(function() {
            adjustTableHeight();
        });
    });

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
                const profile = this.getAttribute('data-profile'); // Assuming you have a data attribute for this

                // Set values in the modal
                document.getElementById('edit_idpeg').value = idpeg;
                document.getElementById('edit_iddep').value = iddep;
                // document.getElementById('edit_idjab').value = idjab;
                // Tidakdiperlukan karena dalam AJAX sukses callback,
                //kita sudah melakukan pengaturan nilai idjab setelah opsi jabatan berhasil dimuat melalui:
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

                 // Set the current profile picture source
                document.getElementById('current_profile_picture').src = 'foto_peg/' + profile; // Set the src for current profile picture


                // // Load jabatan options based on the selected department
                if (iddep !== "") {
                    $.ajax({
                        url: 'get_jabatan.php',
                        method: 'POST',
                        data: { iddep: iddep },
                        success: function(response) {
                            // Update the jabatan dropdown
                            $('#edit_idjab').html(response);

                            // After loading the options, set the selected jabatan
                            $('#edit_idjab').val(idjab);
                        },
                        error: function() {
                            $('#edit_idjab').html('<option>Error loading jabatan</option>');
                        }
                    });
                } else {
                    $('#edit_idjab').html('<option value="">Pilih Jabatan</option>');
                }
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

    //Print ke PDF 
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.print-btn').forEach(function(button) {
            button.addEventListener('click', function() {
                var id = this.getAttribute('data-id');
                window.open('print_pegawai.php?id=' + id, '_blank');
            });
        });
    });
</script>