<?php
session_start();
require_once 'connection.php';

// Initialize payment status
if (!isset($_SESSION['payment_status'])) {
    $_SESSION['payment_status'] = false;
}

$ticket_id = null; //This variable will store the generated Ticket ID



if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'passenger') {
    header("Location: login_signup.php");
    exit();
}

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

$pricePerKm = 2; // ETB per km per seat


$bookedSeats = [];

if (isset($_POST['date'], $_POST['from'], $_POST['to']) && !isset($_POST['make_payment'])) {

    $date = $_POST['date'];
    $from = $_POST['from'];
    $to = $_POST['to'];

    $stmt = $conn->prepare("
    SELECT seat_number
    FROM booked_seats
    WHERE journey_date = ?
    AND (
        (from_city = ? AND to_city = ?)
        OR
        (from_city = ? AND to_city = ?)
    )
");

$stmt->bind_param("sssss", $date, $from, $to, $to, $from);

    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $bookedSeats[] = (string)$row['seat_number'];
        
    }
}



if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['selected_seats']) &&
    $_SESSION['payment_status'] === true
) {


    $from = $_POST['from'];
    $to = $_POST['to'];
    $date = $_POST['date'];
    $seats = $_POST['selected_seats'];
    $seatCount = empty($seats) ? 0 : count(explode(',', $seats));


    $routeKey1 = strtolower($from . '-' . $to);
$routeKey2 = strtolower($to . '-' . $from);

if (isset($routeDistances[$routeKey1])) {
    $distance = $routeDistances[$routeKey1];
} elseif (isset($routeDistances[$routeKey2])) {
    $distance = $routeDistances[$routeKey2];
} else {
    $distance = 300; // default / fallback
}


    $price = $distance * $pricePerKm * $seatCount;

    $user_id = $_SESSION['user_id'];

    if (empty($seats)) {
        echo "<script>alert('Select at least one seat');</script>";
    } else {

        $conn->begin_transaction();

try {
    // 1️⃣ Insert booking
    $stmt = $conn->prepare("
        INSERT INTO bookings
        (user_id, from_city, to_city, journey_date, seats, total_price)
        VALUES (?, ?, ?, ?, ?, ?)
    ");

    $stmt->bind_param(
        "isssid",
        $user_id,
        $from,
        $to,
        $date,
        $seats,
        $price
    );

    $stmt->execute();
    $booking_id = $conn->insert_id;

    // 2️⃣ Insert each seat (LOCKED by UNIQUE constraint)
    $seatArray = explode(',', $seats);

    $seatStmt = $conn->prepare("
        INSERT INTO booked_seats
        (booking_id, journey_date, from_city, to_city, seat_number)
        VALUES (?, ?, ?, ?, ?)
    ");

    foreach ($seatArray as $seat) {
        $seat = (int)$seat;
        $seatStmt->bind_param(
            "isssi",
            $booking_id,
            $date,
            $from,
            $to,
            $seat
        );
        $seatStmt->execute();
    }

    // 3️⃣ Commit transaction
    $conn->commit();
    unset($_SESSION['payment_status']);

    $ticket_id = $booking_id;

} catch (mysqli_sql_exception $e) {
    // 🚫 Seat already booked
    $conn->rollback();
    echo "<script>alert('❌ One or more selected seats were just booked by another passenger. Please reselect seats.');</script>";
}

    }
}
/* ================================
   HANDLE BANK TRANSFER PAYMENT
================================ */

if (
    isset($_POST['make_payment']) &&
    isset($_POST['booking_id']) &&
    $_SESSION['role'] === 'passenger'
) {

    $booking_id      = (int)$_POST['booking_id'];
    $passenger_id    = $_SESSION['user_id'];
    $amount          = (float)$_POST['amount'];
    $bank_name       = trim($_POST['bank_name']);
    $transaction_ref = trim($_POST['transaction_ref']);

    if ($amount <= 0 || empty($bank_name) || empty($transaction_ref)) {
        echo "<script>alert('❌ All payment fields are required');</script>";
    } else {

        $check = $conn->prepare(
            "SELECT id FROM payments WHERE booking_id = ? AND status = 'pending'"
        );
        $check->bind_param("i", $booking_id);
        $check->execute();

        if ($check->get_result()->num_rows > 0) {
            echo "<script>alert('⚠️ Payment already submitted for this booking');</script>";
        } else {

$stmt = $conn->prepare("
    INSERT INTO payments
    (booking_id, passenger_id, passenger_email, amount, bank_name, transaction_ref)
    VALUES (?, ?, ?, ?, ?, ?)
");

$passenger_email = $_SESSION['email'];

$stmt->bind_param(
    "iisdss",
    $booking_id,
    $passenger_id,
    $passenger_email,
    $amount,
    $bank_name,
    $transaction_ref
);


            $stmt->execute();

            echo "<script>alert('✅ Payment submitted successfully. Waiting for admin approval.');</script>";

            $_SESSION['payment_status'] = true;
        }
    }
}


?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>passenger page</title>
    <link rel="stylesheet" href="bt.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    

    <h1>Welcome <?php echo $_SESSION['name']; ?></h1>
    <p>This page is accessible only to users with the <b>'passenger'</b> role.</p>
        <div class="menu-icon" id="menu-toggle">
          <i class="fa-solid fa-bars"></i>
        </div>
        <nav class="navbar">    
       <ul id="nav-links">
        <li><a href="index.php">Home</a></li>
        <li><a href="booking.php" class="login">Book Ticket</a></li>
        <li><a href="logout.php" class="login">Logout</a></li>
       <ul>
      </nav>
    
    <main class="book-ticket-page">
    <div class="main">
        <h2>Bus Ticket Booking</h2>

    <form method="POST" id="booking-form">

        <div class="form-group active">
          
            <label for="from">From:</label>
            <select id="from" name="from" required>
                <option value="" disabled selected>Select Departure City</option>
                <option value="burie">Burie</option>
                <option value="addiss_ababa" disabled>Addis Ababa</option>
                <option value="debre_markos" disabled>Debre Markos</option>
                <option value="dangila" disabled>Dangila</option>
                <option value="finote_selam" disabled>Finote Selam</option>
                <option value="dembecha" disabled>Dembecha</option>
            </select>
        

             <div>
            <label for="to">To:</label>
            <select id="to" name="to" required>
                <option value="" disabled selected>Select Destination City</option>
                <option value="bahir_dar">Bahir Dar</option>
                <option value="addiss_ababa">Addis Ababa</option>
                <option value="debre_markos">Debre Markos</option>
                <option value="dangila">Dangila</option>
                <option value="finote_selam">Finote Selam</option>
                <option value="dembecha">Dembecha</option>
                <option value="durbete">Durbete</option>
                <option value="merawi">Merawi</option>
            </select>
            </div>
        
        <div>
        <label for="date">Journey Date:</label>
        <input type="date" id="date" name="date" required><br>
        </div>
        <div>
            
            <input type="hidden" name="selected_seats" id="selected_seats">

        </div>
        

        <?php if ($_SESSION['payment_status'] === true): ?>

<h3>Select Seats</h3>
<div class="seats" id="seats-container">
    <!-- Seats will be generated here by javascript -->
</div>

<?php else: ?>
<p style="color:red;">
    ❌ Please make payment first to select seats.
</p>
<?php endif; ?>
        <h3 id="price-preview">Total Price: 0 ETB</h3>


        <?php if ($_SESSION['payment_status'] === true): ?>
    <button type="submit">Confirm Booking</button>
<?php else: ?>
    <p style="color:red;"><b>Payment required before booking.</b></p>
<?php endif; ?>
        </div>

</form>

<?php if ($ticket_id): ?>
<div class="form-group active" id="ticket-area">

    <h2 style="text-align:center;">🚌 Bus Ticket</h2>
    <hr>

    <p><strong>Ticket ID:</strong> <?= htmlspecialchars($ticket_id) ?></p>
    <p><strong>Passenger Name:</strong> <?= htmlspecialchars($_SESSION['name']) ?></p>
    <p><strong>From:</strong> <?= htmlspecialchars($from) ?></p>
    <p><strong>To:</strong> <?= htmlspecialchars($to) ?></p>
    <p><strong>Journey Date:</strong> <?= htmlspecialchars($date) ?></p>
    <p><strong>Seats:</strong> <?= htmlspecialchars($seats) ?></p>
    <p><strong>Total Price:</strong> <?= htmlspecialchars($price) ?> ETB</p>

    <hr>
    <p style="color:green;"><b>Status:</b> Not Verified</p>

    <button onclick="printTicket()">🖨️ Print Ticket</button>

    <hr>
<h3>💳 Bank Transfer Payment</h3>

<p>
<b>Transfer To:</b><br>
Commercial Bank of Ethiopia (CBE)<br>
Account Name: <b>Bus Ticket System</b><br>
Account Number: <b>100012345678</b>
</p>

<form method="POST">
    <input type="hidden" name="booking_id" value="<?= $ticket_id ?>">

    <input type="hidden" name="make_payment" value="1">
    <input type="hidden" name="amount" value="<?= htmlspecialchars($price) ?>">

    <label>Bank Name</label>
    <input type="text" name="bank_name" required>

    <label>Transaction Reference Number</label>
    <input type="text" name="transaction_ref" required>

    <button type="submit">Submit Payment</button>
</form>

<p style="color:orange;">
⏳ Payment Status: Pending Admin Verification
</p>


</div>
<?php endif; ?>



        <p id="summary"></p>
    </div>
    </main>


    <script>
       const bookedSeats = <?= json_encode($bookedSeats); ?>;
       const routeDistances = <?= json_encode($routeDistances); ?>;
       const pricePerKm = <?= $pricePerKm; ?>;
    </script>

    <script src="Bus_Ticket.js"></script>

<script>
function printTicket() {
    const content = document.getElementById("ticket-area").innerHTML;
    const win = window.open('', '', 'width=800,height=600');

    win.document.write(`
        <html>
        <head>
            <title>Print Ticket</title>
            <style>
                body { font-family: Arial; padding: 20px; }
                h2 { text-align: center; }
                hr { margin: 10px 0; }
            </style>
        </head>
        <body>
            ${content}
        </body>
        </html>
    `);

    win.document.close();
    win.focus();
    win.print();
    win.close();
}
</script>

    
</body>
</html>