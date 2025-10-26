<?php
include '../includes/config.php';
include '../includes/functions.php';
include '../includes/auth.php';

checkAuth();
checkAdmin();

// Update booking status
if (isset($_POST['update_status'])) {
    $booking_id = $_POST['booking_id'];
    $status = $_POST['status'];
    
    $stmt = $pdo->prepare("UPDATE bookings SET status = ? WHERE id = ?");
    $stmt->execute([$status, $booking_id]);
    
    header('Location: bookings.php?success=status_updated');
    exit();
}

// Get all bookings
$bookings = $pdo->query("
    SELECT b.*, u.username, u.email as user_email, f.flight_number, f.departure_city, f.arrival_city, f.departure_date, a.name as airline_name
    FROM bookings b 
    JOIN users u ON b.user_id = u.id 
    JOIN flights f ON b.flight_id = f.id 
    JOIN airlines a ON f.airline_id = a.id 
    ORDER BY b.booking_date DESC
")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pemesanan - FlyWme</title>
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
                    
                    <li><a href="index.php">Dashboard</a></li>
                    <li><a href="flights.php">Penerbangan</a></li>
                    <li><a href="bookings.php" class="active">Pemesanan</a></li>
                    <li><a href="users.php">Pengguna</a></li>
                    <li><a href="../process/logout.php">Logout</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <main class="dashboard">
        <div class="container">
            <div class="dashboard-header">
                <h1>Kelola Pemesanan</h1>
            </div>

            <?php if (isset($_GET['success'])): ?>
                <div style="background: #d1fae5; color: #065f46; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                    Status pemesanan berhasil diperbarui!
                </div>
            <?php endif; ?>

            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Kode Booking</th>
                            <th>Pengguna</th>
                            <th>Penerbangan</th>
                            <th>Penumpang</th>
                            <th>Kontak</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($bookings as $booking): ?>
                        <tr>
                            <td><?php echo $booking['booking_code']; ?></td>
                            <td>
                                <strong><?php echo $booking['username']; ?></strong><br>
                                <small><?php echo $booking['user_email']; ?></small>
                            </td>
                            <td>
                                <strong><?php echo $booking['airline_name']; ?></strong><br>
                                <?php echo $booking['flight_number']; ?><br>
                                <?php echo $booking['departure_city']; ?> → <?php echo $booking['arrival_city']; ?>
                            </td>
                            <td>
                                <?php echo $booking['passenger_name']; ?><br>
                                <small><?php echo $booking['passenger_email']; ?></small>
                            </td>
                            <td><?php echo $booking['passenger_phone']; ?></td>
                            <td>Rp <?php echo number_format($booking['total_price'], 0, ',', '.'); ?></td>
                            <td>
                                <form action="" method="POST" style="display: inline;">
                                    <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>">
                                    <select name="status" onchange="this.form.submit()" style="padding: 4px; border-radius: 4px; border: 1px solid #ddd;">
                                        <option value="pending" <?php echo $booking['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                        <option value="paid" <?php echo $booking['status'] == 'paid' ? 'selected' : ''; ?>>Paid</option>
                                        <option value="cancelled" <?php echo $booking['status'] == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                    </select>
                                    <button type="submit" name="update_status" style="display: none;">Update</button>
                                </form>
                            </td>
                            <td><?php echo date('d M Y H:i', strtotime($booking['booking_date'])); ?></td>
                            <td>
                                <?php if($booking['status'] == 'paid'): ?>
                                    <a href="../process/download_ticket.php?booking_id=<?php echo $booking['id']; ?>" class="btn btn-secondary">Download Tiket</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
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