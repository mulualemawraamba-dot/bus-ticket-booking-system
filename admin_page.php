<?php
session_start();
require_once 'connection.php';

if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'admin') {
    header("Location: login_signup.php");
    exit();
}

$successMsg = "";
$errorMsg   = "";

if (isset($_POST['assign_driver'])) {

    $bookingId      = $_POST['booking_id'] ?? null;
    $driverEmail    = $_POST['driver_email'] ?? null;
    $from           = $_POST['from_city'] ?? null;
    $to             = $_POST['to_city'] ?? null;
    $date           = $_POST['journey_date'] ?? null;
    $departure_time = $_POST['departure_time'] ?? null;
    $vehicle_type   = $_POST['vehicle_type'] ?? null;
    $vehicle_serial = $_POST['vehicle_serial'] ?? null;

    if (
        !$bookingId || !$driverEmail || !$from || !$to || !$date ||
        !$departure_time || !$vehicle_type || !$vehicle_serial
    ) {
        $errorMsg = " Missing required data.";
    }
    elseif ($date < date('Y-m-d')) {
        $errorMsg = " You cannot assign a driver to a past trip.";
    }
    else {

        /* Check booking already assigned */
        $checkBooking = $conn->prepare(
            "SELECT id FROM driver_routes WHERE booking_id = ?"
        );
        $checkBooking->bind_param("i", $bookingId);
        $checkBooking->execute();

        if ($checkBooking->get_result()->num_rows > 0) {
            $errorMsg = " This booking already has a driver.";
        }
        else {

            /* Check driver availability */
            $checkDriver = $conn->prepare(
                "SELECT id FROM driver_routes
                 WHERE driver_email = ? AND travel_date = ?"
            );
            $checkDriver->bind_param("ss", $driverEmail, $date);
            $checkDriver->execute();

            if ($checkDriver->get_result()->num_rows > 0) {
                $errorMsg = " Driver already assigned for this date.";
            }
            else {

                /* Insert assignment */
                $insert = $conn->prepare(
                    "INSERT INTO driver_routes
                     (booking_id, driver_email, route_from, route_to, travel_date,
                      departure_time, vehicle_type, vehicle_serial)
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
                );

                $insert->bind_param(
                    "isssssss",
                    $bookingId,
                    $driverEmail,
                    $from,
                    $to,
                    $date,
                    $departure_time,
                    $vehicle_type,
                    $vehicle_serial
                );

                $insert->execute();
                $successMsg = "✅ Driver assigned successfully.";
            }
        }
    }
}

/* Fetch drivers */
$drivers = $conn->query(
    "SELECT name, email FROM users WHERE role='driver'"
);

/* Fetch bookings */
$result = $conn->query(
    "SELECT bookings.*, users.name, users.email
     FROM bookings
     JOIN users ON bookings.user_id = users.id
     ORDER BY bookings.created_at DESC"
);

$driverList = [];
while ($d = $drivers->fetch_assoc()) {
    $driverList[] = $d;
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="bt.css">
</head>
<body>
  <div class="container">
   <main>

        <!-- SUCCESS / ERROR MESSAGES (ADD HERE) -->
    <?php if (!empty($successMsg)): ?>
        <p style="color:green;"><?= $successMsg ?></p>
    <?php endif; ?>

    <?php if (!empty($errorMsg)): ?>
        <p style="color:red;"><?= $errorMsg ?></p>
    <?php endif; ?>
    <!--  END MESSAGE BLOCK -->
    <h1>Welcome <?php echo $_SESSION['name']; ?></h1>
    <p>This page is accessible only to users with the <b>'admin'</b> role.</p>
            <div class="menu-icon" id="menu-toggle">
          <i class="fa-solid fa-bars"></i>
        </div>

    <div class="menu">
      <nav class="navbar">    
       <ul id="nav-links">
        <li><a href="index.php">Home</a></li>
        <li><a href="admin_approve.php">Approve Drivers & Officers</a></li>
        <li><a href="logout.php" class="login">Logout</a></li>
       <ul>
      </nav>
    </div>
    <h2>Manage Users</h2>

    <h2>All Bookings</h2>

<table border="1" cellpadding="10">
<tr>
    <th>#</th>
    <th>Passenger</th>
    <th>Email</th>
    <th>From</th>
    <th>To</th>
    <th>Date</th>
    <th>Seats</th>
    <th>Total Price</th>
    <th>Booked At</th>
    <th>Assign Driver</th>
</tr>


    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
<tr>
    <td><?= $row['id'] ?></td>
    <td><?= htmlspecialchars($row['name']) ?></td>
    <td><?= htmlspecialchars($row['email']) ?></td>
    <td><?= htmlspecialchars($row['from_city']) ?></td>
    <td><?= htmlspecialchars($row['to_city']) ?></td>
    <td><?= $row['journey_date'] ?></td>
    <td><?= $row['seats'] ?></td>
    <td><?= $row['total_price'] ?> ETB</td>
    <td><?= $row['created_at'] ?></td>

 
    <!-- ASSIGN DRIVER FORM -->
<td>
<form method="post">

    <input type="hidden" name="booking_id" value="<?= $row['id'] ?>">
    <input type="hidden" name="from_city" value="<?= $row['from_city'] ?>">
    <input type="hidden" name="to_city" value="<?= $row['to_city'] ?>">
    <input type="hidden" name="journey_date" value="<?= $row['journey_date'] ?>">

    <select name="driver_email" required>
        <option value="">Select Driver</option>
        <?php foreach ($driverList as $driver): ?>
            <option value="<?= $driver['email'] ?>">
                <?= htmlspecialchars($driver['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <input type="time" name="departure_time" required>

    <select name="vehicle_type" required>
        <option value="">Vehicle Type</option>
        <option value="Bus">Bus</option>
        <option value="Mini Bus">Mini Bus</option>
        <option value="Coaster">Coaster</option>
    </select>

    <input type="text"
           name="vehicle_serial"
           placeholder="Car Serial Number"
           required>

    <button type="submit" name="assign_driver">Assign</button>
</form>
</td>


</tr>

        <?php endwhile; ?>
    <?php else: ?>
        <tr><td colspan="9">No bookings found</td></tr>
    <?php endif; ?>
</table>

<h2>Pending Bank Transfers</h2>

<table border="1">
<tr>
    <th>Booking</th>
    <th>Email</th>
    <th>Amount</th>
    <th>Bank</th>
    <th>Reference</th>
    <th>Action</th>
</tr>

<?php
$result = $conn->query("SELECT * FROM payments WHERE status='pending'");
while ($row = $result->fetch_assoc()):
?>
<tr>
    <td><?= $row['booking_id'] ?></td>
    <td><?= $row['passenger_email'] ?></td>
    <td><?= $row['amount'] ?> ETB</td>
    <td><?= $row['bank_name'] ?></td>
    <td><?= $row['transaction_ref'] ?></td>
    <td>
        <a href="approve_payment.php?id=<?= $row['id'] ?>">Approve</a>
        |
        <a href="reject_payment.php?id=<?= $row['id'] ?>">Reject</a>
    </td>
</tr>
<?php endwhile; ?>
</table>




   </main>
  </div>

  <script src="Bus_Ticket.js"></script>
    
</body>
</html>