<?php
session_start();
require_once 'connection.php';

if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'passenger') {
    header("Location: login_signup.php");
    exit();
}

$booking_id = $_POST['booking_id'];
$email      = $_POST['passenger_email'];
$amount     = $_POST['amount'];
$bank       = $_POST['bank_name'];
$ref        = $_POST['transaction_ref'];

$stmt = $conn->prepare("
    INSERT INTO payments 
    (booking_id, passenger_email, amount, bank_name, transaction_ref)
    VALUES (?, ?, ?, ?, ?)
");
$stmt->bind_param("isdss", $booking_id, $email, $amount, $bank, $ref);
$stmt->execute();

header("Location: passenger_page.php?payment=success");
exit();
