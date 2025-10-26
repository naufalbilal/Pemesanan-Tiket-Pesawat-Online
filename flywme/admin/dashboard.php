<?php
include '../includes/config.php';
include '../includes/functions.php';
include '../includes/auth.php';

checkAuth();
checkAdmin();

// Get statistics for dashboard
$stmt = $pdo->query("SELECT COUNT(*) as total FROM users WHERE role = 'user'");
$total_users = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

$stmt = $pdo->query("SELECT COUNT(*) as total FROM flights");
$total_flights = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

$stmt = $pdo->query("SELECT COUNT(*) as total FROM bookings");
$total_bookings = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

$stmt = $pdo->query("SELECT SUM(total_price) as total FROM bookings WHERE status = 'paid'");
$total_revenue = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

// Get booking statistics by status
$stmt = $pdo->query("SELECT status, COUNT(*) as count FROM bookings GROUP BY status");
$booking_stats = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Recent bookings (last 5)
$stmt = $pdo->prepare("
    SELECT b.*, u.username, f.flight_number, f.departure_city, f.arrival_city 
    FROM bookings b 
    JOIN users u ON b.user_id = u.id 
    JOIN flights f ON b.flight_id = f.id 
    ORDER BY b.booking_date DESC 
    LIMIT 5
");
$stmt->execute();
$recent_bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Upcoming flights (next 3 days)
$three_days_later = date('Y-m-d', strtotime('+3 days'));
$stmt = $pdo->prepare("
    SELECT f.*, a.name as airline_name 
    FROM flights f 
    JOIN airlines a ON f.airline_id = a.id 
    WHERE f.departure_date BETWEEN ? AND ? 
    ORDER BY f.departure_date, f.departure_time 
    LIMIT 5
");
$stmt->execute([date('Y-m-d'), $three_days_later]);
$upcoming_flights = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - FlyWme</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            text-align: center;
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            color: var(--primary);
            margin-bottom: 0.5rem;
        }
        
        .stat-label {
            color: var(--secondary);
            font-size: 0.9rem;
        }
        
        .dashboard-section {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        
        .booking-status-chart {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
        }
        
        .status-item {
            flex: 1;
            text-align: center;
            padding: 1rem;
            border-radius: 8px;
        }
        
        .status-pending { background: #fef3c7; color: #d97706; }
        .status-paid { background: #d1fae5; color: #065f46; }
        .status-cancelled { background: #fee2e2; color: #dc2626; }
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
                    
                    <li><a href="dashboard.php" class="active">Dashboard</a></li>
                    <li><a href="flights.php">Penerbangan</a></li>
                    <li><a href="bookings.php">Pemesanan</a></li>
                    <li><a href="users.php">Pengguna</a></li>
                    <li><a href="../process/logout.php">Logout</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <main class="dashboard">
        <div class="container">
            <div class="dashboard-header">
                <h1>Dashboard Admin</h1>
                <p>Selamat datang, <?php echo $_SESSION['full_name']; ?>! - <?php echo date('l, d F Y'); ?></p>
            </div>

            <!-- Statistics Grid -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number"><?php echo $total_users; ?></div>
                    <div class="stat-label">Total Pengguna</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-number"><?php echo $total_flights; ?></div>
                    <div class="stat-label">Total Penerbangan</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-number"><?php echo $total_bookings; ?></div>
                    <div class="stat-label">Total Pemesanan</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-number">Rp <?php echo number_format($total_revenue, 0, ',', '.'); ?></div>
                    <div class="stat-label">Total Pendapatan</div>
                </div>
            </div>

            <div class="dashboard-grid">
                <!-- Booking Statistics -->
                <div class="dashboard-card">
                    <h3>Statistik Pemesanan</h3>
                    <div class="booking-status-chart">
                        <?php foreach($booking_stats as $stat): ?>
                            <div class="status-item status-<?php echo $stat['status']; ?>">
                                <div style="font-size: 1.5rem; font-weight: bold;"><?php echo $stat['count']; ?></div>
                                <div><?php echo ucfirst($stat['status']); ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Upcoming Flights -->
                <div class="dashboard-card">
                    <h3>Penerbangan Mendatang (3 Hari)</h3>
                    <?php if($upcoming_flights): ?>
                        <?php foreach($upcoming_flights as $flight): ?>
                            <div style="padding: 1rem; border-bottom: 1px solid var(--border);">
                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                    <div>
                                        <strong><?php echo $flight['airline_name']; ?> - <?php echo $flight['flight_number']; ?></strong>
                                        <div style="font-size: 0.9rem; color: var(--secondary);">
                                            <?php echo $flight['departure_city']; ?> → <?php echo $flight['arrival_city']; ?>
                                        </div>
                                    </div>
                                    <div style="text-align: right;">
                                        <div><?php echo formatDate($flight['departure_date']); ?></div>
                                        <div style="font-size: 0.9rem; color: var(--secondary);">
                                            <?php echo formatTime($flight['departure_time']); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p style="text-align: center; color: var(--secondary); padding: 2rem;">
                            Tidak ada penerbangan dalam 3 hari ke depan
                        </p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Recent Bookings -->
            <div class="dashboard-section">
                <h2>Pemesanan Terbaru</h2>
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Kode Booking</th>
                                <th>Pengguna</th>
                                <th>Penerbangan</th>
                                <th>Rute</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($recent_bookings as $booking): ?>
                            <tr>
                                <td><?php echo $booking['booking_code']; ?></td>
                                <td><?php echo $booking['username']; ?></td>
                                <td><?php echo $booking['flight_number']; ?></td>
                                <td><?php echo $booking['departure_city']; ?> → <?php echo $booking['arrival_city']; ?></td>
                                <td>Rp <?php echo number_format($booking['total_price'], 0, ',', '.'); ?></td>
                                <td>
                                    <span class="badge badge-<?php echo $booking['status']; ?>">
                                        <?php echo ucfirst($booking['status']); ?>
                                    </span>
                                </td>
                                <td><?php echo date('d M Y H:i', strtotime($booking['booking_date'])); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
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