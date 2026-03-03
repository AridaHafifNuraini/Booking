<?php
session_start();
require_once __DIR__ . '/../../config/autoload.php';
if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'user') {
    header('Location: ../auth/login.php');
    exit;
}

$userCtrl = new UserController();
$message = '';

if (isset($_GET['id_booking'])) {
    $id_booking = (int)$_GET['id_booking'];
    $booking = $userCtrl->getBookingReceipt($id_booking, $_SESSION['id_user']);

    if (!$booking) {
        $message = 'Booking tidak ditemukan atau tidak dapat diakses.';
    }
} else {
    $message = 'ID booking tidak valid.';
}
?>

<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Struk Pembayaran - User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
            color: #212529;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
        }
        .receipt-container {
            background: #ffffff;
            border: 2px solid #dee2e6;
            border-radius: 10px;
            padding: 30px;
            margin: 30px auto;
            max-width: 600px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .receipt-header {
            text-align: center;
            border-bottom: 2px solid #007bff;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .receipt-title {
            color: #007bff;
            font-weight: 700;
            font-size: 24px;
            margin-bottom: 10px;
        }
        .receipt-subtitle {
            color: #6c757d;
            font-size: 14px;
        }
        .receipt-body {
            margin-bottom: 20px;
        }
        .receipt-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e9ecef;
        }
        .receipt-row:last-child {
            border-bottom: none;
            font-weight: 600;
            font-size: 18px;
            color: #007bff;
        }
        .receipt-label {
            font-weight: 500;
        }
        .receipt-value {
            text-align: right;
        }
        .receipt-footer {
            text-align: center;
            border-top: 2px solid #007bff;
            padding-top: 20px;
            margin-top: 20px;
            color: #6c757d;
            font-size: 12px;
        }
        .btn-print {
            background: #007bff;
            border: none;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
        }
        .btn-print:hover {
            background: #0056b3;
        }
        @media print {
            body {
                background: white;
            }
            .receipt-container {
                box-shadow: none;
                border: 1px solid #000;
            }
            .btn-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if ($message): ?>
            <div class="alert alert-danger mt-4">
                <i class="fas fa-exclamation-triangle me-1"></i><?php echo htmlspecialchars($message); ?>
            </div>
        <?php elseif ($booking): ?>
            <div class="receipt-container">
                <div class="receipt-header">
                    <div class="receipt-title">
                        <i class="fas fa-receipt me-2"></i>STRUK PEMBAYARAN - <?php echo htmlspecialchars($_SESSION['nama']); ?>
                    </div>
                    <div class="receipt-subtitle">
                        Booking PlayStation Futsal - <?php echo ucfirst($booking['payment_method'] ?? 'Cash'); ?>
                    </div>
                </div>

                <div class="receipt-body">
                    <div class="receipt-row">
                        <span class="receipt-label">ID Booking:</span>
                        <span class="receipt-value">#<?php echo $booking['id_booking']; ?></span>
                    </div>
                    <div class="receipt-row">
                        <span class="receipt-label">Nama Customer:</span>
                        <span class="receipt-value"><?php echo htmlspecialchars($_SESSION['nama']); ?> (ID: <?php echo $_SESSION['id_user']; ?>)</span>
                    </div>
                    <div class="receipt-row">
                        <span class="receipt-label">Lapangan:</span>
                        <span class="receipt-value"><?php echo htmlspecialchars($booking['nama_lapangan']); ?></span>
                    </div>
                    <div class="receipt-row">
                        <span class="receipt-label">Tanggal:</span>
                        <span class="receipt-value"><?php echo date('d/m/Y', strtotime($booking['tanggal'])); ?></span>
                    </div>
                    <div class="receipt-row">
                        <span class="receipt-label">Waktu:</span>
                        <span class="receipt-value"><?php echo $booking['jam_mulai'] . ' - ' . $booking['jam_selesai']; ?></span>
                    </div>
                    <div class="receipt-row">
                        <span class="receipt-label">Metode Pembayaran:</span>
                        <span class="receipt-value"><?php echo ucfirst($booking['payment_method'] ?? 'Cash'); ?></span>
                    </div>
                    <div class="receipt-row">
                        <span class="receipt-label">Status:</span>
                        <span class="receipt-value"><?php echo ucfirst($booking['payment_status']); ?></span>
                    </div>
                    <div class="receipt-row">
                        <span class="receipt-label">Total Pembayaran:</span>
                        <span class="receipt-value">Rp <?php echo number_format($booking['total_harga'], 0, ',', '.'); ?></span>
                    </div>
                </div>

                <div class="receipt-footer">
                    <div>Terima kasih atas pembayaran Anda!</div>
                    <div>Booking berhasil diproses pada <?php echo date('d/m/Y H:i:s'); ?></div>
                </div>

                <div class="text-center">
                    <button class="btn-print" onclick="window.print()">
                        <i class="fas fa-print me-1"></i>Cetak Struk
                    </button>
                    <a href="riwayat.php" class="btn btn-secondary ms-2">
                        <i class="fas fa-arrow-left me-1"></i>Kembali
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
