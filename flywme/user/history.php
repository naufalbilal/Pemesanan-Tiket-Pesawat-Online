<?php
include '../includes/config.php';
include '../includes/functions.php';
include '../includes/auth.php';

if (!isLoggedIn() || isAdmin()) {
    redirect('../index.php');
}

$user_id = $_SESSION['user_id'];

// Get user bookings
$stmt = $pdo->prepare("
    SELECT b.*, f.flight_number, f.departure_city, f.arrival_city, f.departure_date, f.departure_time, f.arrival_time, a.name as airline_name
    FROM bookings b 
    JOIN flights f ON b.flight_id = f.id 
    JOIN airlines a ON f.airline_id = a.id 
    WHERE b.user_id = ? 
    ORDER BY b.booking_date DESC
");
$stmt->execute([$user_id]);
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pemesanan - FlyWme</title>
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
                   
                    <li><a href="../search.php">Cari Penerbangan</a></li>
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li><a href="history.php" class="active">Riwayat</a></li>
                    <li><a href="profile.php">Profile</a></li>
                    <li><a href="../process/logout.php">Logout</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <main class="dashboard">
        <div class="container">
            <div class="dashboard-header">
                <h1>Riwayat Pemesanan</h1>
                <p>Semua tiket yang pernah Anda pesan</p>
            </div>

            <div class="bookings-list">
                <?php if($bookings): ?>
                    <?php foreach($bookings as $booking): ?>
                    <div class="booking-card">
                        <div class="booking-header">
                            <div class="booking-info">
                                <h3><?php echo $booking['airline_name']; ?> - <?php echo $booking['flight_number']; ?></h3>
                                <p class="booking-code">Kode Booking: <?php echo $booking['booking_code']; ?></p>
                            </div>
                            <div class="booking-status">
                                <span class="badge badge-<?php echo $booking['status']; ?>">
                                    <?php echo ucfirst($booking['status']); ?>
                                </span>
                            </div>
                        </div>
                        
                        <div class="booking-details">
                            <div class="route">
                                <div class="departure">
                                    <strong><?php echo formatTime($booking['departure_time']); ?></strong>
                                    <p><?php echo $booking['departure_city']; ?></p>
                                    <small><?php echo formatDate($booking['departure_date']); ?></small>
                                </div>
                                <div class="duration">
                                    <div class="line"></div>
                                    <div class="flight-time">
                                        <?php 
                                        $duration = strtotime($booking['arrival_time']) - strtotime($booking['departure_time']);
                                        echo gmdate('H:i', $duration);
                                        ?>
                                    </div>
                                </div>
                                <div class="arrival">
                                    <strong><?php echo formatTime($booking['arrival_time']); ?></strong>
                                    <p><?php echo $booking['arrival_city']; ?></p>
                                    <small><?php echo formatDate($booking['departure_date']); ?></small>
                                </div>
                            </div>
                            
                            <div class="passenger-info">
                                <p><strong>Penumpang:</strong> <?php echo $booking['passenger_name']; ?></p>
                                <p><strong>Email:</strong> <?php echo $booking['passenger_email']; ?></p>
                                <p><strong>Telepon:</strong> <?php echo $booking['passenger_phone']; ?></p>
                            </div>
                            
                            <div class="booking-footer">
                                <div class="total-price">
                                    <strong>Total: Rp <?php echo number_format($booking['total_price'], 0, ',', '.'); ?></strong>
                                </div>
                                <div class="booking-actions">
                                    <?php if($booking['status'] == 'paid'): ?>
                                        <a href="../process/download_ticket.php?booking_id=<?php echo $booking['id']; ?>" class="btn btn-primary">Download Tiket</a>
                                    <?php elseif($booking['status'] == 'pending'): ?>
                                        <a href="../process/payment.php?booking_id=<?php echo $booking['id']; ?>" class="btn btn-success">Lanjutkan Pembayaran</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-state">
                        <h3>Belum ada pemesanan</h3>
                        <p>Anda belum melakukan pemesanan tiket pesawat.</p>
                        <a href="../search.php" class="btn btn-primary">Cari Penerbangan</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2025 FlyWme. Muhammad Naufal Bilal Syam (owner).</p>
        </div>
    </footer>
</body>
</html>