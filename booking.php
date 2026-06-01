<?php
session_start();
require_once 'connection.php';

if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'passenger') {
    header("Location: login_signup.php");
    exit();
}

// Routes and prices
$routeDistances = [
    'burie-addiss_ababa' => 350,
    'burie-bahir_dar' => 120,
    'burie-finote_selam' => 25,
    'burie-dembecha' => 50,
    'burie-durbete' => 90,
    'burie-dangila' => 70,
    'burie-debre_markos' => 100,
    'burie-merawi' => 95,
];

$pricePerKm = 2;
$bookedSeats = [];

// Fetch booked tickets for this passenger (optional, to show cancel button)
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM bookings WHERE user_id = ? ORDER BY id DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$myBookings = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book Ticket</title>
    <link rel="stylesheet" href="bt.css">
</head>
<body>

<h2>Bus Ticket Booking</h2>

<form method="POST" action="process_booking.php" id="booking-form">
    <!-- YOUR EXISTING FORM HTML HERE -->

    <input type="hidden" name="selected_seats" id="selected_seats">
    <button type="submit">Confirm Booking</button>
</form>

<hr>
<h2>My Bookings</h2>

<?php if ($myBookings->num_rows > 0): ?>
    <?php while ($booking = $myBookings->fetch_assoc()): ?>
        <div style="border:1px solid #ccc; padding:10px; margin-bottom:10px;">
            <p><strong>Ticket ID:</strong> <?= htmlspecialchars($booking['id']) ?></p>
            <p><strong>From:</strong> <?= htmlspecialchars($booking['from_city']) ?></p>
            <p><strong>To:</strong> <?= htmlspecialchars($booking['to_city']) ?></p>
            <p><strong>Date:</strong> <?= htmlspecialchars($booking['journey_date']) ?></p>
            <p><strong>Seats:</strong> <?= htmlspecialchars($booking['seats']) ?></p>
            <p><strong>Status:</strong> <?= htmlspecialchars($booking['status']) ?></p>

            <?php if ($booking['status'] !== 'canceled'): ?>
                <!-- CANCEL TICKET BUTTON -->
                <form method="POST" action="cancel_ticket.php" onsubmit="return confirm('Are you sure you want to cancel this ticket?');">
                    <input type="hidden" name="ticket_id" value="<?= $booking['id'] ?>">
                    <button type="submit" style="background-color:red; color:white;">❌ Cancel Ticket</button>
                </form>
            <?php else: ?>
                <p style="color:red;"><strong>Ticket Canceled</strong></p>
            <?php endif; ?>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <p>No bookings found.</p>
<?php endif; ?>

<script>
const bookedSeats = <?= json_encode($bookedSeats); ?>;
const routeDistances = <?= json_encode($routeDistances); ?>;
const pricePerKm = <?= $pricePerKm ?>;
</script>
<script src="Bus_Ticket.js"></script>

</body>
</html>