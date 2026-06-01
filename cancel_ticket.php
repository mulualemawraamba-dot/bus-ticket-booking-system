<?php
session_start();
require_once 'connection.php';

if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'passenger') {
    header("Location: login_signup.php");
    exit();
}

if (isset($_POST['ticket_id'])) {
    $ticket_id = (int)$_POST['ticket_id'];
    $user_id   = $_SESSION['user_id'];

    // Verify ticket belongs to logged-in passenger
    $stmt = $conn->prepare("SELECT * FROM bookings WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $ticket_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo "<script>alert('❌ Ticket not found or you are not authorized'); window.location.href='booking.php';</script>";
        exit();
    }

    // Update ticket status to canceled
    $update = $conn->prepare("UPDATE bookings SET status = 'canceled', payment_status = 'refunded' WHERE id = ?");
    $update->bind_param("i", $ticket_id);

    if ($update->execute()) {
        // Optional: free booked seats
        $deleteSeats = $conn->prepare("DELETE FROM booked_seats WHERE booking_id = ?");
        $deleteSeats->bind_param("i", $ticket_id);
        $deleteSeats->execute();

        echo "<script>alert('✅ Ticket canceled successfully'); window.location.href='booking.php';</script>";
    } else {
        echo "<script>alert('❌ Failed to cancel ticket'); window.location.href='booking.php';</script>";
    }
}
?>