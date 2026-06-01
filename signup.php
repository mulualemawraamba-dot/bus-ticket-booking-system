<?php
session_start();
include "connection.php";

if (isset($_POST['submit'])) {

    $fname  = mysqli_real_escape_string($conn, $_POST['first_name']);
    $mname  = mysqli_real_escape_string($conn, $_POST['middle_name']);
    $lname  = mysqli_real_escape_string($conn, $_POST['last_name']);
    $gender = $_POST['gender'];
    $email  = mysqli_real_escape_string($conn, $_POST['email']);
    $idno   = mysqli_real_escape_string($conn, $_POST['ID_no']);
    $role   = $_POST['role'];

    // 🔐 HASH PASSWORD (VERY IMPORTANT)
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // ❌ Check if email already exists
    $check = mysqli_query($conn, "SELECT id FROM users WHERE email='$email'");
    if (mysqli_num_rows($check) > 0) {
        die("Email already exists!");
    }

    $sql = "INSERT INTO users 
        (first_name, middle_name, last_name, gender, email, password, id_no, role)
        VALUES 
        ('$fname','$mname','$lname','$gender','$email','$password','$idno','$role')";

    if (mysqli_query($conn, $sql)) {
        header("Location: login.php");
        exit();
    } else {
        echo "Signup failed!";
    }
}
?>
