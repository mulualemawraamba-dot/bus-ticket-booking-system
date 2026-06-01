<?php
session_start();
require_once 'connection.php';

if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'passenger') {
    header("Location: login_signup.php");
    exit();
}

$from  = $_POST['from'];
$to    = $_POST['to'];
$date  = $_POST['date'];
$seats = $_POST['selected_seats'];

if (empty($seats)) {
    header("Location: booking.php?error=seat");
    exit();
}

$user_id = $_SESSION['user_id'];
$seatArray = explode(',', $seats);

$conn->begin_transaction();

try {
    $stmt = $conn->prepare("
        INSERT INTO bookings (user_id, from_city, to_city, journey_date, seats)
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("issss", $user_id, $from, $to, $date, $seats);
    $stmt->execute();

    $booking_id = $conn->insert_id;

    $seatStmt = $conn->prepare("
        INSERT INTO booked_seats (booking_id, journey_date, from_city, to_city, seat_number)
        VALUES (?, ?, ?, ?, ?)
    ");

    foreach ($seatArray as $seat) {
        $seat = (int)$seat;
        $seatStmt->bind_param("isssi", $booking_id, $date, $from, $to, $seat);
        $seatStmt->execute();
    }

    $conn->commit();

    header("Location: ticket.php?id=$booking_id");
    exit();

} catch (Exception $e) {
    $conn->rollback();
    header("Location: booking.php?error=seat_taken");
}
