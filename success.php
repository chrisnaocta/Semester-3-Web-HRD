<?php
// success.php
if (isset($_GET['iduser'])) {
    $iduser = htmlspecialchars($_GET['iduser']);
} else {
    // Handle the case where iduser is not provided
    $iduser = 'No ID available';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Success</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="stylesheet" href="/LAT_HRD/CSS/success_1.css">
    <link rel="stylesheet" href="/LAT_HRD/CSS/success_2.css">
</head>
<body>
    <div class="container">
        <div class="card card-success">
            <div class="card-header text-center">
              <h3 class="card-title">Registration Successful</h3>
            </div>
            <!-- /.card-header -->

            <div class="card-body text-center">
                <p>Your registration was successful!</p>
                <p><strong>Your User ID is: <?php echo $iduser; ?></strong></p>
                <a href="register.php" class="btn btn-primary">Register Another User</a>
            </div>
        </div>
    </div>
</body>
</html>