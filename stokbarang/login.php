<?php
require 'function.php';

//Cek login, terdaftar apa kagak
if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (!$conn) {
        die("Koneksi gagal: " . mysqli_connect_error());
    }

    $stmt = mysqli_prepare($conn, "SELECT * FROM login WHERE email = ? AND password = ?");
    mysqli_stmt_bind_param($stmt, "ss", $email, $password);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $hitung = mysqli_num_rows($result);

    // DEBUG: lihat hasil
    // echo "Rows ditemukan: $hitung<br>";
    // echo "Email: $email<br>Password: $password<br>";

    if ($hitung > 0) {
        $_SESSION['log'] = 'True';
        header('location:index.php');
        exit();
    } else {
        echo "<script>alert('Login gagal! Email atau password salah.');</script>";
    }

    mysqli_stmt_close($stmt);
}


?>

<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <title>Login</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
        <link href="css/styles.css" rel="stylesheet" />
        <style>
            .centered-btn {
                position: absolute;
                top: 75%;
                left: 50%;
                transform: translate(-50%, -50%);
            }
            .login-card {
                display: none; /* Sembunyikan form login awalnya */
            }
        </style>
    </head>
    <body class="bg-image">
        <!-- Tombol Login kecil -->
        <div class="centered-btn" id="loginButtonContainer">
            <button class="btn btn-primary btn-sm" id="showLoginFormBtn">Login</button>
        </div>

        <!-- Form Login -->
        <div class="container login-card" id="loginFormContainer">
            <div class="row justify-content-center">
                <div class="col-lg-5">
                    <div class="card shadow-lg border-0 rounded-lg mt-5">
                        <div class="card-header">
                            <h3 class="text-center font-weight-light my-4">Login</h3>
                        </div>
                        <div class="card-body">
                            <form method="post">
                                <div class="form-floating mb-3">
                                    <input class="form-control" name="email" id="inputEmail" type="email" placeholder="name@example.com" required/>
                                    <label for="inputEmail">Email</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <input class="form-control" name="password" id="inputPassword" type="password" placeholder="Password" required/>
                                    <label for="inputPassword">Password</label>
                                </div>
                                <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                    <button class="btn btn-primary" name="login">Login</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Script -->
        <script>
            document.getElementById('showLoginFormBtn').addEventListener('click', function () {
                document.getElementById('loginButtonContainer').style.display = 'none';
                document.getElementById('loginFormContainer').style.display = 'block';
            });
        </script>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    </body>
</html>
