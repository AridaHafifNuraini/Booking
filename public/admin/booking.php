    <?php
    session_start();
    require_once __DIR__ . '/../../config/autoload.php';
    if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'admin') {
        header('Location: ../auth/login.php'); exit;
    }
    $admin = new AdminController();
    if (isset($_GET['set']) && isset($_GET['id'])) {
        $admin->verifyPayment((int)$_GET['id'], $_GET['set']);
        header('Location: booking.php'); exit;
    }
    if (isset($_GET['mark_paid'])) {
        $admin->markAsPaidByCashier((int)$_GET['mark_paid']);
        header('Location: booking.php'); exit;
    }
    if (isset($_GET['change_payment']) && isset($_GET['id'])) {
        $booking_id = (int)$_GET['id'];
        $booking = $admin->getBookingById($booking_id);
        if (!$booking) {
            header('Location: booking.php'); exit;
        }
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_payment_method'])) {
            $new_method = $_POST['new_payment_method'];
            $admin->updateBookingPaymentMethod($booking_id, $new_method);
            header('Location: booking.php'); exit;
        }
        
        ?>
        <!doctype html>
        <html>
        <head>
            <title>Change Payment Method</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        </head>
        <body>
        <div class="container mt-5">
            <h4>Change Payment Method for Booking #<?php echo $booking_id; ?></h4>
            <form method="post">
                <div class="mb-3">
                    <label for="new_payment_method" class="form-label">New Payment Method</label>
                    <select name="new_payment_method" id="new_payment_method" class="form-select" required>
                        <option value="cash">Cash</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Change</button>
                <a href="booking.php" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
        </body>
        </html>
        <?php
        exit;
    }
    if (isset($_GET['delete'])) {
        $admin->deleteBooking((int)$_GET['delete']);
        header('Location: booking.php'); exit;
    }
    $list = $admin->allBooking();
    ?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Booking - Admin</title>
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
        .btn-success {
            background: #28a745;
            border: none;
            transition: all 0.3s ease;
        }
        .btn-success:hover {
            background: #218838;
        }
        .btn-warning {
            background: #ffc107;
            border: none;
            color: #212529;
            transition: all 0.3s ease;
        }
        .btn-warning:hover {
            background: #e0a800;
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
        .table {
            background: #ffffff;
            color: #212529;
            border: 1px solid #dee2e6;
        }
        .table th {
            background: #f8f9fa;
            border-color: #dee2e6;
            color: #007bff;
            font-weight: 600;
        }
        .table td {
            border-color: #dee2e6;
        }
        .table-hover tbody tr:hover {
            background: #f8f9fa;
        }
        a {
            color: #007bff;
            text-decoration: none;
        }
        a:hover {
            color: #0056b3;
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
                <i class="fas fa-list me-2"></i>Data Booking
            </h4>
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th><i class="fas fa-user me-1"></i>User</th>
                            <th><i class="fas fa-map-marker-alt me-1"></i>PlayStation</th>
                            <th><i class="fas fa-calendar me-1"></i>Tanggal</th>
                            <th><i class="fas fa-clock me-1"></i>Jam</th>
                            <th><i class="fas fa-money-bill-wave me-1"></i>Total</th>
                            <th><i class="fas fa-file-image me-1"></i>Bukti</th>
                            <th><i class="fas fa-credit-card me-1"></i>Pembayaran</th>
                            <th><i class="fas fa-info-circle me-1"></i>Status</th>
                            <th><i class="fas fa-cogs me-1"></i>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($list as $r): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($r['usernama']); ?></td>
                            <td><?php echo htmlspecialchars($r['nama_lapangan']); ?></td>
                            <td><?php echo htmlspecialchars($r['tanggal']); ?></td>
                            <td><?php echo htmlspecialchars($r['jam_mulai'] . ' - ' . $r['jam_selesai']); ?></td>
                            <td>Rp<?php echo number_format($r['total_harga']); ?></td>
                            <td>
                                <?php if($r['payment_status'] === 'dibayar' || $r['payment_status'] === 'accepted'): ?>
                                    <a target="_blank" href="receipt.php?id_booking=<?php echo $r['id_booking']; ?>">
                                        <i class="fas fa-receipt me-1"></i>Struk
                                    </a>
                                <?php elseif($r['proof_payment']): ?>
                                    <a target="_blank" href="../uploads/<?php echo htmlspecialchars($r['proof_payment']); ?>">
                                        <i class="fas fa-eye me-1"></i>Lihat
                                    </a>
                                <?php else: ?>
                                    Belum
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars(ucfirst($r['payment_method'])); ?></td>
                            <td><?php echo htmlspecialchars($r['payment_status']); ?></td>
                            <td>
                                <?php if($r['payment_method'] !== 'cash' && $r['proof_payment']): ?>
                                    <a class="btn btn-sm btn-success me-1" href="?set=accepted&id=<?php echo $r['id_booking']; ?>">
                                        <i class="fas fa-check me-1"></i>Terima
                                    </a>
                                    <a class="btn btn-sm btn-warning me-1" href="?set=rejected&id=<?php echo $r['id_booking']; ?>">
                                        <i class="fas fa-times me-1"></i>Tolak
                                    </a>
                                <?php elseif($r['payment_method'] === 'cash'): ?>
                                    <a class="btn btn-sm btn-info me-1" href="?mark_paid=<?php echo $r['id_booking']; ?>" onclick="return confirm('Mark as paid by cashier?')">
                                        <i class="fas fa-cash-register me-1"></i>Bayar Cash
                                    </a>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                                <!-- <a class="btn btn-sm btn-secondary me-1" href="?change_payment&id=<?php echo $r['id_booking']; ?>">
                                    <i class="fas fa-exchange-alt me-1"></i>Ganti Payment
                                </a> -->
                                <a class="btn btn-sm btn-danger" href="?delete=<?php echo $r['id_booking']; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus booking ini?')">
                                    <i class="fas fa-trash me-1"></i>Hapus
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="text-center mt-4">
                <a class="btn btn-secondary" href="index.php">
                    <i class="fas fa-arrow-left me-1"></i>Kembali
                </a>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
