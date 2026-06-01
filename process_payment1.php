<?php
session_start();
require_once 'connection.php';

if (!isset($_SESSION['email'])  $_SESSION['role'] !== 'passenger') {
    header("Location: login_signup.php");
    exit();
}

$amount = (float)$_POST['amount'];
$bank   = trim($_POST['bank_name']);
$ref    = trim($_POST['transaction_ref']);

if ($amount <= 0  empty($bank) || empty($ref)) {
    die("Invalid payment data");
}

/* Save payment WITHOUT booking */
$stmt = $conn->prepare("
    INSERT INTO payments (passenger_email, amount, bank_name, transaction_ref, status)
    VALUES (?, ?, ?, ?, 'paid')
");

$email = $_SESSION['email'];
$stmt->bind_param("sdss", $email, $amount, $bank, $ref);
$stmt->execute();

/* 🔓 UNLOCK SEAT SELECTION */
$_SESSION['payment_status'] = true;
$_SESSION['paid_amount']    = $amount;

header("Location: passenger_page.php");
exit();