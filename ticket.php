<?php
// tickets.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Available Tickets</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        <li><a href="login_signup.php">Login</a></li>
       <ul>
      </nav>
    <h1 class="about">Available Bus Tickets</h1>

    <p>Browse available bus routes, schedules, and ticket options.Select your preferred route and reserve your seat easily.</p>

    <h2>Available Routes</h2>

    <table style="display:table;">
        <tr>
            <th>Route</th>
            <th>Departure</th>
            <th>Arrival</th>
            <th>Time</th>
            <th>Price</th>
            <th>Type of Car</th>
            <th>Status</th>
        </tr>

        <tr>
            <td>Burie → Addis Ababa</td>
            <td>Burie</td>
            <td>Addis Ababa</td>
            <td>08:00 AM</td>
            <td>300 ETB</td>
            <td>Bus</td>
            <td>Available</td>
        </tr>

        <tr>
            <td>Burie → Debre Markos</td>
            <td>Burie</td>
            <td>Debre Markos</td>
            <td>09:30 AM</td>
            <td>450 ETB</td>
            <td>Minibus</td>
            <td>Available</td>
        </tr>

        <tr>
            <td>Burie → Bahir Dar</td>
            <td>Burie</td>
            <td>Bahir Dar</td>
            <td>06:00 AM</td>
            <td>600 ETB</td>
            <td>Minibus</td>
            <td>Limited Seats</td>
        </tr>

        <tr>
            <td>Burie → Finote Selam</td>
            <td>Burie</td>
            <td>Finote Selam</td>
            <td>05:30 AM</td>
            <td>800 ETB</td>
            <td>Minibus</td>
            <td>Full</td>
        </tr>
    </table>

    <h2>Ticket Information</h2>
    <p>Tickets can be reserved online by registered passengers.Please login to select seats and confirm your booking.
    </p>

    
    <ul class="availabletickets">
        <h2>Important Notes</h2>
        <li>Tickets are subject to availability</li>
        <li>Passengers must arrive 30 minutes before departure</li>
        <li>Valid ID is required during boarding</li>
        <li>Online booking closes 1 hour before departure</li>
    </ul>
</main>

<footer>
    <p>&copy; <?php echo date("Y"); ?> Burie Bus Ticket Reservation System. All rights reserved.</p>
</footer>

 <script src="Bus_Ticket.js"></script>

</body>
</html>
