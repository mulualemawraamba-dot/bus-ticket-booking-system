<?php
session_start();
require_once 'connection.php';

if ($_SESSION['role'] !== 'admin') {
    exit();
}

$id = $_GET['id'];

$conn->query("UPDATE payments SET status='approved' WHERE id=$id");

/* OPTIONAL: Mark booking as PAID */
$conn->query("
    UPDATE bookings 
    SET payment_status='paid'
    WHERE id = (
        SELECT booking_id FROM payments WHERE id=$id
    )
");

header("Location: admin_page.php");
exit();
