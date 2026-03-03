    <?php
    session_start();
    require_once __DIR__ . '/../../config/autoload.php';
    if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'user') {
        header('Location: ../auth/login.php'); exit;
    }
    $userCtrl = new UserController();
    if (isset($_GET['delete'])) {
        $userCtrl->deleteBooking((int)$_GET['delete'], $_SESSION['id_user']);
        header('Location: riwayat.php'); exit;
    }
    $riwayat = $userCtrl->getRiwayat($_SESSION['id_user']);
    ?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Riwayat - Booking</title>
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
                <i class="fas fa-history me-2"></i>Riwayat Booking
            </h4>
            <p class="description mt-3">
                <i class="fas fa-info-circle me-2"></i>Lihat dan kelola riwayat pemesanan PlayStation Anda.
            </p>
        </div>

        <div class="card mt-4">
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th><i class="fas fa-calendar-day me-1"></i>Tanggal</th>
                            <th><i class="fas fa-playstation me-1"></i>PlayStation</th>
                            <th><i class="fas fa-clock me-1"></i>Jam</th>
                            <th><i class="fas fa-money-bill-wave me-1"></i>Total</th>
                            <th><i class="fas fa-file-upload me-1"></i>Bukti</th>
                            <th><i class="fas fa-credit-card me-1"></i>Pembayaran</th>
                            <th><i class="fas fa-info-circle me-1"></i>Status</th>
                            <th><i class="fas fa-cogs me-1"></i>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($riwayat as $r): ?>
                        <tr>
                            <td><?=htmlspecialchars($r['tanggal'])?></td>
                            <td><?=htmlspecialchars($r['nama_playstation'])?></td>
                            <td><?=htmlspecialchars($r['jam_mulai']).' - '.htmlspecialchars($r['jam_selesai'])?></td>
                            <td>Rp<?=number_format($r['total_harga'])?></td>
                            <td><?php if($r['payment_status'] === 'dibayar' || $r['payment_status'] === 'accepted') echo '<a target="_blank" href="receipt.php?id_booking='.$r['id_booking'].'"><i class="fas fa-receipt"></i> Struk</a>'; elseif($r['proof_payment']) echo '<a target="_blank" href="../uploads/'.htmlspecialchars($r['proof_payment']).'"><i class="fas fa-eye"></i> Lihat</a>'; else echo '<span class="text-muted">Belum</span>'; ?></td>
                            <td><?php echo htmlspecialchars(ucfirst($r['payment_method'])); ?></td>
                            <td>
                                <?php
                                $status = htmlspecialchars($r['payment_status']);
                                if ($status == 'pending') {
                                    echo '<span class="badge bg-warning text-dark">Pending</span>';
                                } elseif ($status == 'accepted') {
                                    echo '<span class="badge bg-info">Diterima</span>';
                                } elseif ($status == 'rejected') {
                                    echo '<span class="badge bg-danger">Ditolak</span>';
                                } elseif ($status == 'dibayar') {
                                    echo '<span class="badge bg-success">Dibayar</span>';
                                } else {
                                    echo $status;
                                }
                                ?>
                            </td>
                            <td>
                                <?php if(!$r['proof_payment'] && $r['payment_status'] !== 'dibayar' && $r['payment_method'] !== 'cash') echo '<a class="btn btn-sm btn-primary me-1" href="upload_proof.php?id='.$r['id_booking'].'"><i class="fas fa-upload"></i> Upload</a>'; ?>
                                <?php if($r['payment_status'] !== 'dibayar') echo '<a class="btn btn-sm btn-danger" href="?delete='.$r['id_booking'].'" onclick="return confirm(\'Apakah Anda yakin ingin menghapus booking ini?\')"><i class="fas fa-trash"></i> Hapus</a>'; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4">
            <a class="btn btn-secondary" href="index.php">
                <i class="fas fa-arrow-left me-1"></i>Kembali
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
