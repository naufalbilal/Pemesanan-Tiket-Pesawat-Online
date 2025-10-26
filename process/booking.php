<?php
include '../includes/config.php';
include '../includes/functions.php';
include '../includes/auth.php';

if (!isLoggedIn() || isAdmin()) {
    redirect('../login.php');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $flight_id = $_POST['flight_id'];
    $passengers = $_POST['passengers'];
    $passenger_name = $_POST['passenger_name'];
    $passenger_email = $_POST['passenger_email'];
    $passenger_phone = $_POST['passenger_phone'];
    
    // Validate input
    if (empty($flight_id) || empty($passengers) || empty($passenger_name) || empty($passenger_email) || empty($passenger_phone)) {
        header('Location: ../booking.php?error=missing_fields&flight_id=' . $flight_id . '&passengers=' . $passengers);
        exit();
    }
    
    // Check flight availability
    $stmt = $pdo->prepare("SELECT * FROM flights WHERE id = ? AND seats_available >= ?");
    $stmt->execute([$flight_id, $passengers]);
    $flight = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$flight) {
        header('Location: ../booking.php?error=not_available&flight_id=' . $flight_id . '&passengers=' . $passengers);
        exit();
    }
    
    // Calculate total price
    $total_price = $flight['price'] * $passengers;
    $booking_code = generateBookingCode();
    
    // Start transaction
    $pdo->beginTransaction();
    
    try {
        // Create booking
        $stmt = $pdo->prepare("
            INSERT INTO bookings (user_id, flight_id, booking_code, passenger_name, passenger_email, passenger_phone, total_price) 
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        
        $stmt->execute([
            $_SESSION['user_id'],
            $flight_id,
            $booking_code,
            $passenger_name,
            $passenger_email,
            $passenger_phone,
            $total_price
        ]);
        
        $booking_id = $pdo->lastInsertId();
        
        // Update available seats
        $stmt = $pdo->prepare("UPDATE flights SET seats_available = seats_available - ? WHERE id = ?");
        $stmt->execute([$passengers, $flight_id]);
        
        // Commit transaction
        $pdo->commit();
        
        // Redirect to payment page
        header("Location: payment.php?booking_id=$booking_id");
        exit();
        
    } catch (Exception $e) {
        // Rollback transaction on error
        $pdo->rollBack();
        header('Location: ../booking.php?error=booking_failed&flight_id=' . $flight_id . '&passengers=' . $passengers);
        exit();
    }
} else {
    // If not POST request, redirect to search
    redirect('../search.php');
}
?>