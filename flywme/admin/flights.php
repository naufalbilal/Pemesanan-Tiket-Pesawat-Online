<?php
include '../includes/config.php';
include '../includes/functions.php';
include '../includes/auth.php';

checkAuth();
checkAdmin();

// Add new flight
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_flight'])) {
    $airline_id = $_POST['airline_id'];
    $flight_number = $_POST['flight_number'];
    $departure_city = $_POST['departure_city'];
    $arrival_city = $_POST['arrival_city'];
    $departure_date = $_POST['departure_date'];
    $departure_time = $_POST['departure_time'];
    $arrival_date = $_POST['arrival_date'];
    $arrival_time = $_POST['arrival_time'];
    $price = $_POST['price'];
    $seats_available = $_POST['seats_available'];
    
    $stmt = $pdo->prepare("
        INSERT INTO flights (airline_id, flight_number, departure_city, arrival_city, departure_date, departure_time, arrival_date, arrival_time, price, seats_available) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    
    if ($stmt->execute([$airline_id, $flight_number, $departure_city, $arrival_city, $departure_date, $departure_time, $arrival_date, $arrival_time, $price, $seats_available])) {
        header('Location: flights.php?success=flight_added');
        exit();
    }
}

// Delete flight
if (isset($_GET['delete'])) {
    $flight_id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM flights WHERE id = ?");
    $stmt->execute([$flight_id]);
    header('Location: flights.php?success=flight_deleted');
    exit();
}

// Get airlines for dropdown
$airlines = $pdo->query("SELECT * FROM airlines")->fetchAll(PDO::FETCH_ASSOC);

// Get all flights
$flights = $pdo->query("
    SELECT f.*, a.name as airline_name, a.code as airline_code 
    FROM flights f 
    JOIN airlines a ON f.airline_id = a.id 
    ORDER BY f.departure_date DESC, f.departure_time DESC
")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Penerbangan - FlyWme</title>
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
                    <li><a href="flights.php" class="active">Penerbangan</a></li>
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
                <h1>Kelola Penerbangan</h1>
            </div>

            <?php if (isset($_GET['success'])): ?>
                <div style="background: #d1fae5; color: #065f46; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                    <?php
                    $success_messages = [
                        'flight_added' => 'Penerbangan berhasil ditambahkan!',
                        'flight_deleted' => 'Penerbangan berhasil dihapus!'
                    ];
                    echo $success_messages[$_GET['success']] ?? 'Operasi berhasil!';
                    ?>
                </div>
            <?php endif; ?>

            <div class="dashboard-grid">
                <div class="dashboard-card">
                    <h3>Tambah Penerbangan Baru</h3>
                    <form action="" method="POST">
                        <div class="form-group">
                            <label for="airline_id">Maskapai</label>
                            <select class="form-control" id="airline_id" name="airline_id" required>
                                <option value="">Pilih Maskapai</option>
                                <?php foreach($airlines as $airline): ?>
                                    <option value="<?php echo $airline['id']; ?>"><?php echo $airline['name']; ?> (<?php echo $airline['code']; ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="flight_number">Nomor Penerbangan</label>
                            <input type="text" class="form-control" id="flight_number" name="flight_number" required>
                        </div>
                        <div class="form-group">
                            <label for="departure_city">Kota Keberangkatan</label>
                            <input type="text" class="form-control" id="departure_city" name="departure_city" required>
                        </div>
                        <div class="form-group">
                            <label for="arrival_city">Kota Tujuan</label>
                            <input type="text" class="form-control" id="arrival_city" name="arrival_city" required>
                        </div>
                        <div class="form-group">
                            <label for="departure_date">Tanggal Keberangkatan</label>
                            <input type="date" class="form-control" id="departure_date" name="departure_date" required>
                        </div>
                        <div class="form-group">
                            <label for="departure_time">Waktu Keberangkatan</label>
                            <input type="time" class="form-control" id="departure_time" name="departure_time" required>
                        </div>
                        <div class="form-group">
                            <label for="arrival_date">Tanggal Tiba</label>
                            <input type="date" class="form-control" id="arrival_date" name="arrival_date" required>
                        </div>
                        <div class="form-group">
                            <label for="arrival_time">Waktu Tiba</label>
                            <input type="time" class="form-control" id="arrival_time" name="arrival_time" required>
                        </div>
                        <div class="form-group">
                            <label for="price">Harga</label>
                            <input type="number" class="form-control" id="price" name="price" required>
                        </div>
                        <div class="form-group">
                            <label for="seats_available">Kursi Tersedia</label>
                            <input type="number" class="form-control" id="seats_available" name="seats_available" required>
                        </div>
                        <button type="submit" name="add_flight" class="btn btn-primary">Tambah Penerbangan</button>
                    </form>
                </div>

                <div class="dashboard-card">
                    <h3>Daftar Penerbangan</h3>
                    <div class="table-container">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Maskapai</th>
                                    <th>Nomor</th>
                                    <th>Rute</th>
                                    <th>Tanggal</th>
                                    <th>Waktu</th>
                                    <th>Harga</th>
                                    <th>Kursi</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($flights as $flight): ?>
                                <tr>
                                    <td><?php echo $flight['airline_name']; ?></td>
                                    <td><?php echo $flight['flight_number']; ?></td>
                                    <td><?php echo $flight['departure_city']; ?> → <?php echo $flight['arrival_city']; ?></td>
                                    <td><?php echo formatDate($flight['departure_date']); ?></td>
                                    <td><?php echo formatTime($flight['departure_time']); ?> - <?php echo formatTime($flight['arrival_time']); ?></td>
                                    <td>Rp <?php echo number_format($flight['price'], 0, ',', '.'); ?></td>
                                    <td><?php echo $flight['seats_available']; ?></td>
                                    <td>
                                        <a href="?delete=<?php echo $flight['id']; ?>" class="btn btn-danger" onclick="return confirm('Hapus penerbangan ini?')">Hapus</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
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