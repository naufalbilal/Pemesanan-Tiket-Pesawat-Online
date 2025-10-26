<?php 
include 'includes/config.php';
include 'includes/auth.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cari Penerbangan Eksklusif - FlyWme</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        /* Dark Gold Luxury Theme */
        :root {
            --gold-primary: #D4AF37;
            --gold-secondary: #FFD700;
            --gold-light: #F7EF8A;
            --dark-bg: #1a1a1a;
            --dark-card: #2d2d2d;
            --dark-text: #e0e0e0;
            --dark-border: #444444;
        }

        body {
            background: var(--dark-bg);
            color: var(--dark-text);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .navbar {
    background: rgba(26, 26, 26, 0.95);
    backdrop-filter: blur(10px);
    border-bottom: 2px solid var(--gold-primary);
}

.nav-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
    display: flex;
    justify-content: space-between;
    align-items: center; /* Pastikan ini ada */
    height: 70px; /* Pastikan height konsisten */
}

.nav-logo h2 {
    color: var(--gold-primary) !important;
    font-weight: 700;
    text-shadow: 0 0 10px rgba(212, 175, 55, 0.3);
    margin: 0; /* Pastikan tidak ada margin */
    line-height: 1; /* Pastikan line-height normal */
}

.nav-menu {
    display: flex;
    list-style: none;
    gap: 2rem;
    margin: 0; /* Pastikan tidak ada margin */
    padding: 0; /* Pastikan tidak ada padding */
    align-items: center; /* Sejajar vertikal */
}

.nav-menu li {
    margin: 0; /* Pastikan tidak ada margin di li */
}

.nav-menu a {
    color: var(--dark-text);
    text-decoration: none;
    transition: all 0.3s ease;
    font-weight: 500;
    padding: 8px 0; /* Tambahkan padding untuk area klik yang lebih baik */
}

.nav-menu a:hover,
.nav-menu a.active {
    color: var(--gold-primary);
}

/* ===== Sticky footer via Flexbox ===== */
html, body {
  height: 100%;
  margin: 0;
}

body {
  display: flex;
  flex-direction: column;   /* susun vertikal: header, main, footer */
  min-height: 100vh;
}

main {
  flex: 1 0 auto;           /* isi mendorong footer ke bawah */
  padding: 140px 0 80px;    /* padding yang tadi sudah ada */
  /* HAPUS: min-height: 100vh; */
}

footer {
  margin-top: auto;         /* kunci: footer nempel di bawah */
  background: var(--dark-card);
  color: var(--dark-text);
  padding: 3rem 0;
  text-align: center;
  border-top: 2px solid var(--gold-primary);
}

/* (opsional) Container standar */
.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 20px;
}


        /* Main Content */
        main {
            padding: 140px 0 80px;
            background: var(--dark-bg);
            min-height: 100vh;
        }

        .container h1 {
            text-align: center;
            font-size: 3rem;
            margin-bottom: 3rem;
            background: linear-gradient(45deg, var(--gold-primary), var(--gold-secondary));
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: 700;
        }

        /* Search Form */
        .search-form {
            background: var(--dark-card);
            padding: 3rem;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
            border: 1px solid var(--dark-border);
            margin-bottom: 3rem;
        }

        .search-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.8rem;
            color: var(--gold-light);
            font-weight: 600;
            font-size: 1.1rem;
        }

        .form-control {
            width: 100%;
            padding: 15px 20px;
            background: rgba(255,255,255,0.05);
            border: 2px solid var(--dark-border);
            border-radius: 12px;
            color: var(--dark-text);
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--gold-primary);
            box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.2);
            background: rgba(255,255,255,0.08);
        }

        .form-control::placeholder {
            color: #888;
        }

        .btn-primary {
            background: linear-gradient(45deg, var(--gold-primary), var(--gold-secondary));
            color: var(--dark-bg);
            padding: 18px 40px;
            border: none;
            border-radius: 12px;
            font-size: 1.2rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 8px 25px rgba(212, 175, 55, 0.4);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(212, 175, 55, 0.6);
        }

        /* Results Section */
        h2 {
            color: var(--gold-primary);
            font-size: 2.5rem;
            margin: 4rem 0 2rem;
            text-align: center;
            font-weight: 700;
        }

        /* Flight Cards */
        .flight-card {
            background: var(--dark-card);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.3);
            padding: 2.5rem;
            margin-bottom: 2rem;
            border: 1px solid var(--dark-border);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .flight-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(45deg, var(--gold-primary), var(--gold-secondary));
        }

        .flight-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 25px 50px rgba(212, 175, 55, 0.2);
            border-color: var(--gold-primary);
        }

        .flight-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--dark-border);
        }

        .airline {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--gold-primary);
        }

        .flight-number {
            color: var(--gold-light);
            font-weight: 600;
            background: rgba(212, 175, 55, 0.1);
            padding: 8px 16px;
            border-radius: 8px;
        }

        .flight-info {
            display: grid;
            grid-template-columns: 2fr 1fr 2fr;
            align-items: center;
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .flight-departure,
        .flight-arrival {
            text-align: center;
        }

        .flight-time {
            font-size: 2.2rem;
            font-weight: 700;
            color: var(--gold-primary);
            margin-bottom: 0.5rem;
        }

        .flight-city {
            font-size: 1.2rem;
            color: var(--dark-text);
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .flight-date {
            color: var(--gold-light);
            font-size: 1rem;
        }

        .flight-duration {
            text-align: center;
        }

        .duration {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--gold-primary);
            margin-bottom: 0.5rem;
        }

        .route {
            font-size: 2rem;
            color: var(--gold-secondary);
            margin: 1rem 0;
        }

        .flight-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 2rem;
            border-top: 1px solid var(--dark-border);
        }

        .seats-available {
            color: var(--gold-light);
            font-weight: 600;
            background: rgba(16, 185, 129, 0.1);
            padding: 8px 16px;
            border-radius: 8px;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .flight-price {
            text-align: right;
        }

        .price {
            font-size: 2rem;
            font-weight: 700;
            color: var(--gold-primary);
            margin-bottom: 0.5rem;
        }

        .flight-price small {
            color: var(--gold-light);
            font-size: 1rem;
        }

        /* No Results */
        .no-results {
            text-align: center;
            padding: 4rem 2rem;
            background: var(--dark-card);
            border-radius: 20px;
            border: 2px solid var(--dark-border);
        }

        .no-results h3 {
            color: var(--gold-primary);
            font-size: 2rem;
            margin-bottom: 1rem;
        }

        .no-results p {
            color: var(--dark-text);
            font-size: 1.2rem;
        }

        /* Footer */
        footer {
            background: var(--dark-card);
            color: var(--dark-text);
            padding: 3rem 0;
            text-align: center;
            border-top: 2px solid var(--gold-primary);
        }

        footer p {
            margin: 0;
            font-size: 1.1rem;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            main {
                padding: 120px 0 60px;
            }

            .container h1 {
                font-size: 2.2rem;
            }

            .search-grid {
                grid-template-columns: 1fr;
            }

            .search-form {
                padding: 2rem;
            }

            .flight-info {
                grid-template-columns: 1fr;
                text-align: center;
                gap: 1rem;
            }

            .flight-duration {
                order: 2;
            }

            .flight-footer {
                flex-direction: column;
                gap: 1.5rem;
                text-align: center;
            }

            .flight-price {
                text-align: center;
            }
        }

        @media (max-width: 480px) {
            .search-form {
                padding: 1.5rem;
            }

            .flight-card {
                padding: 1.5rem;
            }

            .flight-time {
                font-size: 1.8rem;
            }
        }
    </style>
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
                    <li><a href="search.php" class="active">Cari Penerbangan</a></li>
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <?php if($_SESSION['role'] == 'admin'): ?>
                            <li><a href="admin/">Dashboard Admin</a></li>
                        <?php else: ?>
                            <li><a href="user/dashboard.php">Dashboard</a></li>
                        <?php endif; ?>
                        <li><a href="process/logout.php">Logout</a></li>
                    <?php else: ?>
                        <li><a href="login.php">Login</a></li>
                        <li><a href="register.php">Register</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>
    </header>

    <main>
        <div class="container">
            <h1>Cari Penerbangan Eksklusif</h1>
            
            <div class="search-form">
                <form action="search.php" method="GET">
                    <div class="search-grid">
                        <div class="form-group">
                            <label for="departure">🛫 Kota Keberangkatan</label>
                            <input type="text" class="form-control" id="departure" name="departure" 
                                   value="<?php echo $_GET['departure'] ?? ''; ?>" 
                                   placeholder="Contoh: Jakarta" required>
                        </div>
                        <div class="form-group">
                            <label for="arrival">🛬 Kota Tujuan</label>
                            <input type="text" class="form-control" id="arrival" name="arrival" 
                                   value="<?php echo $_GET['arrival'] ?? ''; ?>" 
                                   placeholder="Contoh: Bali" required>
                        </div>
                        <div class="form-group">
                            <label for="departure_date">📅 Tanggal Keberangkatan</label>
                            <input type="date" class="form-control" id="departure_date" name="departure_date" 
                                   value="<?php echo $_GET['departure_date'] ?? ''; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="passengers">👥 Jumlah Penumpang</label>
                            <select class="form-control" id="passengers" name="passengers">
                                <option value="1" <?php echo ($_GET['passengers'] ?? '1') == '1' ? 'selected' : ''; ?>>1 Penumpang</option>
                                <option value="2" <?php echo ($_GET['passengers'] ?? '1') == '2' ? 'selected' : ''; ?>>2 Penumpang</option>
                                <option value="3" <?php echo ($_GET['passengers'] ?? '1') == '3' ? 'selected' : ''; ?>>3 Penumpang</option>
                                <option value="4" <?php echo ($_GET['passengers'] ?? '1') == '4' ? 'selected' : ''; ?>>4 Penumpang</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 2rem;">
                        🔍 Cari Penerbangan Eksklusif
                    </button>
                </form>
            </div>

            <?php
            if (isset($_GET['departure']) && isset($_GET['arrival']) && isset($_GET['departure_date'])) {
                $departure = $_GET['departure'];
                $arrival = $_GET['arrival'];
                $departure_date = $_GET['departure_date'];
                $passengers = $_GET['passengers'] ?? 1;
                
                $stmt = $pdo->prepare("
                    SELECT f.*, a.name as airline_name, a.code as airline_code, a.logo 
                    FROM flights f 
                    JOIN airlines a ON f.airline_id = a.id 
                    WHERE f.departure_city LIKE ? AND f.arrival_city LIKE ? AND f.departure_date = ? AND f.seats_available >= ?
                    ORDER BY f.departure_time
                ");
                $stmt->execute(["%$departure%", "%$arrival%", $departure_date, $passengers]);
                $flights = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                if ($flights) {
                    echo '<h2> Hasil Pencarian Eksklusif</h2>';
                    foreach ($flights as $flight) {
                        ?>
                        <div class="flight-card">
                            <div class="flight-header">
                                <span class="airline"><?php echo $flight['airline_name']; ?></span>
                                <span class="flight-number"><?php echo $flight['flight_number']; ?></span>
                            </div>
                            <div class="flight-info">
                                <div class="flight-departure">
                                    <div class="flight-time"><?php echo date('H:i', strtotime($flight['departure_time'])); ?></div>
                                    <div class="flight-city"><?php echo $flight['departure_city']; ?></div>
                                    <div class="flight-date"><?php echo date('d M Y', strtotime($flight['departure_date'])); ?></div>
                                </div>
                                <div class="flight-duration">
                                    <div class="route">→</div>
                                    <div class="duration">
                                        <?php 
                                        $duration = strtotime($flight['arrival_time']) - strtotime($flight['departure_time']);
                                        echo gmdate('H:i', $duration);
                                        ?>
                                    </div>
                                    <div style="color: var(--gold-light); font-size: 0.9rem;">Langsung</div>
                                </div>
                                <div class="flight-arrival">
                                    <div class="flight-time"><?php echo date('H:i', strtotime($flight['arrival_time'])); ?></div>
                                    <div class="flight-city"><?php echo $flight['arrival_city']; ?></div>
                                    <div class="flight-date"><?php echo date('d M Y', strtotime($flight['arrival_date'])); ?></div>
                                </div>
                            </div>
                            <div class="flight-footer">
                                <div class="seats-available">✅ <?php echo $flight['seats_available']; ?> kursi tersedia</div>
                                <div class="flight-price">
                                    <div class="price">Rp <?php echo number_format($flight['price'], 0, ',', '.'); ?></div>
                                    <small>per penumpang</small>
                                </div>
                                <?php if(isset($_SESSION['user_id']) && $_SESSION['role'] == 'user'): ?>
                                    <a href="booking.php?flight_id=<?php echo $flight['id']; ?>&passengers=<?php echo $passengers; ?>" class="btn btn-primary">Pesan Sekarang</a>
                                <?php elseif(!isset($_SESSION['user_id'])): ?>
                                    <a href="login.php" class="btn btn-primary">Login untuk Pesan</a>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo '<div class="no-results">';
                    echo '<h3>🚫 Tidak Ada Penerbangan Ditemukan</h3>';
                    echo '<p>Maaf, tidak ada penerbangan yang sesuai dengan kriteria pencarian Anda.<br>Silakan coba dengan kota atau tanggal yang berbeda.</p>';
                    echo '</div>';
                }
            }
            ?>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2025 FlyWme. Muhammad Naufal Bilal Syam (owner). | Layanan Pemesanan Tiket Pesawat Eksklusif</p>
        </div>
    </footer>
</body>
</html>