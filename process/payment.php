<?php
include '../includes/config.php';
include '../includes/functions.php';
include '../includes/auth.php';

if (!isLoggedIn()) {
    redirect('../login.php');
}

if (!isset($_GET['booking_id'])) {
    redirect('../search.php');
}

$booking_id = $_GET['booking_id'];
$user_id = $_SESSION['user_id'];

// Get booking details
$stmt = $pdo->prepare("
    SELECT b.*, f.flight_number, f.departure_city, f.arrival_city, f.departure_date, a.name as airline_name
    FROM bookings b 
    JOIN flights f ON b.flight_id = f.id 
    JOIN airlines a ON f.airline_id = a.id 
    WHERE b.id = ? AND b.user_id = ? AND b.status = 'pending'
");
$stmt->execute([$booking_id, $user_id]);
$booking = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$booking) {
    die('Booking tidak ditemukan atau sudah diproses');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $payment_method = $_POST['payment_method'];
    
    // Simulate payment processing
    $payment_success = true; // In real app, this would be from payment gateway
    
    if ($payment_success) {
        // Update booking status
        $stmt = $pdo->prepare("UPDATE bookings SET status = 'paid', payment_date = NOW() WHERE id = ?");
        $stmt->execute([$booking_id]);
        
        // Record payment
        $stmt = $pdo->prepare("INSERT INTO payments (booking_id, payment_method, amount, payment_status) VALUES (?, ?, ?, 'success')");
        $stmt->execute([$booking_id, $payment_method, $booking['total_price']]);
        
        header("Location: ../user/dashboard.php?success=payment_success&booking_id=$booking_id");
        exit();
    } else {
        header("Location: payment.php?booking_id=$booking_id&error=payment_failed");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran - FlyWme</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="nav-container">
                <div class="nav-logo">
                    <h2><a href="../index.php">FlyWme</a></h2>
                </div>
                <ul class="nav-menu">
                    <li><a href="../index.php">Home</a></li>
                    <li><a href="../search.php">Cari Penerbangan</a></li>
                    <li><a href="../user/dashboard.php">Dashboard</a></li>
                    <li><a href="../process/logout.php">Logout</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <main style="padding: 100px 0 50px;">
        <div class="container">
            <h1>Pembayaran</h1>
            
            <div class="payment-container">
                <div class="payment-summary">
                    <h3>Ringkasan Pembayaran</h3>
                    <div class="summary-card">
                        <p><strong>Kode Booking:</strong> <?php echo $booking['booking_code']; ?></p>
                        <p><strong>Penerbangan:</strong> <?php echo $booking['airline_name']; ?> - <?php echo $booking['flight_number']; ?></p>
                        <p><strong>Rute:</strong> <?php echo $booking['departure_city']; ?> → <?php echo $booking['arrival_city']; ?></p>
                        <p><strong>Tanggal:</strong> <?php echo formatDate($booking['departure_date']); ?></p>
                        <p><strong>Total Pembayaran:</strong> Rp <?php echo number_format($booking['total_price'], 0, ',', '.'); ?></p>
                    </div>
                </div>

                <div class="payment-form">
                    <h3>Pilih Metode Pembayaran</h3>
                    <form action="" method="POST">
                        <div class="form-group">
                            <label>
                                <input type="radio" name="payment_method" value="bank_transfer" required> Transfer Bank
                            </label>
                        </div>
                        <div class="form-group">
                            <label>
                                <input type="radio" name="payment_method" value="credit_card" required> Kartu Kredit
                            </label>
                        </div>
                        <div class="form-group">
                            <label>
                                <input type="radio" name="payment_method" value="e_wallet" required> E-Wallet
                            </label>
                        </div>
                        
                        <div class="payment-instruction" style="background: #f8fafc; padding: 1rem; border-radius: 8px; margin: 1rem 0;">
                            <h4>Instruksi Pembayaran:</h4>
                            <p>Setelah mengklik "Bayar Sekarang", sistem akan memproses pembayaran Anda secara otomatis.</p>
                            <p>Tiket elektronik akan tersedia untuk diunduh setelah pembayaran berhasil.</p>
                        </div>
                        
                        <button type="submit" class="btn btn-success" style="width: 100%; font-size: 1.2rem; padding: 15px;">
                            Bayar Sekarang - Rp <?php echo number_format($booking['total_price'], 0, ',', '.'); ?>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2024 FlyWme. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>