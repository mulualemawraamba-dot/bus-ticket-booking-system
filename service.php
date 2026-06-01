<?php
// service.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Our Services | Bus Ticket Reservation</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="bt.css">
</head>
<body>

<main>

        <div class="menu-icon" id="menu-toggle">
          <i class="fa-solid fa-bars"></i>
        </div>

      <nav class="navbar">    
       <ul id="nav-links">
        <li><a href="index.php">Home</a></li>
        <li><a href="about.php">About Us</a></li>
        <li><a href="ticket.php">Tickets</a></li>
        <li><a href="service.php">Services</a></li>
        <li><a href="contact.php">Contact us</a></li>
        <li><a href="login_signup.php">Login</a></li>
      </nav>
    <h1 class="about">Our Services</h1>

    <p>
        Our Bus Ticket Reservation System provides a wide range of services designed
        to simplify bus travel management for passengers, drivers, officers, and
        administrators.
    </p>

    <h2>Passenger Services</h2>
    <ul class="availabletickets" style="display:block; opacity:1;">
        <li>Search available bus routes and schedules</li>
        <li>Online ticket booking</li>
        <li>Seat selection and availability checking</li>
        <li>View and manage reservations</li>
        <li>Secure login and account management</li>
    </ul>

    <h2>Driver Services</h2>
    <p>
        Drivers can access their assigned trips, view passenger lists, and manage
        trip-related information efficiently through the system.
    </p>

    <h2>Officer Services</h2>
    <p>
        Transport officers can monitor routes, schedules, and passengers to ensure
        smooth operation and compliance with transport regulations.
    </p>

    <h2>Admin Services</h2>
    <ul class="availabletickets" style="display:block; opacity:1;">
        <li>User management (Admin, Driver, Passenger, Officer)</li>
        <li>Route and schedule management</li>
        <li>Bus and seat configuration</li>
        <li>System monitoring and control</li>
        <li>Security and access control</li>
    </ul>

    <h2>Secure & Reliable Service</h2>
    <p>
        Our system uses password encryption, session handling, and role-based access
        control to ensure secure and reliable service for all users.
    </p>

    <h2>Service Benefits</h2>
    <p>
        By using our platform, users save time, reduce manual errors, and enjoy a
        faster, more organized ticket reservation experience.
    </p>
</main>

<footer>
    <p>&copy; <?php echo date("Y"); ?> Bus Ticket Reservation System. All rights reserved.</p>
</footer>

 <script src="Bus_Ticket.js"></script>

</body>
</html>
