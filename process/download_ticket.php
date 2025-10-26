<?php
include '../includes/config.php';
include '../includes/functions.php';
include '../includes/auth.php';

if (!isLoggedIn()) {
    redirect('../login.php');
}

if (!isset($_GET['booking_id'])) {
    die('Booking ID tidak valid');
}

$booking_id = $_GET['booking_id'];
$user_id = $_SESSION['user_id'];

// Get booking details
$stmt = $pdo->prepare("
    SELECT b.*, f.*, a.name as airline_name, a.code as airline_code, u.full_name as user_name, u.email as user_email
    FROM bookings b
    JOIN flights f ON b.flight_id = f.id
    JOIN airlines a ON f.airline_id = a.id
    JOIN users u ON b.user_id = u.id
    WHERE b.id = ? AND b.user_id = ? AND b.status = 'paid'
");
$stmt->execute([$booking_id, $user_id]);
$booking = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$booking) {
    die('Tiket tidak ditemukan atau belum dibayar');
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Ticket - <?php echo $booking['booking_code']; ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background: #fff;
            padding: 20px;
        }
        
        .ticket-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            border: 2px solid #2563eb;
            border-radius: 10px;
            position: relative;
        }
        
        .ticket-header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #2563eb;
        }
        
        .ticket-header h1 {
            color: #2563eb;
            font-size: 28px;
            margin-bottom: 10px;
        }
        
        .booking-code {
            font-size: 18px;
            font-weight: bold;
            color: #666;
        }
        
        .section {
            margin-bottom: 25px;
            padding: 15px;
            background: #f8fafc;
            border-radius: 8px;
        }
        
        .section h2 {
            color: #2563eb;
            margin-bottom: 15px;
            font-size: 18px;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 8px;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        
        .info-item {
            margin-bottom: 8px;
        }
        
        .info-label {
            font-weight: bold;
            color: #64748b;
        }
        
        .flight-info {
            display: grid;
            grid-template-columns: 2fr 1fr 2fr;
            align-items: center;
            text-align: center;
            padding: 20px;
            background: white;
            border-radius: 8px;
            margin: 15px 0;
        }
        
        .flight-time {
            font-size: 24px;
            font-weight: bold;
            color: #2563eb;
        }
        
        .flight-city {
            color: #64748b;
            margin-top: 5px;
        }
        
        .flight-date {
            font-size: 14px;
            color: #94a3b8;
            margin-top: 5px;
        }
        
        .flight-duration {
            text-align: center;
        }
        
        .duration {
            font-weight: bold;
            color: #2563eb;
        }
        
        .route {
            font-size: 24px;
            color: #2563eb;
            margin: 10px 0;
        }
        
        .barcode {
            text-align: center;
            margin: 20px 0;
            padding: 15px;
            background: #f8fafc;
            border-radius: 8px;
        }
        
        .barcode-text {
            font-family: 'Courier New', monospace;
            font-size: 18px;
            letter-spacing: 3px;
            margin-top: 10px;
        }
        
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
            color: #64748b;
            font-size: 14px;
        }
        
        .action-buttons {
            text-align: center;
            margin: 20px 0;
            position: sticky;
            top: 10px;
            background: white;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            z-index: 100;
        }
        
        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            margin: 0 10px;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s;
        }
        
        .btn-print {
            background: #2563eb;
            color: white;
        }
        
        .btn-print:hover {
            background: #1d4ed8;
        }
        
        .btn-close {
            background: #64748b;
            color: white;
        }
        
        .btn-close:hover {
            background: #475569;
        }
        
        .btn-back {
            background: #10b981;
            color: white;
        }
        
        .btn-back:hover {
            background: #059669;
        }
        
        @media print {
            .action-buttons {
                display: none;
            }
            
            .ticket-container {
                border: none;
                box-shadow: none;
            }
            
            body {
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <div class="action-buttons">
        <button class="btn btn-print" onclick="window.print()">🖨️ Print Tiket</button>
        <a href="../user/dashboard.php" class="btn btn-back">← Kembali ke Dashboard</a>
        <button class="btn btn-close" onclick="closeWindow()">✕ Tutup Window</button>
    </div>

    <div class="ticket-container">
        <div class="ticket-header">
            <h1>E-TICKET FLYWME</h1>
            <div class="booking-code">Kode Booking: <?php echo $booking['booking_code']; ?></div>
        </div>

        <!-- Passenger Information -->
        <div class="section">
            <h2>INFORMASI PENUMPANG</h2>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Nama Penumpang</div>
                    <div><?php echo $booking['passenger_name']; ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Email</div>
                    <div><?php echo $booking['passenger_email']; ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Telepon</div>
                    <div><?php echo $booking['passenger_phone']; ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Pemesan</div>
                    <div><?php echo $booking['user_name']; ?> (<?php echo $booking['user_email']; ?>)</div>
                </div>
            </div>
        </div>

        <!-- Flight Information -->
        <div class="section">
            <h2>INFORMASI PENERBANGAN</h2>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Maskapai</div>
                    <div><?php echo $booking['airline_name']; ?> (<?php echo $booking['airline_code']; ?>)</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Nomor Penerbangan</div>
                    <div><?php echo $booking['flight_number']; ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Rute</div>
                    <div><?php echo $booking['departure_city']; ?> → <?php echo $booking['arrival_city']; ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Tanggal Booking</div>
                    <div><?php echo date('d M Y H:i', strtotime($booking['booking_date'])); ?></div>
                </div>
            </div>

            <div class="flight-info">
                <div class="flight-departure">
                    <div class="flight-time"><?php echo date('H:i', strtotime($booking['departure_time'])); ?></div>
                    <div class="flight-city"><?php echo $booking['departure_city']; ?></div>
                    <div class="flight-date"><?php echo date('d M Y', strtotime($booking['departure_date'])); ?></div>
                </div>
                <div class="flight-duration">
                    <div class="route">→</div>
                    <div class="duration">
                        <?php 
                        $duration = strtotime($booking['arrival_time']) - strtotime($booking['departure_time']);
                        echo gmdate('H:i', $duration);
                        ?>
                    </div>
                    <div class="flight-city">Langsung</div>
                </div>
                <div class="flight-arrival">
                    <div class="flight-time"><?php echo date('H:i', strtotime($booking['arrival_time'])); ?></div>
                    <div class="flight-city"><?php echo $booking['arrival_city']; ?></div>
                    <div class="flight-date"><?php echo date('d M Y', strtotime($booking['arrival_date'])); ?></div>
                </div>
            </div>
        </div>

        <!-- Payment Information -->
        <div class="section">
            <h2>INFORMASI PEMBAYARAN</h2>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Total Harga</div>
                    <div>Rp <?php echo number_format($booking['total_price'], 0, ',', '.'); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Status</div>
                    <div style="color: #10b981; font-weight: bold;"><?php echo strtoupper($booking['status']); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Tanggal Pembayaran</div>
                    <div><?php echo $booking['payment_date'] ? date('d M Y H:i', strtotime($booking['payment_date'])) : '-'; ?></div>
                </div>
            </div>
        </div>

        <!-- Barcode -->
        <div class="barcode">
            <div style="font-size: 14px; color: #64748b; margin-bottom: 10px;">KODE BOARDING</div>
            <div class="barcode-text">*<?php echo $booking['booking_code']; ?>*</div>
            <div style="margin-top: 15px; font-size: 12px; color: #64748b;">
                *Tunjukkan tiket ini saat check-in di bandara
            </div>
        </div>

        <div class="footer">
            <p><strong>FlyWme - Sistem Pemesanan Tiket Pesawat</strong></p>
            <p>Terima kasih telah memilih FlyWme. Selamat menikmati perjalanan Anda!</p>
            <p style="margin-top: 10px;">Tiket ini berlaku sebagai bukti pembayaran yang sah</p>
        </div>
    </div>

    <script>
        function closeWindow() {
            // Coba beberapa metode untuk menutup window
            if (window.history.length > 1) {
                // Jika ada history, gunakan back
                window.history.back();
            } else if (window.opener) {
                // Jika dibuka dari window lain
                window.close();
            } else {
                // Fallback: redirect ke dashboard
                window.location.href = '../user/dashboard.php';
            }
        }

        // Tambahkan event listener untuk keyboard (ESC key)
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeWindow();
            }
        });

        // Auto print jika diinginkan (optional)
        // setTimeout(() => { window.print(); }, 1000);
        
        // CSS untuk print
        const style = document.createElement('style');
        style.textContent = `
            @media print {
                .action-buttons {
                    display: none !important;
                }
                .ticket-container {
                    border: none !important;
                    box-shadow: none !important;
                }
                body {
                    padding: 0 !important;
                }
                body * {
                    visibility: hidden;
                }
                .ticket-container, .ticket-container * {
                    visibility: visible;
                }
                .ticket-container {
                    position: absolute;
                    left: 0;
                    top: 0;
                    width: 100%;
                }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>