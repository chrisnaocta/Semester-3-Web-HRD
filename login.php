<?php
require 'user_session.php';
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Menggunakan prepared statement untuk mencegah SQL injection
    $stmt = $conn->prepare("SELECT iduser, username, password FROM login WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Bind hasil
        //Menyimpan data dari database ke variabel
        // iduser di variabel $iduser, username di $db_username, password di $db_password
        $stmt->bind_result($iduser, $db_username, $db_password);
        $stmt->fetch();

         // Verify if the username fetched matches the input username
        if ($username == $db_username){
            //Verify the password
            if (password_verify($password, $db_password)){
                $_SESSION['iduser'] = $iduser;
                header("Location: index.php");
                exit(); 
            }else{
                $error = "Password salah.";
            }
        }else{
            $error = "username salah.";
        }
    } else {
        $error = "Username tidak ditemukan.";
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .input-group .icon {
            background: #e0e0e0;
            padding: 10px;
            border-top-left-radius: 4px;
            border-bottom-left-radius: 4px;
            border: 1px solid #ddd;
            border-right: 0;
            height: 20px;
            width: 30px;
            margin-bottom: 5px;
            margin-right: 7px;
        }

        .input-group .icon {
            text-align:center;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-form">
            <h2>Login</h2>
            <form method="post" action="">
                <div class="input-group">
                    <span class="icon "><i class="fas fa-user"></i></span>
                    <input type="text" id="username" name="username" placeholder="Username" required>
                </div>
                <div class="input-group">
                    <span class="icon">&#128274;</span>
                    <input type="password" id="password" name="password" placeholder="Password" required>
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