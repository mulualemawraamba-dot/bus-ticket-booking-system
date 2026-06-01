<?php
session_start();
require_once 'connection.php';

/* 🔒 Admin only access */
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login_signup.php");
    exit();
}

/* ✅ Handle approval */
if (isset($_GET['approve'])) {
    $userId = intval($_GET['approve']);

    $stmt = $conn->prepare(
        "UPDATE users SET status = 'approved' WHERE id = ? AND status = 'pending'"
    );
    $stmt->bind_param("i", $userId);
    $stmt->execute();

    header("Location: admin_approve.php");
    exit();
}

/* 🔍 Get pending drivers & officers */
$result = $conn->query(
    "SELECT id, name, email, role 
     FROM users 
     WHERE status = 'pending' 
     AND role IN ('driver', 'officer')"
);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Approve Users</title>
    <style>
        table { border-collapse: collapse; width: 80%; }
        th, td { border: 1px solid #ccc; padding: 10px; }
        th { background: #eee; }
        a.btn { padding: 5px 10px; background: green; color: white; text-decoration: none; }
    </style>
</head>
<body>

<h2>Pending Driver & Officer Approvals</h2>

<table>
    <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Role</th>
        <th>Action</th>
    </tr>

    <?php if ($result->num_rows === 0): ?>
        <tr><td colspan="4">No pending users</td></tr>
    <?php endif; ?>

    <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?= htmlspecialchars($row['name']) ?></td>
        <td><?= htmlspecialchars($row['email']) ?></td>
        <td><?= ucfirst($row['role']) ?></td>
        <td>
            <a class="btn" href="?approve=<?= $row['id'] ?>">Approve</a>
        </td>
    </tr>
    <?php endwhile; ?>

</table>

</body>
</html>
