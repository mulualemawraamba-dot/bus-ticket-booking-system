<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>About Us</title>
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
    <h1 class="about">About Our Bus Ticket Reservation System</h1>

    <p>
        Our Bus Ticket Reservation System is a modern web-based platform designed
        to make bus travel booking simple, fast, and reliable. It allows passengers
        to search routes, reserve seats, and manage bookings online without visiting
        a bus station.
    </p>

    <h2>Our Mission</h2>
    <p>
        Our mission is to provide a secure, user-friendly, and efficient ticket
        booking system that saves time and improves the travel experience for
        passengers, drivers, and transport officers.
    </p>

    <h2>What We Offer</h2>
    <ul class="availabletickets" style="display:block; opacity:1;">
        <li>Online bus ticket booking</li>
        <li>Seat selection and availability checking</li>
        <li>Role-based access (Admin, Driver, Passenger, Officer)</li>
        <li>Secure login and signup system</li>
        <li>Fast and reliable reservation process</li>
    </ul>

    <h2>User Roles</h2>
    <p>
        <strong>Passengers</strong> can book tickets and view their reservations.<br>
        <strong>Drivers</strong> can manage assigned trips.<br>
        <strong>Officers</strong> can monitor schedules and passengers.<br>
        <strong>Admins</strong> manage users, routes, and system settings.
    </p>

    <h2>Security & Reliability</h2>
    <p>
        We prioritize data security by using password encryption, session management,
        and role-based authorization to ensure safe access to the system.
    </p>

    <h2>Why Choose Our System?</h2>
    <p>
        Our system reduces manual work, minimizes booking errors, and provides
        real-time access to travel information — making bus transportation more
        efficient and accessible.
    </p>

    <h2>Contact Us</h2>
    <p>
        If you have questions or feedback, feel free to contact us through our
        official communication channels.
    </p>
</main>

<footer>
    <p>&copy; <?php echo date("Y"); ?> Burie Bus Ticket Reservation System. All rights reserved.</p>
</footer>

 <script src="Bus_Ticket.js"></script>

</body>
</html>
