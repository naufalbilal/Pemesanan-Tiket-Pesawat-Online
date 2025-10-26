<?php include 'includes/config.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Member Premium - FlyWme</title>
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
            min-height: 100vh;
            display: flex;
            flex-direction: column;
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

        .nav-logo a {
            text-decoration: none;
        }

        .nav-menu a {
            color: var(--dark-text);
            transition: all 0.3s ease;
        }

        .nav-menu a:hover,
        .nav-menu a.active {
            color: var(--gold-primary);
        }

        main {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 120px 20px 80px;
            background: linear-gradient(135deg, rgba(26, 26, 26, 0.9), rgba(45, 45, 45, 0.9)),
                        url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" opacity="0.03"><path fill="%23D4AF37" d="M50 0l50 50-50 50L0 50z"/></svg>');
        }

        .form-container {
            background: var(--dark-card);
            padding: 3rem;
            border-radius: 20px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.4);
            border: 1px solid var(--dark-border);
            width: 100%;
            max-width: 500px;
            position: relative;
            overflow: hidden;
        }

        .form-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(45deg, var(--gold-primary), var(--gold-secondary));
        }

        .form-container h2 {
            text-align: center;
            font-size: 2.2rem;
            margin-bottom: 2rem;
            background: linear-gradient(45deg, var(--gold-primary), var(--gold-secondary));
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: 700;
        }

        .alert {
            padding: 1rem 1.5rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            font-weight: 600;
            border: 1px solid;
        }

        .alert-error {
            background: rgba(220, 38, 38, 0.1);
            color: #fca5a5;
            border-color: rgba(220, 38, 38, 0.3);
        }

        .form-group {
            margin-bottom: 1.5rem;
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

        textarea.form-control {
            resize: vertical;
            min-height: 80px;
        }

        .btn-primary {
            background: linear-gradient(45deg, var(--gold-primary), var(--gold-secondary));
            color: var(--dark-bg);
            padding: 16px 40px;
            border: none;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 8px 25px rgba(212, 175, 55, 0.4);
            width: 100%;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(212, 175, 55, 0.6);
        }

        .form-footer {
            text-align: center;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--dark-border);
        }

        .form-footer p {
            color: var(--dark-text);
            margin-bottom: 0.5rem;
        }

        .form-footer a {
            color: var(--gold-primary);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .form-footer a:hover {
            color: var(--gold-secondary);
            text-decoration: underline;
        }

        footer {
            background: var(--dark-card);
            color: var(--dark-text);
            padding: 2rem 0;
            text-align: center;
            border-top: 2px solid var(--gold-primary);
        }

        footer p {
            margin: 0;
            font-size: 1rem;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            main {
                padding: 100px 15px 60px;
            }

            .form-container {
                padding: 2rem 1.5rem;
                margin: 0 10px;
            }

            .form-container h2 {
                font-size: 1.8rem;
            }
        }

        @media (max-width: 480px) {
            .form-container {
                padding: 1.5rem;
            }

            .form-container h2 {
                font-size: 1.6rem;
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
                    <li><a href="search.php">Cari Penerbangan</a></li>
                    <li><a href="login.php">Login</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <main>
        <div class="form-container">
            <h2>👑 Daftar </h2>
            
            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-error">
                    <?php
                    $errors = [
                        'password_mismatch' => '❌ Password dan konfirmasi password tidak cocok!',
                        'user_exists' => '❌ Username atau email sudah terdaftar!',
                        'registration_failed' => '❌ Pendaftaran gagal! Silakan coba lagi.'
                    ];
                    echo $errors[$_GET['error']] ?? '❌ Terjadi kesalahan!';
                    ?>
                </div>
            <?php endif; ?>

            <form action="process/register.php" method="POST">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" class="form-control" id="username" name="username" 
                           placeholder="Buat username unik" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" 
                           placeholder="email@contoh.com" required>
                </div>
                <div class="form-group">
                    <label for="full_name">Nama Lengkap</label>
                    <input type="text" class="form-control" id="full_name" name="full_name" 
                           placeholder="Masukkan nama lengkap" required>
                </div>
                <div class="form-group">
                    <label for="phone">Nomor Telepon</label>
                    <input type="tel" class="form-control" id="phone" name="phone" 
                           placeholder="0812-3456-7890" required>
                </div>
                <div class="form-group">
                    <label for="address">Alamat Lengkap</label>
                    <textarea class="form-control" id="address" name="address" rows="3" 
                              placeholder="Masukkan alamat lengkap" required></textarea>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" 
                           placeholder="Buat password kuat" required>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Konfirmasi Password</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" 
                           placeholder="Ulangi password" required>
                </div>
                <button type="submit" class="btn btn-primary">Daftar Member Premium</button>
            </form>
            <div class="form-footer">
                <p>Sudah punya akun eksklusif?</p>
                <a href="login.php">Login ke akun premium di sini</a>
            </div>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2025 FlyWme. Muhammad Naufal Bilal Syam (owner). | Komunitas Eksklusif Penerbangan Premium</p>
        </div>
    </footer>
</body>
</html>