    <?php
    session_start();
    require_once __DIR__ . '/../../config/autoload.php';
    if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'admin') {
        header('Location: ../auth/login.php'); exit;
    }
    $admin = new AdminController();
    $msg = null;
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nama'])) {
        $gambarPath = null;
        if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../uploads/';
            $fileName = uniqid() . '_' . basename($_FILES['gambar']['name']);
            $targetFile = $uploadDir . $fileName;
            $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
            if (in_array($fileType, ['jpg', 'jpeg', 'png', 'gif']) && $_FILES['gambar']['size'] <= 5000000) { // 5MB limit
                if (move_uploaded_file($_FILES['gambar']['tmp_name'], $targetFile)) {
                  
                    $maxWidth = 500;
                    $maxHeight = 140;
                    $imageInfo = getimagesize($targetFile);
                    $width = $imageInfo[0];
                    $height = $imageInfo[1];
                    if ($width > $maxWidth || $height > $maxHeight) {
                        $ratio = min($maxWidth / $width, $maxHeight / $height);
                        $newWidth = round($width * $ratio);
                        $newHeight = round($height * $ratio);
                        $newImage = imagecreatetruecolor($newWidth, $newHeight);
                        if ($fileType == 'png') {
                            $source = imagecreatefrompng($targetFile);
                            imagealphablending($newImage, false);
                            imagesavealpha($newImage, true);
                        } elseif ($fileType == 'gif') {
                            $source = imagecreatefromgif($targetFile);
                        } else {
                            $source = imagecreatefromjpeg($targetFile);
                        }
                        imagecopyresampled($newImage, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
                        if ($fileType == 'png') {
                            imagepng($newImage, $targetFile);
                        } elseif ($fileType == 'gif') {
                            imagegif($newImage, $targetFile);
                        } else {
                            imagejpeg($newImage, $targetFile, 90);
                        }
                        imagedestroy($newImage);
                        imagedestroy($source);
                    }
                    $gambarPath = 'uploads/' . $fileName;
                } else {
                    $msg = 'Gagal mengupload gambar.';
                }
            } else {
                $msg = 'Format gambar tidak valid atau ukuran terlalu besar.';
            }
        }
        if (!$msg) {
            $admin->addLapangan($_POST['nama'], (int)$_POST['harga'], $_POST['deskripsi'], $gambarPath);
            $msg = 'PlayStation ditambahkan.';
        }
    }
    if (isset($_GET['hapus'])) {
        $admin->deleteLapangan((int)$_GET['hapus']);
        header('Location: PlayStation.php'); exit;
    }
    $list = $admin->allLapangan();
    ?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PlayStation - Admin</title>
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
            color: #212529;
        }
        .btn-danger {
            background: #dc3545;
            border: none;
            transition: all 0.3s ease;
        }
        .btn-danger:hover {
            background: #c82333;
        }
        .dashboard-card {
            background: #ffffff;
            border: 1px solid #dee2e6;
            border-radius: 10px;
            padding: 30px;
            margin-top: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .welcome-text {
            color: #007bff;
            font-weight: 600;
        }
        .card {
            border: 1px solid #dee2e6;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 15px rgba(0,0,0,0.1);
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
                <i class="fas fa-tachometer-alt me-2"></i>Booking - Admin
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link btn btn-light btn-sm me-2" href="PlayStation.php">
                    <i class="fas fa-playstation     me-1"></i>PlayStation
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
        <div class="dashboard-card">
            <h4 class="welcome-text">
                <i class="fas fa-playstation me-2"></i>Kelola PlayStation
            </h4>
            <p class="description mt-3">
                <i class="fas fa-info-circle me-2"></i>Tambah, lihat, dan kelola PlayStation yang tersedia.
            </p>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-plus-circle me-2"></i>Tambah PlayStation Baru
                </h5>
            </div>
            <div class="card-body">
                <?php if($msg) echo '<div class="alert alert-info"><i class="fas fa-info-circle me-2"></i>'.htmlspecialchars($msg).'</div>'; ?>
                <form method="post" enctype="multipart/form-data">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label for="nama" class="form-label">
                                <i class="fas fa-tag me-1"></i>Nama PlayStation
                            </label>
                            <input name="nama" id="nama" class="form-control" placeholder="Masukkan nama PlayStation" required>
                        </div>
                        <div class="col-md-2">
                            <label for="harga" class="form-label">
                                <i class="fas fa-dollar-sign me-1"></i>Harga per Jam
                            </label>
                            <input name="harga" id="harga" class="form-control" type="number" placeholder="Rp" required>
                        </div>
                        <div class="col-md-3">
                            <label for="deskripsi" class="form-label">
                                <i class="fas fa-info-circle me-1"></i>Deskripsi
                            </label>
                            <input name="deskripsi" id="deskripsi" class="form-control" placeholder="Deskripsi singkat">
                        </div>
                        <div class="col-md-2">
                            <label for="gambar" class="form-label">
                                <i class="fas fa-image me-1"></i>Gambar
                            </label>
                            <input name="gambar" id="gambar" class="form-control" type="file" accept="image/*">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button class="btn btn-primary w-100">
                                <i class="fas fa-plus me-1"></i>Tambah
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-list me-2"></i>Daftar PlayStation
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <?php foreach($list as $l): ?>
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100">
                                <?php if($l['gambar']): ?>
                                    <img src="../<?php echo htmlspecialchars($l['gambar']); ?>" class="card-img-top" alt="Gambar PlayStation" style="height: 200px; object-fit: cover;">
                                <?php else: ?>
                                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                        <span class="text-muted">
                                            <i class="fas fa-image fa-2x"></i><br>Tidak ada gambar
                                        </span>
                                    </div>
                                <?php endif; ?>
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <i class="fas fa-playstation me-2"></i><?php echo htmlspecialchars($l['nama_playstation']); ?>
                                    </h5>
                                    <p class="card-text"><strong>Rp <?php echo number_format($l['harga_per_jam']); ?> / jam</strong></p>
                                    <p class="card-text"><?php echo htmlspecialchars($l['deskripsi'] ?: 'Tidak ada deskripsi'); ?></p>
                                    <a class="btn btn-outline-danger btn-sm w-100" href="?hapus=<?php echo $l['id']; ?>" onclick="return confirm('Yakin ingin menghapus?')">
                                        <i class="fas fa-trash me-1"></i>Hapus
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <a class="btn btn-secondary" href="index.php">
                <i class="fas fa-arrow-left me-1"></i>Kembali ke Dashboard
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
