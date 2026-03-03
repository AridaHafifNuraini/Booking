<?php
session_start();
require_once __DIR__ . '/../../config/autoload.php';

if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'user') {
    header('Location: ../auth/login.php');
    exit;
}

$userCtrl = new UserController();
$lapangan = $userCtrl->listLapangan();

$nama_user = $_SESSION['nama'] ?? "Pengguna";

$search = $_GET['search'] ?? '';
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>User - Booking</title>

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
        .hero-box {
            background: white;
            padding: 30px;
            border-radius: 15px;
            border: 1px solid #dee2e6;
            box-shadow: 0 3px 10px rgba(0,0,0,0.07);
        }
        .hero-title {
            font-size: 26px;
            font-weight: 700;
            color: #007bff;
        }
        .hero-sub {
            font-size: 16px;
            color: #6c757d;
        }
        .search-area {
            max-width: 480px;
            margin: auto;
        }
        .card {
            border: 1px solid #dee2e6;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 18px rgba(0,0,0,0.12);
        }
        .empty-state {
            text-align: center;
            padding: 40px;
            background: #fff;
            border-radius: 15px;
            border: 1px solid #dee2e6;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }



         .btn-danger {
            background: #007bff;
            border: none;
            transition: all 0.3s ease;
        }
        .btn-danger:hover {
            background: #c82333;
        }

         .nav-link {
            color: #212529 !important;
            transition: color 0.3s ease;
        }
        .nav-link:hover {
            color: #007bff !important;
        }   
    </style>
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="#">
            <i class="fas fa-calendar-check me-2"></i>Booking
        </a>
        <div class="navbar-nav ms-auto">
            <a class="nav-link btn btn-light btn-sm me-2" href="booking.php">
                <i class="fas fa-calendar-alt me-1"></i>Booking
            </a>
            <a class="nav-link btn btn-light btn-sm me-2" href="riwayat.php">
                <i class="fas fa-history me-1"></i>Riwayat
            </a>
            <a class="btn btn-danger btn-sm" href="logout.php">
                <i class="fas fa-sign-out-alt me-1"></i>Logout
            </a>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <div class="hero-box text-center">
        <h2 class="hero-title">Selamat Datang, <?= htmlspecialchars($nama_user) ?>! 👋</h2>
        <p class="hero-sub">
            Temukan lapangan terbaik untuk aktivitasmu — cepat, mudah, dan nyaman!
        </p>
    </div>
</div>


<!-- <div class="container mt-4">
    <form method="GET" class="search-area">
        <div class="input-group shadow-sm">
            <input type="text" name="search" value="<?= htmlspecialchars($search) ?>"
                class="form-control" placeholder="Cari lapangan…">
            <button class="btn btn-primary">
                <i class="fas fa-search"></i>
            </button>
        </div>
    </form>
</div> -->


<div class="container py-4">

    <div class="row mt-4">

        <?php
        $filtered = array_filter($lapangan, function($l) use ($search) {
            return stripos($l['nama_playstation'], $search) !== false;
        });

        if (empty($filtered)): ?>
            <div class="col-12">
                <div class="empty-state">
                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">PlayStation tidak ditemukan…</h5>
                </div>
            </div>
        <?php endif; ?>

        <?php foreach($filtered as $l): ?>
            <div class="col-md-4">
                <div class="card mb-4">
                    <?php if (!empty($l['gambar'])): ?>
                        <img src="../<?= htmlspecialchars($l['gambar']) ?>" class="card-img-top" alt="PlayStation" style="height: 200px; object-fit: cover;">
                    <?php endif; ?>

                    <div class="card-body">
                        <h5 class="card-title text-primary">
                            <i class="fas fa-playstation me-2"></i><?= htmlspecialchars($l['nama_playstation']) ?>
                        </h5>
                        <p class="card-text text-muted"><?= htmlspecialchars($l['deskripsi']) ?></p>

                        <p class="card-text"><strong>Rp<?= number_format($l['harga_per_jam']) ?>/jam</strong></p>

                        <a href="booking.php" class="btn btn-primary w-100">
                            <i class="fas fa-calendar-plus me-1"></i>Pesan Sekarang
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
