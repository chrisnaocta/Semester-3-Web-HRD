<?php
session_start();
require 'config.php';
require 'login_session.php';
require 'read_departemen.php';

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

// Ambil data cuti dan relasi dengan pegawai
$result = $conn->query("SELECT p.id_cuti, p.tanggal, p.daritgl, p.sampaitgl, p.lamacuti, p.alasan, p.idpeg, g.nama, p.ditetapkan, p.pembuat_surat, g.cuti FROM cuti p JOIN pegawai g ON p.idpeg = g.idpeg ORDER BY p.id_cuti DESC");

// Dapatkan nomor urut terbaru untuk id_cuti baru
$stmt = $conn->query("SELECT id_cuti FROM cuti ORDER BY id_cuti DESC LIMIT 1");
$latestid = $stmt->fetch_assoc();
$urut = 1;
if ($latestid) {
    $latestNumber = (int) substr($latestid['id_cuti'], 8);  // Ambil 3 digit terakhir dari id_cuti
    $urut = $latestNumber + 1;
}
$newid = 'SC' . date('Y') . date('m') . str_pad($urut, 3, '0', STR_PAD_LEFT);

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
        <div class="container-fluid mt-3" style="margin-left: 15px;">
            <div class="row">
                <div class="col-md-12 d-flex justify-content-between align-items-center">
                    <h4>Cuti</h4>
                    <div>
                        <button type="button" class="btn btn-primary mb-3 mr-2" data-bs-toggle="modal" data-bs-target="#addCutiModal"><i class='fas fa-plus'></i> Add </button>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table id="cutiTable" class="table table-striped table-bordered table-hover">
                            <thead class="text-center table-primary">
                                <tr>
                                    <th>No</th>
                                    <th>No.Surat</th>
                                    <th>Id.Peg</th>
                                    <th>Nama Pegawai</th>
                                    <th>Tanggal</th>
                                    <th>Dari</th>
                                    <th>Sampai</th>
                                    <th>Lama Cuti</th>
                                    <th>Alasan</th>
                                    <th>Ditetapkan</th>
                                    <th>Pembuat</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($result && $result->num_rows > 0) {
                                    $no = 1;
                                    while ($cuti = $result->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td class='text-center'>" . $no++ . "</td>";
                                        echo "<td class='text-center'>" . htmlspecialchars($cuti['id_cuti']) . "</td>";
                                        echo "<td>" . htmlspecialchars($cuti['idpeg']) . "</td>";
                                        echo "<td>" . htmlspecialchars($cuti['nama']) . "</td>";
                                        echo "<td>" . htmlspecialchars($cuti['tanggal']) . "</td>";
                                        echo "<td>" . htmlspecialchars($cuti['daritgl']) . "</td>";
                                        echo "<td>" . htmlspecialchars($cuti['sampaitgl']) . "</td>";
                                        echo "<td>" . htmlspecialchars($cuti['lamacuti']) . "</td>";
                                        echo "<td>" . htmlspecialchars($cuti['alasan']) . "</td>";
                                        echo "<td>" . htmlspecialchars($cuti['ditetapkan']) . "</td>";
                                        echo "<td>" . htmlspecialchars($cuti['pembuat_surat']) . "</td>";
                                        echo "<td class='text-center'>";
                                        echo "<div class='d-flex justify-content-center'>";
                                        echo "<button class='btn btn-warning btn-sm edit-btn mr-1' data-bs-toggle='modal' data-bs-target='#editCutiModal' data-id='" . htmlspecialchars($cuti['id_cuti']) . "' data-tanggal='" . htmlspecialchars($cuti['tanggal']) . "' data-daritgl='" . htmlspecialchars($cuti['daritgl']) . "'data-sampaitgl='" . htmlspecialchars($cuti['sampaitgl']) . "'data-lamacuti='" . htmlspecialchars($cuti['lamacuti']) . "' data-alasan='" . htmlspecialchars($cuti['alasan']) . "' data-ditetapkan='" . htmlspecialchars($cuti['ditetapkan']) . "' data-pembuat_surat='" . htmlspecialchars($cuti['pembuat_surat']) ."'><i class='fas fa-edit'></i></button>";
                                        echo "<button class='btn btn-danger btn-sm delete-btn mr-1' data-id='" . htmlspecialchars($cuti['id_cuti']) . "'><i class='fas fa-trash'></i></button>";
                                        echo "<button class='btn btn-success btn-sm print-btn' data-id='" . htmlspecialchars($cuti['id_cuti']) . "'><i class='fas fa-print'></i></button>";
                                        echo "</div>";
                                        echo "</td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='12' class='text-center'>No data found</td></tr>";
                                }
                                ?>                                
                            </tbody>                            
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require 'footer.php'; ?>
</div>
<!-- Modal Add cuti -->
<div class="modal fade" id="addCutiModal" tabindex="-1" aria-labelledby="addCutiModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCutiModalLabel">Add Cuti</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="add_cuti.php" method="post">
                    <div class="mb-3">
                        <label for="id_cuti" class="form-label">Kode Cuti</label>
                        <input type="text" class="form-control" id="id_cuti" name="id_cuti" value="<?php echo htmlspecialchars($newid); ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="add_idpeg" class="form-label">Nama Pegawai</label>
                        <select class="form-select" id="add_idpeg" name="idpeg" required>
                            <option value="" selected disabled>Pilih Pegawai</option>
                            <?php
                            $pegawai = $conn->query("SELECT idpeg, nama FROM pegawai ORDER BY nama");
                            while ($row = $pegawai->fetch_assoc()) {
                                echo "<option value='" . htmlspecialchars($row['idpeg']) . "'>" . htmlspecialchars($row['nama']) . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="tanggal" class="form-label">Tanggal Surat</label>
                        <input type="date" class="form-control" id="tanggal" name="tanggal" required>
                    </div>
                    <div class="mb-3">
                        <label for="daritgl" class="form-label">Tanggal Mulai</label>
                        <input type="date" class="form-control" id="daritgl" name="daritgl" required>
                    </div>
                    <div class="mb-3">
                        <label for="lamacuti" class="form-label">Lama Cuti</label>
                        <input type="number" class="form-control" id="add_lamacuti" name="lamacuti" min="1" max="12" required>
                    </div>
                    <div class="mb-3">
                        <label for="alasan" class="form-label">Alasan</label>
                        <textarea class="form-control" id="alasan" name="alasan" rows="5" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="ditetapkan" class="form-label">Ditetapkan di</label>
                        <input type="text" class="form-control" id="ditetapkan" name="ditetapkan" required>
                    </div>
                    <div class="mb-3">
                        <label for="pembuat_surat" class="form-label">Diterbitkan oleh</label>
                        <input type="text" class="form-control" id="pembuat_surat" name="pembuat_surat" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Not Done
$(document).ready(function() {
    $('#add_idpeg').change(function() {
        var idpeg = $(this).val();
        if (idpeg !== "") {
            $.ajax({
                url: 'get_cuti.php',
                method: 'POST',
                data: {idpeg: idpeg},
                beforeSend: function() {
                    $('#add_lamacuti').html('<option>Loading...</option>');
                },
                success: function(response) {
                    $('#add_lamacuti').attr(response);
                },
                error: function() {
                    $('#add_lamacuti').html('<option>Error loading jabatan</option>');
                }
            });
        } else {
            $('#add_lamacuti').attr("placeholder","Max=12");
        }
    });
});
</script>

<!-- Modal Edit Cuti -->
<div class="modal fade" id="editCutiModal" tabindex="-1" aria-labelledby="editCutiModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCutiModalLabel">Edit Cuti</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="edit_cuti.php" method="post">
                    <div class="mb-3">
                        <label for="edit_id_cuti" class="form-label">Kode Cuti</label>
                        <input type="text" class="form-control" id="edit_id_cuti" name="id_cuti" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="edit_idpeg" class="form-label">Nama Pegawai</label>
                        <select class="form-select" id="edit_idpeg" name="idpeg" required>
                            <!-- Nama pegawai akan diisi melalui JavaScript -->
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_tanggal" class="form-label">Tanggal Surat</label>
                        <input type="date" class="form-control" id="edit_tanggal" name="tanggal" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_daritgl" class="form-label">Tanggal Mulai</label>
                        <input type="date" class="form-control" id="edit_daritgl" name="daritgl" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_lamacuti" class="form-label">Lama Cuti</label>
                        <input type="number" class="form-control" id="edit_lamacuti" name="lamacuti" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_alasan" class="form-label">Alasan</label>
                        <textarea class="form-control" id="edit_alasan" name="alasan" rows="5" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="ditetapkan" class="form-label">Ditetapkan di</label>
                        <input type="text" class="form-control" id="edit_ditetapkan" name="ditetapkan" required>
                    </div>
                    <div class="mb-3">
                        <label for="pembuat_surat" class="form-label">Diterbitkan oleh</label>
                        <input type="text" class="form-control" id="edit_pembuat_surat" name="pembuat_surat" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('click', function (e) {
        if (e.target && e.target.matches('.edit-btn')) {
            const id_cuti = e.target.getAttribute('data-id');
            const tanggal = e.target.getAttribute('data-tanggal');
            const daritgl = e.target.getAttribute('data-daritgl');
            const lamacuti = e.target.getAttribute('data-lamacuti');
            const alasan = e.target.getAttribute('data-alasan');
            const ditetapkan = e.target.getAttribute('data-ditetapkan');
            const pembuat_surat = e.target.getAttribute('data-pembuat_surat');
            const idpeg = e.target.closest('tr').querySelector('td:nth-child(3)').innerText; // Ambil id pegawai dari kolom tabel
            const namaPegawai = e.target.closest('tr').querySelector('td:nth-child(4)').innerText; // Ambil nama pegawai dari kolom tabel

            document.getElementById('edit_id_cuti').value = id_cuti;
            document.getElementById('edit_tanggal').value = tanggal;
            document.getElementById('edit_daritgl').value = daritgl;
            document.getElementById('edit_lamacuti').value = lamacuti;
            document.getElementById('edit_alasan').value = alasan;
            document.getElementById('edit_ditetapkan').value = ditetapkan;
            document.getElementById('edit_pembuat_surat').value = pembuat_surat;

            // Set nama pegawai yang sesuai di combobox
            const editPegawaiSelect = document.getElementById('edit_idpeg');
            editPegawaiSelect.innerHTML = `<option value="${idpeg}">${namaPegawai}</option>`;

            // Ketika combobox di klik, load seluruh nama pegawai dari database
            editPegawaiSelect.addEventListener('click', function() {
                $.ajax({
                    url: 'get_pegawai_list.php', // Buat file terpisah untuk mengambil list pegawai
                    method: 'GET',
                    success: function(response) {
                        editPegawaiSelect.innerHTML = response;
                        editPegawaiSelect.value = idpeg; // Pastikan pegawai yang dipilih tetap terpilih
                    },
                    error: function(xhr, status, error) {
                        console.error('Failed to fetch pegawai list:', error);
                    }
                });
            }, { once: true }); // Only load the pegawai list once when clicked
        }
    });
</script>

<!-- Bootstrap JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- DataTables -->
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

<script>
    // Menampilkan semua fasilitas pada tabel pada bootstrap
    $('#cutiTable').DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false
    });

    // Menangani modal edit
    document.addEventListener('click', function (e) {
        if (e.target && e.target.matches('.edit-btn')) {
            const id_cuti = e.target.getAttribute('data-id');
            const tanggal = e.target.getAttribute('data-tanggal');
            const daritgl = e.target.getAttribute('data-daritgl');
            const lamacuti = e.target.getAttribute('data-lamacuti');
            const alasan = e.target.getAttribute('data-alasan');
            const ditetapkan = e.target.getAttribute('data-ditetapkan');
            const pembuat_surat = e.target.getAttribute('data-pembuat_surat');

            document.getElementById('edit_id_cuti').value = id_cuti;
            document.getElementById('edit_tanggal').value = tanggal;
            document.getElementById('edit_daritgl').value = daritgl;
            document.getElementById('edit_lamacuti').value = lamacuti;
            document.getElementById('edit_alasan').value = alasan;
            document.getElementById('edit_ditetapkan').value = ditetapkan;
            document.getElementById('edit_pembuat_surat').value = pembuat_surat;
        }
    });

    <?php if ($message): ?>
        Swal.fire({
            title: '<?php echo $message['type'] === 'success' ? 'Success!' : 'Error!'; ?>',
            text: '<?php echo $message['text']; ?>',
            icon: '<?php echo $message['type'] === 'success' ? 'success' : 'error'; ?>'
        });
    <?php endif; ?>

    // Handle delete button click
    $(document).on('click', '.delete-btn', function() {
        var id_cuti = $(this).data('id');
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
                    url: 'delete_cuti.php',
                    type: 'POST',
                    data: { id_cuti: id_cuti },
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
                window.open('print_cuti.php?id=' + id, '_blank');
            });
        });
    });
</script>
