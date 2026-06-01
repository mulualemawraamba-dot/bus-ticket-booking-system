<?php
session_start();
require_once 'connection.php';

if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'driver') {
    header("Location: login_signup.php");
    exit();
}

$driverName  = $_SESSION['name'];
$driverEmail = $_SESSION['email'];
$today = date('Y-m-d');
$errorMsg = "";

/* ENSURE TODAY'S STATUS ROW EXISTS */
$check = $conn->prepare(
    "SELECT id FROM driver_trip_status 
     WHERE driver_email = ? AND travel_date = ?"
);
$check->bind_param("ss", $driverEmail, $today);
$check->execute();
$exists = $check->get_result();

if ($exists->num_rows === 0) {
    $insert = $conn->prepare(
        "INSERT INTO driver_trip_status (driver_email, travel_date, trip_status)
         VALUES (?, ?, 'not_started')"
    );
    $insert->bind_param("ss", $driverEmail, $today);
    $insert->execute();
}

/* FETCH TODAY'S ROUTES */
$routeStmt = $conn->prepare(
    "SELECT route_from, route_to, travel_date,
            departure_time, vehicle_type, vehicle_serial
     FROM driver_routes
     WHERE driver_email = ? AND travel_date = ?"
);
$routeStmt->bind_param("ss", $driverEmail, $today);
$routeStmt->execute();
$routes = $routeStmt->get_result();
$hasRouteToday = ($routes->num_rows > 0);

/* GET DEPARTURE TIME */
$departureTime = null;
if ($hasRouteToday) {
    $routeRow = $routes->fetch_assoc();
    $departureTime = $routeRow['departure_time'];
    $routes->data_seek(0); // reset pointer
}

/* GET TRIP STATUS */
$statusStmt = $conn->prepare(
    "SELECT trip_status FROM driver_trip_status 
     WHERE driver_email = ? AND travel_date = ?"
);
$statusStmt->bind_param("ss", $driverEmail, $today);
$statusStmt->execute();
$statusResult = $statusStmt->get_result();
$row = $statusResult->fetch_assoc();
$tripStatus = $row ? $row['trip_status'] : 'not_started';

/* START TRIP */
if (
    isset($_POST['start_trip']) &&
    $hasRouteToday &&
    $tripStatus === 'not_started'
) {
    $currentTime = date('H:i:s');

    if ($currentTime < $departureTime) {
        $errorMsg = " You cannot start the trip before departure time (" .
                    date("h:i A", strtotime($departureTime)) . ").";
    } else {
        $update = $conn->prepare(
            "UPDATE driver_trip_status
             SET trip_status = 'on_the_way'
             WHERE driver_email = ? AND travel_date = ?"
        );
        $update->bind_param("ss", $driverEmail, $today);
        $update->execute();

        header("Location: driver_page.php");
        exit();
    }
}

/* END TRIP */
if (
    isset($_POST['end_trip']) &&
    $hasRouteToday &&
    $tripStatus === 'started'
) {
    $update = $conn->prepare(
        "UPDATE driver_trip_status
         SET trip_status = 'completed'
         WHERE driver_email = ? AND travel_date = ?"
    );
    $update->bind_param("ss", $driverEmail, $today);
    $update->execute();

    header("Location: driver_page.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Driver Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="bt.css">
</head>
<body>

<h2>Driver Dashboard</h2>

<p>Welcome, <strong><?= htmlspecialchars($driverName) ?></strong></p>

        <div class="menu-icon" id="menu-toggle">
          <i class="fa-solid fa-bars"></i>
        </div>

    <div class="menu">
      <nav class="navbar">    
       <ul id="nav-links">
        <li><a href="index.php">Home</a></li>

        <li><a href="logout.php" class="login">Logout</a></li>
       <ul>
      </nav>
    </div>

<h3>Driver Information</h3>
<p><strong>Name:</strong> <?= htmlspecialchars($driverName) ?></p>
<p><strong>Email:</strong> <?= htmlspecialchars($driverEmail) ?></p>

<hr>

<h3>Assigned Routes (Today)</h3>

<?php if ($routes->num_rows > 0): ?>
<table border="1" cellpadding="8">
<tr>
    <th>From</th>
    <th>To</th>
    <th>Date</th>
    <th>Departure Time</th>
    <th>Vehicle Type</th>
    <th>Vehicle Serial</th>
</tr>

<?php while ($r = $routes->fetch_assoc()): ?>
<tr>
    <td><?= htmlspecialchars($r['route_from']) ?></td>
    <td><?= htmlspecialchars($r['route_to']) ?></td>
    <td><?= htmlspecialchars($r['travel_date']) ?></td>
    <td><?= date("h:i A", strtotime($r['departure_time'])) ?></td>
    <td><?= htmlspecialchars($r['vehicle_type']) ?></td>
    <td><?= htmlspecialchars($r['vehicle_serial']) ?></td>
</tr>
<?php endwhile; ?>
</table>
<?php else: ?>
<p>No routes assigned for today.</p>
<?php endif; ?>

<hr>

<h3>Daily Trip Status (<?= $today ?>)</h3>
<p><strong>Current Status:</strong> <?= ucfirst($tripStatus) ?></p>

<?php if (!empty($errorMsg)): ?>
    <p style="color:red; font-weight:bold;"><?= $errorMsg ?></p>
<?php endif; ?>

<form method="post">
<?php if (!$hasRouteToday): ?>
    <p>No route assigned. Trip cannot be started.</p>

<?php elseif ($tripStatus === 'not_started'): ?>
    <button type="submit" name="start_trip">Start Trip</button>

<?php elseif ($tripStatus === 'started'): ?>
    <button type="submit" name="end_trip">End Trip</button>

<?php else: ?>
    <p>Trip completed for today.</p>
<?php endif; ?>
</form>

<hr>

 <script src="Bus_Ticket.js"></script>

</body>
</html>
