<?php
session_start();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="bt.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Home</title>
</head>
<body>
    
    <div class="container">    
    <header>
        <h1 class="logo">Bus Booking</h1>
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
        <?php if (!isset($_SESSION['email'])): ?>
    <li><a href="login_signup.php">Login</a></li>
<?php else: ?>
    <?php if ($_SESSION['role'] === 'admin'): ?>
        <li><a href="admin_page.php">Admin Panel</a></li>
    <?php endif; ?>
    <li><a href="logout.php">Logout</a></li>
<?php endif; ?>

            
       </ul> 
           
        <!--<a href="" class="download">Download</a>   -->
        
            
      </nav>
      
      
               
    </header>
    
    
    </div> 

    <script src="Bus_Ticket.js"></script>
      
</body>
</html>