<?php
session_start();
require 'config.php';
require 'login_session.php';

$iduser = $_SESSION['iduser'];

// Ambil data user dari database
$stmt = $conn->prepare("SELECT iduser, username, email, foto FROM login WHERE iduser = ?");
$stmt->bind_param("i", $iduser);
$stmt->execute();   
$stmt->bind_result($iduser, $username, $email, $foto);
$stmt->fetch();
$stmt->close();

// Ambil data nama usaha dan alamat dari database
$stmt = $conn->prepare("SELECT nama, alamat FROM namausaha LIMIT 1");
$stmt->execute();
$stmt->bind_result($namaUsaha, $alamatUsaha);
$stmt->fetch();
$stmt->close();
?>

<!-- Bootstrap 5 source -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- DataTables -->
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

<style>
    table th {
        width: 150px;
    }
</style>

<?php require 'head.php'?>
<div class="wrapper">
    <header>
        <h4 style="text-align:center;"><?php echo htmlspecialchars($namaUsaha ?? ''); ?></h4>
        <p style="text-align:center;"><?php echo htmlspecialchars($alamatUsaha ?? ''); ?></p>
    </header>

    <?php include 'sidebar.php'; ?>
    <div class="content" id="content">
        <div class="container-sm mt-3" style="margin-left:15px">
            <div class="row">
                <div class="col-md-12 d-flex justify-content-between align-items-center">
                    <h4>Profile</h4>
                </div>
                <div class="pt-3 col-md-8">
                    <table class="table table-bordered table-striped table-hover">
                        <tr>
                            <th>ID</th>
                            <td><?php echo htmlspecialchars($iduser); ?> </td>
                        </tr>
                        <tr>
                            <th>Username</th>
                            <td><?php echo htmlspecialchars($username); ?> </td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td><?php echo htmlspecialchars($email); ?> </td>
                        </tr>
                        <tr>
                            <th>Profile Picture</th>
                            <td>
                                <img src="foto/<?php echo htmlspecialchars($foto);?>" alt="User Photo" class="user-photo">
                                <form action="post" action="upload_profile.php">
                                </form>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="pt-3 col-md">
                </div>
                <div>
                <button class='btn btn-warning btn-sm edit-btn mr-1' 
                        data-bs-toggle='modal' 
                        data-bs-target='#editprofileModal'
                        data-iduser='<?php echo htmlspecialchars($iduser); ?>'
                        data-username='<?php echo htmlspecialchars($username); ?>'
                        data-email='<?php echo htmlspecialchars($email); ?>'
                        >
                    <i class='fas fa-edit'></i> Edit Profile Picture
                </button>
                </div>
            </div>
        </div>
    </div>
    <?php require 'footer.php'; ?>
</div>

<!-- Modal Edit profile -->
<div class="modal fade" id="editprofileModal" tabindex="-1" aria-labelledby="editprofileModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editprofileModalLabel">Edit profile picture</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="edit_profile.php" method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="profile-picture">Profile picture</label> <br>
                        <input type="file" class="form-control" name="profile-picture" id="profile-picture" accept=".jpg, .jpeg, .png" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Enter your password to update</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>

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
            const iduser = this.getAttribute('data-iduser');
            const username = this.getAttribute('data-username');
            const email = this.getAttribute('data-email');

            // Set values in the modal
            document.getElementById('edit_iduser').value = iduser;
            document.getElementById('edit_username').value = username;
            document.getElementById('edit_email').value = email;
        });
    });
});
</script>
