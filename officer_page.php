<?php
session_start();
require_once 'connection.php';

if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'officer') {
    header("Location: login_signup.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Officer Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="bt.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>

<main>
    <h1>Welcome Officer <?= htmlspecialchars($_SESSION['name']); ?></h1>
    <p>You are logged in as a ticket verification officer.</p>
        <div class="menu-icon" id="menu-toggle">
          <i class="fa-solid fa-bars"></i>
        </div>

    <div class="menu">
      <nav class="navbar">    
       <ul id="nav-links">
        <li><a href="index.php">Home</a></li>
        <li><a href="verify_ticket.php">verify ticket</a></li>
        <li><a href="#">check passenger details</a></li>
        <li><a href="#">view daily report</a></li>
        <li><a href="logout.php" class="login">Logout</a></li>
       <ul>
      </nav>
    </div>

    <br>

    <div style="text-align:center;">
        
    </div>
</main>

<footer>
    <p>Officer Panel  Bus Ticket Reservation System</p>
</footer>

<script src="Bus_Ticket.js"></script>

</body>
</html>
