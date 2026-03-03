    <?php
    session_start();
    require_once __DIR__ . '/../../config/autoload.php';
    if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'user') {
        header('Location: ../auth/login.php'); exit;
    }
    $userCtrl = new UserController();
    $lapangan = $userCtrl->listLapangan();
    $msg = null;
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id_user = $_SESSION['id_user'];
        $id_PlayStation = (int)$_POST['PlayStation'];
        $tanggal = $_POST['tanggal'];
        $jam_mulai = $_POST['jam_mulai'];
        $jam_selesai = $_POST['jam_selesai'];
        $payment_method = $_POST['payment_method'];
        $durasi = (strtotime($jam_selesai) - strtotime($jam_mulai)) / 3600;
        if ($durasi <= 0) { $msg = 'Jam selesai harus lebih besar dari jam mulai.'; }
        else {
            $db = Database::getInstance()->getConnection();
            $stmt = $db->prepare("SELECT harga_per_jam FROM PlayStation WHERE id=:id");
            $stmt->execute([':id'=>$id_PlayStation]); $h = $stmt->fetch();
            $total = $durasi * $h['harga_per_jam'];
            $result = $userCtrl->createBooking([
                'id_user' => $id_user,
                'id_PlayStation' => $id_PlayStation,
                'tanggal' => $tanggal,
                'jam_mulai' => $jam_mulai,
                'jam_selesai' => $jam_selesai,
                'total' => $total,
                'payment_method' => $payment_method
            ]);
            if ($result['success']) {
                $msg = 'Booking berhasil dengan pembayaran cash!';
            } else {
                $msg = $result['message'];
            }
        }
    }
    ?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Booking - Booking</title>
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
        .description {
            color: #6c757d;
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
        .table {
            border-radius: 10px;
            overflow: hidden;
        }
        .table thead th {
            background: #007bff;
            color: white;
            border: none;
        }
        .table tbody tr:hover {
            background: #f8f9fa;
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
                <i class="fas fa-calendar-plus me-2"></i>Form Booking PlayStation
            </h4>
            <p class="description mt-3">
                <i class="fas fa-info-circle me-2"></i>Silakan pilih lapangan dan waktu booking Anda.
            </p>
        </div>

        <div class="card mt-4">
            <div class="card-body">
                <?php if($msg) echo '<div class="alert alert-info"><i class="fas fa-info-circle me-2"></i>'.htmlspecialchars($msg).'</div>'; ?>
                <form method="post">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="id_PlayStation" class="form-label">
                                <i class="fas fa-playstation me-1"></i>Pilih PlayStation
                            </label>
                            <select name="id_PlayStation" id="id_PlayStation" class="form-select" required>
                                <?php foreach($lapangan as $l) echo '<option value="'.$l['id'].'">'.htmlspecialchars($l['nama_playstation']).' (Rp'.number_format($l['harga_per_jam']).'/jam)</option>'; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="tanggal" class="form-label">
                                <i class="fas fa-calendar-day me-1"></i>Tanggal Booking
                            </label>
                            <input type="date" name="tanggal" id="tanggal" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label for="jam_mulai" class="form-label">
                                <i class="fas fa-clock me-1"></i>Jam Mulai
                            </label>
                            <input type="time" name="jam_mulai" id="jam_mulai" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label for="jam_selesai" class="form-label">
                                <i class="fas fa-clock me-1"></i>Jam Selesai
                            </label>
                            <input type="time" name="jam_selesai" id="jam_selesai" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label for="payment_method" class="form-label">
                                <i class="fas fa-credit-card me-1"></i>Metode Pembayaran
                            </label>
                            <select name="payment_method" id="payment_method" class="form-select" required>
                                <option value="cash">Cash</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-check me-1"></i>Booking Sekarang
                            </button>
                        </div>
                    </div>
                </form>
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
