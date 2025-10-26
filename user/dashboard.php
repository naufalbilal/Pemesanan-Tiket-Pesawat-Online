<?php
include '../includes/config.php';
include '../includes/auth.php';

if (!isLoggedIn() || isAdmin()) {
    redirect('../index.php');
}

$user_id = $_SESSION['user_id'];

// Get today's flights
$today = date('Y-m-d');
$stmt = $pdo->prepare("
    SELECT f.*, a.name as airline_name, a.code as airline_code 
    FROM flights f 
    JOIN airlines a ON f.airline_id = a.id 
    WHERE f.departure_date >= ? 
    ORDER BY f.departure_date, f.departure_time 
    LIMIT 5
");
$stmt->execute([$today]);
$flights = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get user bookings
$stmt = $pdo->prepare("
    SELECT b.*, f.flight_number, f.departure_city, f.arrival_city, f.departure_date, a.name as airline_name
    FROM bookings b 
    JOIN flights f ON b.flight_id = f.id 
    JOIN airlines a ON f.airline_id = a.id 
    WHERE b.user_id = ? 
    ORDER BY b.booking_date DESC 
    LIMIT 5
");
$stmt->execute([$user_id]);
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - FlyWme</title>
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
                    <li><a href="dashboard.php" class="active">Dashboard</a></li>
                    <li><a href="history.php">Riwayat</a></li>
                    <li><a href="profile.php">Profile</a></li>
                    <li><a href="../process/logout.php">Logout</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <main class="dashboard">
        <div class="container">
            <div class="dashboard-header">
                <h1>Dashboard Pengguna</h1>
                <p>Selamat datang, <?php echo $_SESSION['full_name']; ?>!</p>
            </div>

            <div class="dashboard-grid">
                <div class="dashboard-card">
                    <h3>Penerbangan Hari Ini dan Selanjutnya</h3>
                    <?php if($flights): ?>
                        <?php foreach($flights as $flight): ?>
                            <div class="flight-card">
                                <div class="flight-header">
                                    <span class="airline"><?php echo $flight['airline_name']; ?></span>
                                    <span class="flight-number"><?php echo $flight['flight_number']; ?></span>
                                </div>
                                <div class="flight-info">
                                    <div class="flight-departure">
                                        <div class="flight-time"><?php echo date('H:i', strtotime($flight['departure_time'])); ?></div>
                                        <div class="flight-city"><?php echo $flight['departure_city']; ?></div>
                                    </div>
                                    <div class="flight-duration">
                                        <div class="duration"><?php echo gmdate('H:i', strtotime($flight['arrival_time']) - strtotime($flight['departure_time'])); ?></div>
                                        <div class="route">→</div>
                                    </div>
                                    <div class="flight-arrival">
                                        <div class="flight-time"><?php echo date('H:i', strtotime($flight['arrival_time'])); ?></div>
                                        <div class="flight-city"><?php echo $flight['arrival_city']; ?></div>
                                    </div>
                                </div>
                                <div class="flight-footer">
                                    <div class="flight-date"><?php echo date('d M Y', strtotime($flight['departure_date'])); ?></div>
                                    <div class="flight-price">Rp <?php echo number_format($flight['price'], 0, ',', '.'); ?></div>
                                    <a href="../booking.php?flight_id=<?php echo $flight['id']; ?>" class="btn btn-primary">Pesan</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>Tidak ada penerbangan tersedia.</p>
                    <?php endif; ?>
                </div>

                <div class="dashboard-card">
                    <h3>Pemesanan Terbaru</h3>
                    <?php if($bookings): ?>
                        <?php foreach($bookings as $booking): ?>
                            <div class="booking-item">
                                <div class="booking-header">
                                    <span class="booking-code"><?php echo $booking['booking_code']; ?></span>
                                    <span class="badge badge-<?php echo $booking['status']; ?>"><?php echo ucfirst($booking['status']); ?></span>
                                </div>
                                <div class="booking-info">
                                    <p><?php echo $booking['airline_name']; ?> - <?php echo $booking['flight_number']; ?></p>
                                    <p><?php echo $booking['departure_city']; ?> → <?php echo $booking['arrival_city']; ?></p>
                                    <p><?php echo date('d M Y', strtotime($booking['departure_date'])); ?></p>
                                </div>
                                <div class="booking-actions">
                                    <?php if($booking['status'] == 'paid'): ?>
                                        <a href="../process/download_ticket.php?booking_id=<?php echo $booking['id']; ?>" class="btn btn-secondary">Download Tiket</a>
                                    <?php elseif($booking['status'] == 'pending'): ?>
                                        <a href="../process/payment.php?booking_id=<?php echo $booking['id']; ?>" class="btn btn-primary">Bayar</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>Belum ada pemesanan.</p>
                    <?php endif; ?>
                    <a href="history.php" class="btn btn-secondary">Lihat Semua Riwayat</a>
                </div>
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