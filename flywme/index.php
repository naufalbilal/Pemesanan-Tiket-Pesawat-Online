<?php 
include 'includes/config.php';
include 'includes/auth.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FlyWme - Pemesanan Tiket Pesawat Eksklusif</title>
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

        .nav-logo h2 {
            color: var(--gold-primary) !important;
            font-weight: 700;
            text-shadow: 0 0 10px rgba(212, 175, 55, 0.3);
        }

        .nav-menu a {
            color: var(--dark-text);
            transition: all 0.3s ease;
        }

        .nav-menu a:hover,
        .nav-menu a.active {
            color: var(--gold-primary);
        }

        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, 
                rgba(26, 26, 26, 0.9) 0%, 
                rgba(45, 45, 45, 0.9) 100%),
                url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 800" opacity="0.1"><path fill="%23D4AF37" d="M0 0h1200v800H0z"/><path fill="%23FFD700" d="M600 200l300 300-300 300-300-300z"/></svg>');
            background-size: cover;
            color: white;
            padding: 180px 0 120px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 30% 50%, rgba(212, 175, 55, 0.1) 0%, transparent 50%);
        }

        .hero-content {
            position: relative;
            z-index: 2;
            max-width: 800px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .hero-content h1 {
            font-size: 4rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            background: linear-gradient(45deg, var(--gold-primary), var(--gold-secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-shadow: 0 0 30px rgba(212, 175, 55, 0.5);
        }

        .hero-content p {
            font-size: 1.4rem;
            margin-bottom: 3rem;
            color: var(--gold-light);
            font-weight: 300;
            line-height: 1.6;
        }

        .btn-hero {
            background: linear-gradient(45deg, var(--gold-primary), var(--gold-secondary));
            color: var(--dark-bg);
            padding: 18px 50px;
            border: none;
            border-radius: 50px;
            font-size: 1.2rem;
            font-weight: 700;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            box-shadow: 0 8px 25px rgba(212, 175, 55, 0.4);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn-hero:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(212, 175, 55, 0.6);
            color: var(--dark-bg);
        }

        /* Features Section */
        .features {
            padding: 120px 0;
            background: var(--dark-bg);
        }

        .section-title {
            text-align: center;
            margin-bottom: 5rem;
        }

        .section-title h2 {
            font-size: 3rem;
            color: var(--gold-primary);
            margin-bottom: 1.5rem;
            position: relative;
            display: inline-block;
        }

        .section-title h2::after {
            content: '';
            position: absolute;
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 4px;
            background: linear-gradient(45deg, var(--gold-primary), var(--gold-secondary));
            border-radius: 2px;
        }

        .section-title p {
            color: var(--gold-light);
            font-size: 1.2rem;
            max-width: 600px;
            margin: 2rem auto 0;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 3rem;
            margin-top: 4rem;
        }

        .feature-card {
            background: var(--dark-card);
            padding: 3rem 2.5rem;
            border-radius: 20px;
            text-align: center;
            box-shadow: 0 15px 35px rgba(0,0,0,0.3);
            transition: all 0.3s ease;
            border: 1px solid var(--dark-border);
            position: relative;
            overflow: hidden;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(45deg, var(--gold-primary), var(--gold-secondary));
        }

        .feature-card:hover {
            transform: translateY(-15px);
            box-shadow: 0 25px 50px rgba(212, 175, 55, 0.2);
            border-color: var(--gold-primary);
        }

        .feature-card h3 {
            font-size: 1.6rem;
            margin-bottom: 1.5rem;
            color: var(--gold-primary);
        }

        .feature-card p {
            color: var(--dark-text);
            line-height: 1.7;
            font-size: 1.1rem;
        }

        .feature-icon {
            font-size: 4rem;
            margin-bottom: 2rem;
            display: block;
            background: linear-gradient(45deg, var(--gold-primary), var(--gold-secondary));
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Stats Section */
        .stats {
            padding: 100px 0;
            background: linear-gradient(135deg, var(--dark-card), var(--dark-bg));
            color: white;
            position: relative;
        }

        .stats::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" opacity="0.05"><path fill="%23D4AF37" d="M50 0l12 38 38 12-38 12-12 38-12-38-38-12 38-12z"/></svg>');
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 3rem;
            text-align: center;
            position: relative;
            z-index: 2;
        }

        .stat-item h3 {
            font-size: 4rem;
            font-weight: 700;
            margin-bottom: 1rem;
            background: linear-gradient(45deg, var(--gold-primary), var(--gold-secondary));
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .stat-item p {
            color: var(--gold-light);
            font-size: 1.3rem;
            font-weight: 300;
        }

        /* How It Works Section */
        .how-it-works {
            padding: 120px 0;
            background: var(--dark-bg);
        }

        .steps {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 3rem;
            margin-top: 4rem;
        }

        .step {
            text-align: center;
            padding: 3rem 2rem;
            position: relative;
            background: var(--dark-card);
            border-radius: 20px;
            border: 1px solid var(--dark-border);
            transition: all 0.3s ease;
        }

        .step:hover {
            transform: translateY(-10px);
            border-color: var(--gold-primary);
            box-shadow: 0 20px 40px rgba(212, 175, 55, 0.1);
        }

        .step-number {
            width: 80px;
            height: 80px;
            background: linear-gradient(45deg, var(--gold-primary), var(--gold-secondary));
            color: var(--dark-bg);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            font-weight: 700;
            margin: 0 auto 2rem;
            box-shadow: 0 10px 25px rgba(212, 175, 55, 0.3);
        }

        .step h4 {
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
            color: var(--gold-primary);
        }

        .step p {
            color: var(--dark-text);
            line-height: 1.7;
            font-size: 1.1rem;
        }

        /* CTA Section */
        .cta {
            padding: 120px 0;
            background: linear-gradient(135deg, var(--gold-primary), var(--gold-secondary));
            color: var(--dark-bg);
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .cta::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" opacity="0.1"><path fill="%231a1a1a" d="M50 0l50 50-50 50L0 50z"/></svg>');
            animation: rotate 20s linear infinite;
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .cta h2 {
            font-size: 3.5rem;
            margin-bottom: 1.5rem;
            position: relative;
            z-index: 2;
            font-weight: 700;
        }

        .cta p {
            font-size: 1.4rem;
            margin-bottom: 3rem;
            position: relative;
            z-index: 2;
            font-weight: 300;
        }

        .btn-cta {
            background: var(--dark-bg);
            color: var(--gold-primary);
            padding: 18px 50px;
            border: none;
            border-radius: 50px;
            font-size: 1.2rem;
            font-weight: 700;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            position: relative;
            z-index: 2;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.3);
        }

        .btn-cta:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.4);
            color: var(--gold-secondary);
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
            .hero-content h1 {
                font-size: 2.8rem;
            }

            .hero-content p {
                font-size: 1.2rem;
            }

            .features-grid {
                grid-template-columns: 1fr;
            }

            .section-title h2 {
                font-size: 2.5rem;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 480px) {
            .hero-content h1 {
                font-size: 2.2rem;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .step {
                padding: 2rem 1rem;
            }
        }
    </style>
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="nav-container">
                <div class="nav-logo">
                    <h2>FlyWme</h2>
                </div>
                <ul class="nav-menu">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="search.php">Cari Penerbangan</a></li>
                    <?php if(isLoggedIn()): ?>
                        <?php if(isAdmin()): ?>
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
        <!-- Hero Section -->
        <section class="hero">
            <div class="hero-content">
                <h1>Terbang Lebih Mewah dengan FlyWme</h1>
                <p>Pengalaman eksklusif dalam memesan tiket pesawat premium. Layanan kelas satu dengan harga terjangkau.</p>
                <a href="search.php" class="btn-hero">Jelajahi Penerbangan Eksklusif</a>
            </div>
        </section>

        <!-- Features Section -->
        <section class="features">
            <div class="container">
                <div class="section-title">
                    <h2>Kelebihan Eksklusif</h2>
                    <p>Nikmati pengalaman premium dengan layanan terbaik kami</p>
                </div>
                <div class="features-grid">
                    <div class="feature-card">
                        <span class="feature-icon">👑</span>
                        <h3>Layanan Premium</h3>
                        <p>Pengalaman memesan tiket dengan standar kelas satu. Interface elegan dan proses yang efisien.</p>
                    </div>
                    <div class="feature-card">
                        <span class="feature-icon">💎</span>
                        <h3>Harga Eksklusif</h3>
                        <p>Akses ke harga khusus untuk penerbangan premium. Nilai terbaik untuk pengalaman mewah.</p>
                    </div>
                    <div class="feature-card">
                        <span class="feature-icon">📱</span>
                        <h3>Design Elegan</h3>
                        <p>Interface yang sophisticated dan responsive. Pengalaman pengguna yang tak tertandingi.</p>
                    </div>
                    <div class="feature-card">
                        <span class="feature-icon">🛡️</span>
                        <h3>Keamanan Premium</h3>
                        <p>Transaksi terenkripsi dengan sistem keamanan tingkat tinggi. Data Anda terlindungi maksimal.</p>
                    </div>
                    <div class="feature-card">
                        <span class="feature-icon">🎫</span>
                        <h3>E-Ticket Mewah</h3>
                        <p>Tiket elektronik dengan design premium. Cetak atau simpan digital dengan gaya.</p>
                    </div>
                    <div class="feature-card">
                        <span class="feature-icon">⭐</span>
                        <h3>Priority Support</h3>
                        <p>Layanan pelanggan prioritas 24/7. Tim ahli siap membantu kebutuhan perjalanan Anda.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Stats Section -->
        <section class="stats">
            <div class="container">
                <div class="stats-grid">
                    <div class="stat-item">
                        <h3>15K+</h3>
                        <p>Member Eksklusif</p>
                    </div>
                    <div class="stat-item">
                        <h3>8K+</h3>
                        <p>Tiket Premium Terjual</p>
                    </div>
                    <div class="stat-item">
                        <h3>75+</h3>
                        <p>Maskapai Partner</p>
                    </div>
                    <div class="stat-item">
                        <h3>150+</h3>
                        <p>Destinasi Mewah</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- How It Works Section -->
        <section class="how-it-works">
            <div class="container">
                <div class="section-title">
                    <h2>Pengalaman Memesan Premium</h2>
                    <p>Empat langkah mudah menuju perjalanan mewah tak terlupakan</p>
                </div>
                <div class="steps">
                    <div class="step">
                        <div class="step-number">1</div>
                        <h4>Pilih Destinasi Eksklusif</h4>
                        <p>Tentukan kota asal dan tujuan dengan fasilitas pencarian canggih kami</p>
                    </div>
                    <div class="step">
                        <div class="step-number">2</div>
                        <h4>Seleksi Penerbangan Premium</h4>
                        <p>Pilih dari berbagai maskapai terbaik dengan layanan kelas satu</p>
                    </div>
                    <div class="step">
                        <div class="step-number">3</div>
                        <h4>Transaksi Aman</h4>
                        <p>Lakukan pembayaran dengan sistem keamanan premium terpercaya</p>
                    </div>
                    <div class="step">
                        <div class="step-number">4</div>
                        <h4>Terbang Mewah</h4>
                        <p>Dapatkan tiket elegan dan siap untuk pengalaman terbang tak terlupakan</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="cta">
            <div class="container">
                <h2>Siap untuk Pengalaman Terbang Mewah?</h2>
                <p>Bergabunglah dengan komunitas eksklusif kami dan nikmati perjalanan tak terlupakan</p>
                <a href="search.php" class="btn-cta">Mulai Perjalanan Mewah</a>
            </div>
        </section>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2025 FlyWme. Muhammad Naufal Bilal Syam (owner). | Layanan Pemesanan Tiket Pesawat Eksklusif</p>
        </div>
    </footer>
</body>
</html>