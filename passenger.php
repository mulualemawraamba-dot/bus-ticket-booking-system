<?php
session_start();

if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'passenger') {
    header("Location: login_signup.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Passenger Dashboard</title>
    <link rel="stylesheet" href="bt.css">
</head>
<body>

<h1>Welcome <?= htmlspecialchars($_SESSION['name']) ?></h1>
<p>Passenger Dashboard</p>

<nav>
    <a href="index.php">Home</a>
    <a href="booking.php">Book Ticket</a>
    <a href="logout.php">Logout</a>
</nav>

</body>
</html>
