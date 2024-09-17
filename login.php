<?php
session_start();
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $username = $_POST['username'];

    // Menggunakan prepared statement untuk mencegah SQL injection
    $stmt = $conn->prepare("SELECT iduser, email, username FROM login WHERE email = ? AND username = ?");
    $stmt->bind_param("ss", $email, $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Bind hasil
        $stmt->bind_result($iduser, $db_email, $db_username);
        $stmt->fetch();

        // Jika email dan username cocok
        if ($email == $db_email && $username == $db_username) {
            $_SESSION['iduser'] = $iduser;
            header("Location: index.php");
            exit();
        } else {
            $error = "username salah.";
        }
    } else {
        $error = "Email tidak ditemukan.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>    
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/LAT_HRD/CSS/login.css">
</head>
<body>
    <div class="login-container">
        <div class="login-form">
            <h2>Login</h2>
            <form method="post" action="">
                <div class="input-group">
                    <span class="icon">&#9993;</span>
                    <input type="email" id="email" name="email" placeholder="Email" required>
                </div>
                <div class="input-group">
                    <span class="icon">&#128274;</span>
                    <input type="username" id="username" name="username" placeholder="username" required>
                </div>
                <button type="submit">Login</button>
                <?php if (isset($error)) echo '<div class="error">' . $error . '</div>'; ?>
            </form>
        </div>
        <div class="register-button">
            <a href="register.php" class="btn btn-link">Register</a>
        </div>
    </div>
</body>
</html>