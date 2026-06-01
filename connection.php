<?php
$conn = mysqli_connect("localhost", "root", "", "bus_ticket_system");

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

define('ADMIN_SECRET', 'awraamba@1964');
//echo "connection successful";
?>