    <?php
    session_start();
    require_once __DIR__ . '/../../config/autoload.php';
    if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'user') {
        header('Location: ../auth/login.php'); exit;
    }
    $id = (int)($_GET['id'] ?? 0);
    $msg = null;

    $userCtrl = new UserController();
    $booking = $userCtrl->getBookingById($id, $_SESSION['id_user']);
    if (!$booking || $booking['payment_method'] === 'cash') {
        header('Location: riwayat.php');
        exit;
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['proof'])) {
        $f = $_FILES['proof'];
        if ($f['error'] === 0) {
            $ext = pathinfo($f['name'], PATHINFO_EXTENSION);
            $allowed = ['jpg','jpeg','png','gif'];
            if (!in_array(strtolower($ext), $allowed)) $msg = 'Format file tidak diperbolehkan.';
            else {
                $new = time().'_'.bin2hex(random_bytes(4)).'.'.$ext;
                $dest = __DIR__.'/../uploads/'.$new;
                if (!is_dir(dirname($dest))) {
                    mkdir(dirname($dest), 0755, true);
                }
                move_uploaded_file($f['tmp_name'], $dest);
                $userCtrl = new UserController();
                $userCtrl->uploadProof($id, $new);
                $msg = 'Bukti pembayaran berhasil diunggah. <a href="receipt.php?id_booking=' . $id . '" target="_blank" class="alert-link">Klik di sini untuk melihat struk pembelian</a>. Menunggu verifikasi admin.';
            }
        } else $msg = 'Upload gagal.';
    }
    ?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Upload Bukti - Booking</title>
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

    <div class="container py-5">
        <div class="dashboard-card">
            <h4 class="welcome-text">
                <i class="fas fa-file-upload me-2"></i>Upload Bukti Pembayaran
            </h4>
            <p class="description mt-3">
                <i class="fas fa-info-circle me-2"></i>Silakan unggah bukti pembayaran untuk booking Anda.
            </p>
        </div>

        <div class="card mt-4">
            <div class="card-body">
                <?php if($msg) echo '<div class="alert alert-info"><i class="fas fa-info-circle me-2"></i>'.htmlspecialchars($msg).'</div>'; ?>
                <form method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="proof" class="form-label">
                            <i class="fas fa-file-image me-1"></i>Pilih File Bukti Pembayaran
                        </label>
                        <input type="file" name="proof" id="proof" class="form-control" required accept="image/*">
                        <div class="form-text">Format yang diperbolehkan: JPG, JPEG, PNG, GIF</div>
                    </div>
                    <button class="btn btn-primary" type="submit">
                        <i class="fas fa-upload me-1"></i>Upload Bukti
                    </button>
                </form>
            </div>
        </div>

        <div class="mt-4">
            <button class="btn btn-secondary" onclick="history.back()">
                <i class="fas fa-arrow-left me-1"></i>Kembali ke Halaman Sebelumnya
            </button>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
