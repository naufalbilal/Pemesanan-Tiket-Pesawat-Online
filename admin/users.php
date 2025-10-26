<?php
include '../includes/config.php';
include '../includes/functions.php';
include '../includes/auth.php';

checkAuth();
checkAdmin();

// Get all users
$users = $pdo->query("SELECT * FROM users WHERE role = 'user' ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pengguna - FlyWme</title>
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
                    <li><a href="bookings.php">Pemesanan</a></li>
                    <li><a href="users.php" class="active">Pengguna</a></li>
                    <li><a href="../process/logout.php">Logout</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <main class="dashboard">
        <div class="container">
            <div class="dashboard-header">
                <h1>Kelola Pengguna</h1>
                <p>Total <?php echo count($users); ?> pengguna terdaftar</p>
            </div>

            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Nama Lengkap</th>
                            <th>Telepon</th>
                            <th>Alamat</th>
                            <th>Tanggal Daftar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($users as $user): ?>
                        <tr>
                            <td><?php echo $user['username']; ?></td>
                            <td><?php echo $user['email']; ?></td>
                            <td><?php echo $user['full_name']; ?></td>
                            <td><?php echo $user['phone']; ?></td>
                            <td><?php echo $user['address']; ?></td>
                            <td><?php echo date('d M Y H:i', strtotime($user['created_at'])); ?></td>
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