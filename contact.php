<?php
// contact.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contact Us | Bus Ticket Reservation</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="bt.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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

    
    <h1 class="about">Contact Us</h1>

    <p>
        If you have any questions, feedback, or need assistance with ticket booking,
        feel free to contact us using the information below.
    </p>

    <h2>Office Address</h2>
    <p>
        Bus Ticket Reservation System<br>
        Burie, Ethiopia
    </p>

    <h2>Contact Information</h2>
    <p>
        <strong>Phone:</strong> +251 947369897<br>
        <strong>Email:</strong> mulualemawraamba@gmail.com
    </p>

    <h2>Working Hours</h2>
    <p>
        Monday - Friday: 8:00 AM - 6:00 PM<br>
        Saturday: 9:00 AM - 2:00 PM<br>
        Sunday: Closed
    </p>

    <h2>Send Us a Message</h2>

    <div class="form-group active">
        <form method="post" action="#">
            <label>Name</label>
            <input type="text" name="name" placeholder="Enter your name" required><br>

            <label>Email</label>
            <input type="email" name="email" placeholder="Enter your email" required><br>

            <label>Message</label>
            <textarea name="message" rows="4" placeholder="Write your message here..."
                style="width:50%; border-radius:20px; padding:10px;" required></textarea>

            <div class="form-buttons">
                <input type="submit" value="Send Message">
            </div>
        </form>
    </div>

    <h2>Note</h2>
    <p>
        Our support team will respond to your message as soon as possible.
        Thank you for using our Bus Ticket Reservation System.
    </p>
</main>

<footer class="foot"><address>
        <p id="p5" class="odd">you can get our support at:</p>
    <a href="#">send email</a><br>
    <a href="#">call us</a><br>
            <p>Follow us on:</p>
<div class="social-links">
    <a href="#"><i class="fa-brands fa-facebook"></i></a>
    <a href="#"><i class="fa-brands fa-youtube"></i></a>
    <a href="#"><i class="fa-brands fa-twitter"></i></a>
    <a href="#"><i class="fa-brands fa-telegram"></i></a>
    <a href="#"><i class="fa-brands fa-whatsapp"></i></a>
    <a href="#"><i class="fa-brands fa-instagram"></i></a>
</div></address>       
        <p>&copy; <?php echo date("Y"); ?> Burie Bus Ticket Reservation System. All rights reserved.</p>
    </footer>

     <script src="Bus_Ticket.js"></script>

</body>
</html>
