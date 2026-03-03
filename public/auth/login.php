<?php
session_start();
require_once __DIR__ . '/../../config/autoload.php';
$auth = new AuthController();
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $res = $auth->login($_POST);
    if ($res['status']) {
        $u = $res['user'];
        $_SESSION['id_user'] = $u['id_user'];
        $_SESSION['nama'] = $u['nama'];
        $_SESSION['role'] = $u['role'];

        if ($u['role'] === 'admin') header('Location: ../admin/index.php');
        else header('Location: ../user/index.php');
        exit;
    } else {
        $error = $res['msg'];
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Booking PlayStation</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <style>
        body {
            background: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
        }
        .login-card {
            background: #ffffff;
            border: 1px solid #dee2e6;
            border-radius: 10px;
            padding: 30px;
            margin-top: 70px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
        .btn-warning {
            background: #ffc107;
            border: none;
            transition: .3s;
            width: 100%;
            padding: 10px;
            font-size: 16px;
        }
        .btn-warning:hover {
            background: #e0a800;
        }
        .form-control {
            height: 45px;
        }
        .navbar-brand {
            color: #007bff !important;
            font-weight: 600;
        }
        .btn-light {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
        }
        .btn-light:hover {
            background: #e9ecef;
        }
    </style>
</head>

<body>

   <!-- NAVBAR -->
<nav class="navbar navbar-expand-lg bg-white border-bottom shadow-sm">
    <div class="container">

        <!-- Brand -->
        <a class="navbar-brand" href="../index.php">
            <i class="fas fa-gamepad me-2"></i>Booking PlayStation
        </a>

       
        <a class="btn btn-light" href="../index.php">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>

    </div>
</nav>


  
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">

                <div class="login-card">
                    <h4 class="text-center fw-bold mb-3">Login Akun</h4>

                    <?php 
                    if ($error) {
                        echo '<div class="alert alert-warning"><i class="fas fa-exclamation-circle me-2"></i>' . 
                             htmlspecialchars($error) . '</div>';
                    }
                    ?>

                    <form method="post">

                        <div class="mb-3">
                            <input class="form-control" name="email" type="email" placeholder="Email" required>
                        </div>

                        <div class="mb-3">
                            <input class="form-control" name="password" type="password" placeholder="Password" required>
                        </div>

                        <button class="btn btn-warning mb-2">Login</button>

                        <a href="register.php" class="btn btn-light w-100">
                            <i class="fas fa-user-plus me-1"></i> Buat Akun Baru
                        </a>

                    </form>
                </div>

            </div>
        </div>
    </div>

</body>
</html>
