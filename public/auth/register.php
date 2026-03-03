<?php
session_start();
require_once __DIR__ . '/../../config/autoload.php';

$auth = new AuthController();
$msg = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $res = $auth->register($_POST);
    if ($res['status']) {
        header('Location: login.php');
        exit;
    } else {
        $msg = $res['msg'];
    }
}
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar - Booking PlayStation</title>

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

<nav class="navbar navbar-expand-lg bg-white border-bottom shadow-sm">
    <div class="container">

      
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

            <div class="card p-4">
                <h4 class="text-center mb-3">
                    <i class="fas fa-user-plus me-2"></i>Registrasi Akun
                </h4>

                <?php 
                if ($msg) {
                    echo '<div class="alert alert-warning">' . htmlspecialchars($msg) . '</div>';
                }
                ?>

                <form method="post">
                    <div class="mb-3">
                        <input class="form-control" name="nama" required placeholder="Nama Lengkap">
                    </div>

                    <div class="mb-3">
                        <input class="form-control" name="email" type="email" required placeholder="Email">
                    </div>

                    <div class="mb-3">
                        <input class="form-control" name="password" type="password" required placeholder="Password">
                    </div>

                    <button class="btn btn-primary">Daftar</button>

                    <a class="link-login" href="login.php">Sudah punya akun? Login</a>
                </form>
            </div>

        </div>
    </div>
</div>

</body>
</html>
