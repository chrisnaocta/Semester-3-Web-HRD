<?php
session_start();
require 'config.php';
require 'login_session.php';
require 'read_namausaha.php';

// Ambil data dari tabel namausaha
$usaha = query("SELECT * FROM namausaha");

$iduser = $_SESSION['iduser'];

// Ambil data user dari database
$stmt = $conn->prepare("SELECT username, email FROM login WHERE iduser = ?");
$stmt->bind_param("i", $iduser);
$stmt->execute();
$stmt->bind_result($username, $email);
$stmt->fetch();
$stmt->close();

// Ambil data nama usaha dan alamat dari database
$stmt = $conn->prepare("SELECT nama, alamat FROM namausaha LIMIT 1");
$stmt->execute();
$stmt->bind_result($namaUsaha, $alamatUsaha);
$stmt->fetch();
$stmt->close();

// Ambil data dari tabel namausaha
// $result = mysqli_query($conn, "SELECT * FROM namausaha");

// Dapatkan nomor urut terbaru untuk iddep baru
$stmt = $conn->query("SELECT idusaha FROM namausaha ORDER BY idusaha DESC LIMIT 1");
$latestidusaha= $stmt->fetch_assoc();
$urut = 1;
if ($latestidusaha) {
    $latestNumber = (int) substr($latestidusaha['idusaha'], 1);
    $urut = $latestNumber + 1;
}
$newidusaha = 'U' . str_pad($urut, 3, '0', STR_PAD_LEFT);


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

<!-- DataTables -->
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>


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
                    <h4>Identitas Usaha</h4>
                    <div>
                        <button type="button" class="btn btn-primary mb-3 mr-2" data-bs-toggle="modal" data-bs-target="#adddusahaModal"><i class='fas fa-plus'></i> Add </button>
                        <button type="button" class="btn btn-secondary mb-3" id="printButton"><i class='fas fa-print'></i> Print</button>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table style="border: 3px;" class="table table-hover table-bordered">    
                            <thead class="text-center table-primary" >
                                <tr>
                                    <th style="width: 1px;">No</th> 
                                    <th style="width: 1%;">Id</th>
                                    <th>Nama Usaha</th>
                                    <th>Alamat</th>
                                    <th>No Telepon</th>
                                    <th>Fax</th>
                                    <th>Email</th>
                                    <th>NPWP</th>
                                    <th>Bank</th>
                                    <th>No Account</th>
                                    <th>Atas Nama</th>
                                    <th>Pimpinan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                <?php $no = 1;
                                foreach($usaha as $row):
                                ?>
                                    <tr>
                                        <td> <?php echo $no++; ?> </td>
                                        <td> <?php echo $row["idusaha"]; ?> </td>
                                        <td> <?php echo $row["nama"]; ?> </td>
                                        <td> <?php echo $row["alamat"]; ?> </td>
                                        <td> <?php echo $row["notelepon"]; ?> </td>
                                        <td> <?php echo $row["fax"]; ?> </td>
                                        <td> <?php echo $row["email"]; ?> </td>
                                        <td> <?php echo $row["npwp"]; ?> </td>
                                        <td> <?php echo $row["bank"]; ?> </td>
                                        <td> <?php echo $row["noaccount"]; ?> </td>
                                        <td> <?php echo $row["atasnama"]; ?> </td>
                                        <td> <?php echo $row["pimpinan"]; ?> </td>
                                        <td>
                                        <button class='btn btn-warning btn-sm edit-btn mr-1' 
                                                data-bs-toggle='modal' 
                                                data-bs-target='#editusaha'
                                                data-idusaha='<?php echo htmlspecialchars($row['idusaha']); ?>'
                                                data-nama='<?php echo htmlspecialchars($row['nama']); ?>'
                                                data-alamat='<?php echo htmlspecialchars($row['alamat']); ?>'
                                                data-notelepon='<?php echo htmlspecialchars($row['notelepon']); ?>'
                                                data-fax='<?php echo htmlspecialchars($row['fax']); ?>'
                                                data-email='<?php echo htmlspecialchars($row['email']); ?>'
                                                data-npwp='<?php echo htmlspecialchars($row['npwp']); ?>'
                                                data-bank='<?php echo htmlspecialchars($row['bank']); ?>'
                                                data-noaccount='<?php echo htmlspecialchars($row['noaccount']); ?>'
                                                data-atasnama='<?php echo htmlspecialchars($row['atasnama']); ?>'
                                                data-pimpinan='<?php echo htmlspecialchars($row['pimpinan']); ?>'>
                                            <i class='fas fa-edit'></i> Edit
                                        </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php require 'footer.php'; ?>
</div>

<!-- Modal Add Usaha -->
<div class="modal fade" id="adddusahaModal" tabindex="-1" aria-labelledby="addusahaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editusahaModalLabel">Add Usaha</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="add_usaha.php" method="post">
                    <div class="mb-3">
                    <label for="iddep" class="form-label">Id usaha</label>
                        <input type="text" class="form-control" id="idusaha" name="idusaha" value="<?php echo htmlspecialchars($newidusaha); ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="add_nama" class="form-label">Nama Usaha</label>
                        <input type="text" class="form-control" id="add_nama" name="nama" required>
                    </div>
                    <div class="mb-3">
                        <label for="add_alamat" class="form-label">Alamat</label>
                        <input type="text" class="form-control" id="add_alamat" name="alamat"required>
                    </div>
                    <div class="mb-3">
                        <label for="add_notelepon" class="form-label">No Telepon</label>
                        <input type="text" class="form-control" id="add_notelepon" minlength="10" maxlength="12" name="notelepon"required>
                    </div>
                    <div class="mb-3">
                        <label for="add_fax" class="form-label">Fax</label>
                        <input type="text" class="form-control" id="add_fax" minlength="7" maxlength="8" name="fax"required>
                    </div>
                    <div class="mb-3">
                        <label for="add_email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="add_email" name="email"required>
                    </div>
                    <div class="mb-3">
                        <label for="add_npwp" class="form-label">NPWP</label>
                        <input type="text" class="form-control" id="add_npwp" minlength="16" maxlength="16" name="npwp"required>
                    </div>
                    <div class="mb-3">
                        <label for="add_bank" class="form-label">Bank</label>
                        <select class="form-control" id="add_bank" name="bank" required>
                            <option value="" disabled selected>Select a bank</option>
                            <option>BCA</option>
                            <option>BRI</option>
                            <option>BNI</option>
                            <option>Mandiri</option>
                            <option>BTN</option>
                            <option>MEGA</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="add_noaccount" class="form-label">No Account</label>
                        <input type="text" class="form-control" id="add_noaccount" minlength="10" maxlength="15" name="noaccount"required>
                    </div>
                    <div class="mb-3">
                        <label for="add_atasnama" class="form-label">Atas Nama</label>
                        <input type="text" class="form-control" id="add_atasnama" name="atasnama" required>
                    </div>
                    <div class="mb-3">
                        <label for="add_pimpinan" class="form-label">Pimpinan</label>
                        <input type="text" class="form-control" id="add_pimpinan" name="pimpinan" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Function to prevent entering numbers in input
    document.querySelectorAll('input[id="add_pimpinan"] , input[id="add_atasnama"]').forEach(function(inputField) {
        inputField.addEventListener('input', function(e) {
            // Remove any non-letter characters (numbers, symbols, etc.) as the user types
            this.value = this.value.replace(/[^a-zA-Z\s]/g, '');
        });
    });

    document.querySelectorAll('input[id="add_notelepon"], input[id="add_fax"], input[id="add_npwp"], input[id="add_noaccount"]').forEach(function(inputField) {
        inputField.addEventListener('input', function(e) {
            // Replace any non-digit characters with an empty string
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    });
</script>

<!-- Modal Edit Usaha -->
<div class="modal fade" id="editusaha" tabindex="-1" aria-labelledby="editusahaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editusahaModalLabel">Edit Usaha</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="edit_usaha.php" method="post">
                    <div class="mb-3">
                        <label for="edit_idusaha" class="form-label">Id Usaha</label>
                        <input type="text" class="form-control" id="edit_idusaha" name="idusaha" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="edit_nama" class="form-label">Nama Usaha</label>
                        <input type="text" class="form-control" id="edit_nama" name="nama">
                    </div>
                    <div class="mb-3">
                        <label for="edit_alamat" class="form-label">Alamat</label>
                        <input type="text" class="form-control" id="edit_alamat" name="alamat">
                    </div>
                    <div class="mb-3">
                        <label for="edit_notelepon" class="form-label">No Telepon</label>
                        <input type="text" class="form-control" id="edit_notelepon" name="notelepon">
                    </div>
                    <div class="mb-3">
                        <label for="edit_fax" class="form-label">Fax</label>
                        <input type="text" class="form-control" id="edit_fax" name="fax">
                    </div>
                    <div class="mb-3">
                        <label for="edit_email" class="form-label">Email</label>
                        <input type="text" class="form-control" id="edit_email" name="email">
                    </div>
                    <div class="mb-3">
                        <label for="edit_npwp" class="form-label">NPWP</label>
                        <input type="text" class="form-control" id="edit_npwp" name="npwp">
                    </div>
                    <div class="mb-3">
                        <label for="edit_bank" class="form-label">Bank</label>
                        <select class="form-control" id="edit_bank" name="bank">
                            <option value="" disabled selected>Select a bank</option>
                            <option>BCA</option>
                            <option>BRI</option>
                            <option>BNI</option>
                            <option>Mandiri</option>
                            <option>BTN</option>
                            <option>MEGA</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_noaccount" class="form-label">No Account</label>
                        <input type="text" class="form-control" id="edit_noaccount" name="noaccount">
                    </div>
                    <div class="mb-3">
                        <label for="edit_atasnama" class="form-label">Atas Nama</label>
                        <input type="text" class="form-control" id="edit_atasnama" name="atasnama">
                    </div>
                    <div class="mb-3">
                        <label for="edit_pimpinan" class="form-label">Pimpinan</label>
                        <input type="text" class="form-control" id="edit_pimpinan" name="pimpinan">
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
            const idusaha = this.getAttribute('data-idusaha');
            const nama = this.getAttribute('data-nama');
            const alamat = this.getAttribute('data-alamat');
            const notelepon = this.getAttribute('data-notelepon');
            const fax = this.getAttribute('data-fax');
            const email = this.getAttribute('data-email');
            const npwp = this.getAttribute('data-npwp');
            const bank = this.getAttribute('data-bank');
            const noaccount = this.getAttribute('data-noaccount');
            const atasnama = this.getAttribute('data-atasnama');
            const pimpinan = this.getAttribute('data-pimpinan');

            // Set values in the modal
            document.getElementById('edit_idusaha').value = idusaha;
            document.getElementById('edit_nama').value = nama;
            document.getElementById('edit_alamat').value = alamat;
            document.getElementById('edit_notelepon').value = notelepon;
            document.getElementById('edit_fax').value = fax;
            document.getElementById('edit_email').value = email;
            document.getElementById('edit_npwp').value = npwp;
            document.getElementById('edit_bank').value = bank;
            document.getElementById('edit_noaccount').value = noaccount;
            document.getElementById('edit_atasnama').value = atasnama;
            document.getElementById('edit_pimpinan').value = pimpinan;
        });
    });
});
</script>