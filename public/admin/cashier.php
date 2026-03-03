<?php
session_start();
require_once __DIR__ . '/../../config/autoload.php';
if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../auth/login.php'); 
    exit;
}
$admin = new AdminController();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_customer = trim($_POST['nama_customer']);
    $id_booking = (int)$_POST['id_booking'];

    $bookings = $admin->allBooking();
    $selected_booking = null;
    foreach ($bookings as $b) {
        if ($b['id_booking'] == $id_booking) {
            $selected_booking = $b;
            break;
        }
    }

    if ($selected_booking && strtolower($selected_booking['usernama']) === strtolower($nama_customer)) {
        $admin->markAsPaidByCashier($id_booking);
        header('Location: receipt.php?id_booking=' . $id_booking);
        exit;
    } else {
        $message = '⚠️ Nama customer tidak cocok dengan booking yang dipilih.';
    }
}

$bookings = $admin->allBooking();
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kasir - Admin</title>
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
        .btn-primary {
            background: #007bff;
            border: none;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background: #0069d9;
        }
        .btn-secondary {
            background: #6c757d;
            border: none;
            transition: all 0.3s ease;
        }
        .btn-secondary:hover {
            background: #5a6268;
        }
        .container-wrapper {
            background: #ffffff;
            border: 1px solid #dee2e6;
            border-radius: 10px;
            padding: 30px;
            margin-top: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .page-title {
            color: #007bff;
            font-weight: 600;
        }
        .form-label {
            font-weight: 500;
        }
        .alert-info {
            background-color: #e7f1ff;
            border-color: #b6d4fe;
            color: #084298;
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

    <div class="container py-4">
        <div class="container-wrapper">
            <h4 class="page-title mb-4">
                <i class="fas fa-cash-register me-2"></i>Halaman Kasir
            </h4>

            <?php if ($message): ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-1"></i><?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <form method="post">
                <div class="mb-3">
                    <label for="nama_customer" class="form-label">
                        <i class="fas fa-user me-1"></i>Nama Customer
                    </label>
                    <input type="text" class="form-control" id="nama_customer" name="nama_customer" required>
                </div>

                <div class="mb-3">
                    <label for="id_booking" class="form-label">
                        <i class="fas fa-calendar-alt me-1"></i>Pilih Booking
                    </label>
                    <select class="form-select" id="id_booking" name="id_booking" required>
                        <option value="">Pilih Booking</option>
                        <?php foreach ($bookings as $b): ?>
                            <option value="<?php echo $b['id_booking']; ?>">
                                <?php echo htmlspecialchars($b['usernama'] . ' - ' . $b['nama_lapangan'] . ' - ' . $b['tanggal'] . ' (' . $b['jam_mulai'] . '-' . $b['jam_selesai'] . ')'); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-check-circle me-1"></i>Submit
                </button>
                <a class="btn btn-secondary ms-2" href="index.php">
                    <i class="fas fa-arrow-left me-1"></i>Kembali
                </a>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
