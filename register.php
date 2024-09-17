<?php
session_start();
require 'config.php';

// Check if there's an error message in the session
$error_message = isset($_SESSION['error']) ? $_SESSION['error'] : '';
unset($_SESSION['error']); // Clear the error message after displaying

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $username = $_POST['username'];
    $code = $_POST['code'];

    // Expected code value
    $expected_code = '3223';

    // Check if the code is correct
    if ($code !== $expected_code) {
        $_SESSION['error'] = 'Wrong Code';
        header("Location: register.php");
        exit();
    }

    // Check if the email or username already exists
    $stmt = $conn->prepare("SELECT iduser FROM login WHERE email = ? OR username = ?");
    $stmt->bind_param("ss", $email, $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Email or username already exists
        $_SESSION['error'] = 'Email or username already exists';
        $stmt->close();
        header("Location: register.php");
        exit();
    } else {
        // Proceed with insertion
        $stmt->close();

        // Menggunakan prepared statement untuk mencegah SQL injection
        $stmt = $conn->prepare("INSERT INTO login (email, username) VALUES (?, ?)");
        $stmt->bind_param("ss", $email, $username);

        if ($stmt->execute()) {
            // Get the auto-generated iduser
            $last_id = $stmt->insert_id;
            
            // Redirect to a success page with iduser
            header("Location: success.php?iduser=" . urlencode($last_id));
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <!-- Bootstrap 5 source -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">


    <link rel="stylesheet" href="/LAT_HRD/CSS/register.css">
    <link rel="stylesheet" href="/LAT_HRD/CSS/register_2.css">

</head>
<body>
    <div class="container">

        <div class="card card-info">
            <div class="card-header text-center">
              <h3 class="card-title"><strong>Register</strong></h3>
            </div>
            <!-- /.card-header -->

            

            <!-- form start -->
            <form class="form-horizontal" method="POST" action="register.php">
              <div class="card-body">
                
                    <?php if ($error_message): ?>
                        <div class="error_message1">
                            <b><?php echo htmlspecialchars($error_message); ?></b>
                        </div>
                    <?php endif; ?>

                <div class="form-group row">
                  <div class="input-group">
                    <span class="icon"><h5>&#9993;</h5></span>
                    <input type="email" class="form-control" id="email" placeholder="Email" name="email" required>
                </div>
            </div>
            
            <div class="form-group row">
                <div class="input-group">
                    <span class="icon"><h5><i class="fas fa-user"></i></h5></span>
                    <input type="text" name="username" id="username" class="form-control" placeholder="Username" required>
                </div>
            </div>
            
            <div class="form-group row">
                <div class="input-group">
                    <span class="icon"><h5><i class='fas fa-key'></i></h5></span>
                    <input type="text" class="form-control" id="code" placeholder="Code" name="code" required>
                  </div>
                </div>
    
              </div>
              <!-- /.card-body -->
              
                <div class="card-footer text-center">
                    <button type="submit" class="btn btn-info" style="margin-top: 0px;">
                        Register
                    </button>

                    <div class="mt-2">
                        <a href="login.php" class="btn btn-link" >
                            Login
                        </a>
                    </div>
                </div>
                <!-- /.card-footer -->
            </form>
          </div>
    </div>

</body>
</html>