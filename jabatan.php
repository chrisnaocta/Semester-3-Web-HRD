<?php
session_start();
require 'config.php';
require 'login_session.php';

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

// Ambil data dari tabel jabatan
$result = $conn->query("
    SELECT jabatan.idjab, jabatan.jabatan, departemen.departemen, departemen.iddep
    FROM jabatan
    JOIN departemen ON jabatan.iddep = departemen.iddep
    ORDER BY jabatan.idjab ASC
");

// Dapatkan nomor urut terbaru untuk idjab baru
$stmt = $conn->query("SELECT idjab FROM jabatan ORDER BY idjab DESC LIMIT 1");
$latestidjab = $stmt->fetch_assoc();
$urut = 1;
if ($latestidjab) {
    $latestNumber = (int) substr($latestidjab['idjab'], 1);
    $urut = $latestNumber + 1;
}
$newidjab = 'J' . str_pad($urut, 3, '0', STR_PAD_LEFT);

// Simpan pesan ke variabel dan hapus dari session
$message = null;
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}
?>

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
                    <h4>Jabatan</h4>
                    <div>
                        <button type="button" class="btn btn-primary mb-3 mr-2" data-bs-toggle="modal" data-bs-target="#addjabatanModal"><i class='fas fa-plus'></i> Add </button>
                        <button type="button" class="btn btn-secondary mb-3" id="printButton"><i class='fas fa-print'></i> Print</button>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table id="jabatanTable" class="table table-striped table-bordered table-hover">    
                            <thead class="text-center table-danger">
                                <tr>
                                    <th style="width: 1%;">No</th>
                                    <th style="width: 5%;">Kode</th>
                                    <th>Nama Jabatan</th>
                                    <th>Nama Departemen</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($result && $result->num_rows > 0) {
                                    $no = 1;
                                    foreach($result as $jabatan) {
                                        echo "<tr>";
                                        echo "<td class='text-center'>" . $no++ . "</td>";
                                        echo "<td class='text-center'>" . htmlspecialchars($jabatan['idjab']) . "</td>";
                                        echo "<td>" . htmlspecialchars($jabatan['jabatan']) . "</td>";
                                        echo "<td>" . htmlspecialchars($jabatan['departemen']) . "</td>";
                                        echo "<td class='text-center'>";
                                        echo "<div class='d-flex justify-content-center'>";
                                        echo "<button class='btn btn-warning btn-sm edit-btn mr-1'
                                        data-bs-toggle='modal'
                                        data-bs-target='#editjabatanModal'
                                        data-id='" . htmlspecialchars($jabatan['idjab']) . "'
                                        data-jabatan='" . htmlspecialchars($jabatan['jabatan']) .  "'
                                        data-departemen='" . htmlspecialchars($jabatan['departemen']) .  "'
                                        data-iddep='" . htmlspecialchars($jabatan['iddep']) . "'>   
                                        <i class='fas fa-edit'></i> 
                                        </button>";
                                        echo "<button class='btn btn-danger btn-sm delete-btn' data-id='" . htmlspecialchars($jabatan['idjab']) . "'><i class='fas fa-trash'></i> </button>";
                                        echo "</div>";
                                        echo "</td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='6' class='text-center'>No data found</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php require 'footer.php'; ?>
</div>

<!-- Modal Add jabatan -->
<div class="modal fade" id="addjabatanModal" tabindex="-1" aria-labelledby="addjabatanModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addjabatanModalLabel">Add jabatan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="add_jabatan.php" method="post">
                    <div class="mb-3">
                        <label for="idjab" class="form-label">Kode jabatan</label>
                        <input type="text" class="form-control" id="add_idjab" name="idjab" value="<?php echo htmlspecialchars($newidjab); ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="jabatan" class="form-label">Nama jabatan</label>
                        <input type="text" class="form-control" id="add_jabatan" name="jabatan" required>
                    </div>
                    <div class="mb-3">
                        <label for="departemen" class="form-label">Nama Departemen</label>
                        <select class="form-control" id="add_departemen" name="departemen" required>
                            <option value="">Pilih Departemen</option>
                            <?php
                            // Ambil data departemen dari database
                            $departemenResult = $conn->query("SELECT iddep, departemen FROM departemen");
                            while ($departemen = $departemenResult->fetch_assoc()) {
                                echo "<option value='" . $departemen['iddep'] . "'>" . htmlspecialchars($departemen['departemen']) . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Add</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit jabatan -->
<div class="modal fade" id="editjabatanModal" tabindex="-1" aria-labelledby="editjabatanModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editjabatanModalLabel">Edit jabatan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="edit_jabatan.php" method="post">
                    <div class="mb-3">
                        <label for="edit_idjab" class="form-label">Kode jabatan</label>
                        <input type="text" class="form-control" id="edit_idjab" name="idjab" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="edit_jabatan" class="form-label">Nama jabatan</label>
                        <input type="text" class="form-control" id="edit_jabatan" name="jabatan" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_departemen" class="form-label">Nama Departemen</label>
                        <select class="form-control" id="edit_departemen" name="departemen" required>   
                            <option value="">Pilih Departemen</option>
                            <?php
                            // Ambil data departemen dari database
                            $departemenResult = $conn->query("SELECT iddep, departemen FROM departemen");
                            while ($departemen = $departemenResult->fetch_assoc()) {
                                // Use selected attribute if the iddep matches the current iddep
                                $selected = (isset($current_iddep) && $current_iddep == $departemen['iddep']) ? "selected" : "";
                                echo "<option value='" . $departemen['iddep'] . "' $selected>" . htmlspecialchars($departemen['departemen']) . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap 5 source -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Include jQuery first -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Include Bootstrap and DataTables -->
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function() {
        // Adjust DataTables' scrolling to avoid overlapping with the footer
        function adjustTableHeight() {
            var footerHeight = $('footer').outerHeight();
            var tableHeight = 'calc(100vh - 290px - ' + footerHeight + 'px)';

            $('#jabatanTable').DataTable().destroy();
            $('#jabatanTable').DataTable({
                "pagingType": "simple_numbers",
                "scrollY": tableHeight,
                "scrollCollapse": true,
                "paging": true
            });
        }

        // Call the function to adjust table height initially
        adjustTableHeight();

        // Adjust table height on window resize
        $(window).resize(function() {
            adjustTableHeight();
        });

        // Populate edit modal with data
        $('#editjabatanModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var idjab = button.data('id');
            var jabatan = button.data('jabatan');
            var iddep = button.data('iddep');
            
            var modal = $(this);
            modal.find('#edit_idjab').val(idjab);
            modal.find('#edit_jabatan').val(jabatan);
            modal.find('#edit_departemen').val(iddep);
        });

        // Show message if it exists in the session
        <?php if ($message): ?>
            Swal.fire({
                title: '<?php echo $message['type'] === 'success' ? 'Success!' : 'Error!'; ?>',
                text: '<?php echo $message['text']; ?>',
                icon: '<?php echo $message['type'] === 'success' ? 'success' : 'error'; ?>'
            });
        <?php endif; ?>

        // Handle delete button click
        $(document).on('click', '.delete-btn', function() {
            var idjab = $(this).data('id');
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
                        url: 'delete_jabatan.php',
                        type: 'POST',
                        data: { idjab: idjab },
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
        $(document).ready(function() {
            // Other existing scripts...

            // Handle print button click
            $('#printButton').click(function() {
                window.open ('print_jabatan.php', '_blank');
            });
        });
    });
</script>
</body>
</html>
