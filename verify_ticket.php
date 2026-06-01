<?php
session_start();
require_once 'connection.php';

// Only officer can access
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'officer') {
    header("Location: login_signup.php");
    exit();
}

$result = null;
$message = "";

/* =======================
   SEARCH TICKET
======================= */
if (isset($_POST['search'])) {
    $ticket_id = $_POST['ticket_id'];

    $stmt = $conn->prepare("
        SELECT b.*, u.name, u.email 
        FROM bookings b
        JOIN users u ON b.user_id = u.id
        WHERE b.id = ?
    ");
    $stmt->bind_param("i", $ticket_id);
    $stmt->execute();
    $result = $stmt->get_result();
}

/* =======================
   VERIFY TICKET
======================= */
if (isset($_POST['verify'])) {
    $ticket_id = $_POST['ticket_id'];
    $officer_name = $_SESSION['name'];

    // Check if ticket exists and is not verified yet
    $checkStmt = $conn->prepare("SELECT verified FROM bookings WHERE id = ?");
    $checkStmt->bind_param("i", $ticket_id);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows === 0) {
        $message = "❌ Ticket not found!";
    } else {
        $ticket = $checkResult->fetch_assoc();
        if ($ticket['verified']) {
            $message = "⚠️ Ticket already verified!";
        } else {
            // Update verification
            $stmt = $conn->prepare("
                UPDATE bookings 
                SET verified = 1, verified_by = ?, verified_at = NOW()
                WHERE id = ? AND verified = 0
            ");
            $stmt->bind_param("si", $officer_name, $ticket_id);

            if ($stmt->execute()) {
                $message = "✅ Ticket Verified Successfully!";
            } else {
                $message = "❌ Verification Failed!";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Verify Ticket</title>
    <link rel="stylesheet" href="bt.css">
</head>
<body>

<main>
    <div class="main">
        <h1>Ticket Verification</h1>

        <!-- SEARCH FORM -->
        <form method="POST" id="booking-form">
            <div class="form-group active">
                <label>Enter Ticket ID</label>
                <input type="number" name="ticket_id" required>
                <button type="submit" name="search">Search</button>
            </div>
        </form>

        <p style="color:green"><?= htmlspecialchars($message) ?></p>

        <!-- DISPLAY TICKET DETAILS -->
        <?php if ($result && $result->num_rows > 0): 
            $row = $result->fetch_assoc(); ?>
            <table border="1" width="100%">
                <tr><th>Passenger</th><td><?= htmlspecialchars($row['name']) ?></td></tr>
                <tr><th>Email</th><td><?= htmlspecialchars($row['email']) ?></td></tr>
                <tr><th>Route</th><td><?= htmlspecialchars($row['from_city']) ?> → <?= htmlspecialchars($row['to_city']) ?></td></tr>
                <tr><th>Date</th><td><?= htmlspecialchars($row['journey_date']) ?></td></tr>
                <tr><th>Seats</th><td><?= htmlspecialchars($row['seats']) ?></td></tr>
                <tr><th>Price</th><td><?= htmlspecialchars($row['total_price']) ?> ETB</td></tr>
                <tr>
                    <th>Status</th>
                    <td><?= $row['verified'] ? "Verified" : "Not Verified" ?></td>
                </tr>

                <?php if ($row['verified']): ?>
                    <tr>
                        <th>Verified By</th>
                        <td><?= htmlspecialchars($row['verified_by']) ?></td>
                    </tr>
                    <tr>
                        <th>Verified At</th>
                        <td><?= htmlspecialchars($row['verified_at']) ?></td>
                    </tr>
                <?php endif; ?>
            </table>

<!-- VERIFY BUTTON -->
            <?php if (!$row['verified']): ?>
                <form method="POST">
                    <input type="hidden" name="ticket_id" value="<?= htmlspecialchars($row['id']) ?>">
                    <button name="verify">Verify Ticket</button>
                </form>
            <?php endif; ?>

        <?php elseif ($result): ?>
            <p class="ticket-invalid">Ticket not found!</p>
        <?php endif; ?>

    </div>
</main>

</body>
</html>