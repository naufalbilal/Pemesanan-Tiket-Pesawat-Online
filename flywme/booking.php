<?php
include 'includes/config.php';
include 'includes/functions.php';
include 'includes/auth.php';

if (!isLoggedIn() || isAdmin()) {
    redirect('login.php');
}

if (!isset($_GET['flight_id']) || !isset($_GET['passengers'])) {
    redirect('search.php');
}

$flight_id = $_GET['flight_id'];
$passengers = $_GET['passengers'];

// Get flight details
$stmt = $pdo->prepare("
    SELECT f.*, a.name as airline_name, a.code as airline_code 
    FROM flights f 
    JOIN airlines a ON f.airline_id = a.id 
    WHERE f.id = ?
");
$stmt->execute([$flight_id]);
$flight = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$flight) {
    die('Penerbangan tidak ditemukan');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $total_price = $flight['price'] * $passengers;
    $booking_code = generateBookingCode();
    
    $stmt = $pdo->prepare("
        INSERT INTO bookings (user_id, flight_id, booking_code, passenger_name, passenger_email, passenger_phone, total_price) 
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    
    if ($stmt->execute([
        $_SESSION['user_id'],
        $flight_id,
        $booking_code,
        $_POST['passenger_name'],
        $_POST['passenger_email'],
        $_POST['passenger_phone'],
        $total_price
    ])) {
        $booking_id = $pdo->lastInsertId();
        
        // Update available seats
        $stmt = $pdo->prepare("UPDATE flights SET seats_available = seats_available - ? WHERE id = ?");
        $stmt->execute([$passengers, $flight_id]);
        
        header("Location: process/payment.php?booking_id=$booking_id");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan Tiket - FlyWme</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="nav-container">
                <div class="nav-logo">
                    <h2><a href="index.php">FlyWme</a></h2>
                </div>
                <ul class="nav-menu">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="search.php">Cari Penerbangan</a></li>
                    <li><a href="user/dashboard.php">Dashboard</a></li>
                    <li><a href="process/logout.php">Logout</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <main style="padding: 100px 0 50px;">
        <div class="container">
            <h1>Pesan Tiket</h1>
            
            <div class="booking-container">
                <div class="flight-summary">
                    <h3>Detail Penerbangan</h3>
                    <div class="flight-card">
                        <div class="flight-header">
                            <span class="airline"><?php echo $flight['airline_name']; ?></span>
                            <span class="flight-number"><?php echo $flight['flight_number']; ?></span>
                        </div>
                        <div class="flight-info">
                            <div class="flight-departure">
                                <div class="flight-time"><?php echo formatTime($flight['departure_time']); ?></div>
                                <div class="flight-city"><?php echo $flight['departure_city']; ?></div>
                                <div class="flight-date"><?php echo formatDate($flight['departure_date']); ?></div>
                            </div>
                            <div class="flight-duration">
                                <div class="duration">
                                    <?php 
                                    $duration = strtotime($flight['arrival_time']) - strtotime($flight['departure_time']);
                                    echo gmdate('H:i', $duration);
                                    ?>
                                </div>
                                <div class="route">→</div>
                            </div>
                            <div class="flight-arrival">
                                <div class="flight-time"><?php echo formatTime($flight['arrival_time']); ?></div>
                                <div class="flight-city"><?php echo $flight['arrival_city']; ?></div>
                                <div class="flight-date"><?php echo formatDate($flight['arrival_date']); ?></div>
                            </div>
                        </div>
                        <div class="flight-price">
                            <strong>Rp <?php echo number_format($flight['price'], 0, ',', '.'); ?> x <?php echo $passengers; ?> penumpang</strong>
                            <div class="total-price">Total: Rp <?php echo number_format($flight['price'] * $passengers, 0, ',', '.'); ?></div>
                        </div>
                    </div>
                </div>

                <div class="booking-form">
                    <h3>Data Penumpang</h3>
                    <form action="" method="POST">
                        <div class="form-group">
                            <label for="passenger_name">Nama Lengkap Penumpang</label>
                            <input type="text" class="form-control" id="passenger_name" name="passenger_name" 
                                   value="<?php echo $_SESSION['full_name']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="passenger_email">Email Penumpang</label>
                            <input type="email" class="form-control" id="passenger_email" name="passenger_email" 
                                   value="<?php echo $_SESSION['email']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="passenger_phone">Nomor Telepon Penumpang</label>
                            <input type="tel" class="form-control" id="passenger_phone" name="passenger_phone" required>
                        </div>
                        <button type="submit" class="btn btn-primary" style="width: 100%;">Lanjut ke Pembayaran</button>
                    </form>
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