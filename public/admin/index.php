<?php
session_start();
require_once __DIR__ . '/../../config/autoload.php';

if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../auth/login.php'); 
    exit;
}
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin - Booking</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <style>
        body {
            background: #f8f9fa;
            color: #212529;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
        }
        .navbar {
            background: #ffffff !important;
            border-bottom: 1px solid #dee2e6;
        }
        .navbar-brand {
            color: #007bff !important;
            font-weight: 600;
        }
        .btn-light {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            color: #212529;
            transition: all 0.3s ease;
        }
        .btn-light:hover {
            background: #e9ecef;
        }
        .btn-danger {
            background: #007bff;
            border: none;
            transition: all 0.3s ease;
        }
        .btn-danger:hover {
            background: #c82333;
        }
        .dashboard-card {
            background: #ffffff;
            border: 1px solid #dee2e6;
            border-radius: 12px;
            padding: 35px;
            margin-top: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }
        .welcome-text {
            color: #007bff;
            font-weight: 700;
            font-size: 28px;
        }
        .description {
            color: #6c757d;
            font-size: 16px;
        }
    </style>
</head>

<body>

<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="#">
            <i class="fas fa-tachometer-alt me-2"></i>Booking - Admin
        </a>
        <div class="navbar-nav ms-auto">
            <a class="nav-link btn btn-light btn-sm me-2" href="PlayStation.php">
                <i class="fas fa-map-marker-alt me-1"></i>PlayStation
            </a>
            <a class="nav-link btn btn-light btn-sm me-2" href="booking.php">
                <i class="fas fa-calendar-check me-1"></i>Booking
            </a>
            <a class="nav-link btn btn-light btn-sm me-2" href="cashier.php">
                <i class="fas fa-cash-register me-1"></i>Kasir
            </a>
            <a class="btn btn-danger btn-sm" href="logout.php">
                <i class="fas fa-sign-out-alt me-1"></i>Logout
            </a>
        </div>
    </div>
</nav>

<div class="container py-5">

   
    <div class="dashboard-card text-center">
        <h2 class="welcome-text">
            Selamat Datang Admin! 🎉
        </h2>
        <p class="description mt-2">
            “Tetap semangat bekerja hari ini!  
            Setiap keputusanmu membantu membuat sistem lebih rapi, nyaman, dan mudah digunakan.  
            Terus melangkah, kamu hebat!” 🚀💪
        </p>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
