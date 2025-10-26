<?php
function generateBookingCode() {
    return 'FLY' . strtoupper(substr(md5(uniqid()), 0, 8));
}

function formatDate($date) {
    return date('d M Y', strtotime($date));
}

function formatTime($time) {
    return date('H:i', strtotime($time));
}

function redirect($url) {
    header("Location: $url");
    exit();
}

// Hapus function isLoggedIn() dan isAdmin() dari sini karena sudah pindah ke auth.php
?>