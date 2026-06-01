<?php
session_start();
require_once 'connection.php';

$id = $_GET['id'];

$stmt = $conn->prepare("
    SELECT b.*, u.name
    FROM bookings b
    JOIN users u ON u.id = b.user_id
    WHERE b.id = ?
");
$stmt->bind_param("i", $id);
$stmt->execute();
$ticket = $stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Ticket</title>
</head>
<body>

<h2> Bus Ticket</h2>

<p><b>Ticket ID:</b> <?= $ticket['id'] ?></p>
<p><b>Name:</b> <?= $ticket['name'] ?></p>
<p><b>From:</b> <?= $ticket['from_city'] ?></p>
<p><b>To:</b> <?= $ticket['to_city'] ?></p>
<p><b>Date:</b> <?= $ticket['journey_date'] ?></p>
<p><b>Seats:</b> <?= $ticket['seats'] ?></p>

<button onclick="window.print()">🖨️ Print</button>

</body>
</html>
