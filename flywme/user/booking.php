<?php
include '../includes/config.php';
include '../includes/functions.php';
include '../includes/auth.php';

if (!isLoggedIn() || isAdmin()) {
    redirect('../login.php');
}

if (!isset($_GET['flight_id']) || !isset($_GET['passengers'])) {
    redirect('../search.php');
}

$flight_id = $_GET['flight_id'];
$passengers = $_GET['passengers'];

// Validate passengers
if ($passengers < 1 || $passengers > 4) {
    redirect('../search.php');
}

// Get flight details
$stmt = $pdo->prepare("
    SELECT f.*, a.name as airline_name, a.code as airline_code 
    FROM flights f 
    JOIN airlines a ON f.airline_id = a.id 
    WHERE f.id = ? AND f.seats_available >= ?
");
$stmt->execute([$flight_id, $passengers]);
$flight = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$flight) {
    header('Location: ../search.php?error=flight_not_available');
    exit();
}

// Calculate total price
$total_price = $flight['price'] * $passengers;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $passenger_name = $_POST['passenger_name'];
    $passenger_email = $_POST['passenger_email'];
    $passenger_phone = $_POST['passenger_phone'];
    
    // Basic validation
    if (empty($passenger_name) || empty($passenger_email) || empty($passenger_phone)) {
        $error = "Harap lengkapi semua data penumpang!";
    } else {
        // Process booking
        $booking_code = generateBookingCode();
        
        $stmt = $pdo->prepare("
            INSERT INTO bookings (user_id, flight_id, booking_code, passenger_name, passenger_email, passenger_phone, total_price) 
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        
        if ($stmt->execute([
            $_SESSION['user_id'],
            $flight_id,
            $booking_code,
            $passenger_name,
            $passenger_email,
            $passenger_phone,
            $total_price
        ])) {
            $booking_id = $pdo->lastInsertId();
            
            // Update available seats
            $stmt = $pdo->prepare("UPDATE flights SET seats_available = seats_available - ? WHERE id = ?");
            $stmt->execute([$passengers, $flight_id]);
            
            // Redirect to payment
            header("Location: ../process/payment.php?booking_id=$booking_id");
            exit();
        } else {
            $error = "Gagal melakukan pemesanan. Silakan coba lagi!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan Tiket - FlyWme</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .booking-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            margin-top: 2rem;
        }
        
        @media (max-width: 768px) {
            .booking-container {
                grid-template-columns: 1fr;
            }
        }
        
        .flight-summary {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .booking-form {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .price-breakdown {
            background: #f8fafc;
            padding: 1rem;
            border-radius: 8px;
            margin-top: 1rem;
        }
        
        .price-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
        }
        
        .price-total {
            border-top: 2px solid var(--border);
            padding-top: 0.5rem;
            margin-top: 0.5rem;
            font-weight: bold;
            font-size: 1.2rem;
        }
    </style>
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
                    <li><a href="history.php">Riwayat</a></li>
                    <li><a href="profile.php">Profile</a></li>
                    <li><a href="../process/logout.php">Logout</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <main style="padding: 100px 0 50px;">
        <div class="container">
            <h1>Pesan Tiket</h1>
            
            <?php if (isset($error)): ?>
                <div style="background: #fee2e2; color: #dc2626; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <div class="booking-container">
                <!-- Flight Summary -->
                <div class="flight-summary">
                    <h3>Detail Penerbangan</h3>
                    <div class="flight-card" style="box-shadow: none; border: 1px solid var(--border);">
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
                    </div>
                    
                    <div class="price-breakdown">
                        <h4>Rincian Harga</h4>
                        <div class="price-item">
                            <span>Harga per orang:</span>
                            <span>Rp <?php echo number_format($flight['price'], 0, ',', '.'); ?></span>
                        </div>
                        <div class="price-item">
                            <span>Jumlah penumpang:</span>
                            <span><?php echo $passengers; ?> orang</span>
                        </div>
                        <div class="price-item price-total">
                            <span>Total:</span>
                            <span>Rp <?php echo number_format($total_price, 0, ',', '.'); ?></span>
                        </div>
                    </div>
                    
                    <div style="margin-top: 1rem; padding: 1rem; background: #ecfdf5; border-radius: 8px;">
                        <small style="color: #065f46;">
                            ✅ <strong>Kursi tersedia:</strong> <?php echo $flight['seats_available']; ?> kursi
                        </small>
                    </div>
                </div>

                <!-- Booking Form -->
                <div class="booking-form">
                    <h3>Data Penumpang</h3>
                    <p style="color: var(--secondary); margin-bottom: 1rem;">
                        Isi data penumpang yang akan melakukan perjalanan
                    </p>
                    
                    <form action="" method="POST">
                        <input type="hidden" name="flight_id" value="<?php echo $flight_id; ?>">
                        <input type="hidden" name="passengers" value="<?php echo $passengers; ?>">
                        
                        <div class="form-group">
                            <label for="passenger_name">Nama Lengkap Penumpang *</label>
                            <input type="text" class="form-control" id="passenger_name" name="passenger_name" 
                                   value="<?php echo $_SESSION['full_name']; ?>" required
                                   placeholder="Masukkan nama lengkap penumpang">
                        </div>
                        
                        <div class="form-group">
                            <label for="passenger_email">Email Penumpang *</label>
                            <input type="email" class="form-control" id="passenger_email" name="passenger_email" 
                                   value="<?php echo $_SESSION['email']; ?>" required
                                   placeholder="email@contoh.com">
                        </div>
                        
                        <div class="form-group">
                            <label for="passenger_phone">Nomor Telepon Penumpang *</label>
                            <input type="tel" class="form-control" id="passenger_phone" name="passenger_phone" 
                                   required placeholder="0812-3456-7890">
                            <small style="color: var(--secondary);">Format: 0812-3456-7890</small>
                        </div>
                        
                        <div style="background: #fffbeb; padding: 1rem; border-radius: 8px; margin: 1.5rem 0;">
                            <h4 style="color: #d97706; margin-bottom: 0.5rem;">📋 Informasi Penting</h4>
                            <ul style="color: #92400e; font-size: 0.9rem; margin: 0; padding-left: 1.2rem;">
                                <li>Pastikan data penumpang sesuai dengan KTP/Paspor</li>
                                <li>Email digunakan untuk mengirim e-ticket</li>
                                <li>Nomor telepon harus aktif untuk konfirmasi</li>
                            </ul>
                        </div>
                        
                        <button type="submit" class="btn btn-primary" style="width: 100%; padding: 15px; font-size: 1.1rem;">
                            🛒 Lanjutkan ke Pembayaran - Rp <?php echo number_format($total_price, 0, ',', '.'); ?>
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

    <script>
        // Format phone number input
        document.getElementById('passenger_phone').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 0) {
                value = value.substring(0, 12);
                if (value.length > 4) {
                    value = value.replace(/(\d{4})(\d{4})/, '$1-$2');
                }
                if (value.length > 8) {
                    value = value.replace(/(\d{4})(\d{4})(\d{4})/, '$1-$2-$3');
                }
            }
            e.target.value = value;
        });
        
        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const phone = document.getElementById('passenger_phone').value.replace(/\D/g, '');
            if (phone.length < 10) {
                e.preventDefault();
                alert('Nomor telepon harus minimal 10 digit!');
                return false;
            }
        });
    </script>
</body>
</html>