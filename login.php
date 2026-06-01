<?php

session_start();
require_once 'connection.php';

   // signup
// signup
if (isset($_POST['signup'])) {

    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];
    $role     = strtolower($_POST['role']); //  take role from form

/*  ADMIN SIGNUP PROTECTION (SKIP FOR FIRST ADMIN) */
if ($role === 'admin') {

    $checkAdmin = $conn->query(
        "SELECT id FROM users WHERE role = 'admin' LIMIT 1"
    );

    // If admin already exists → require secret
    if ($checkAdmin->num_rows > 0) {

        if (empty($_POST['admin_secret'])) {
            $_SESSION['signup_error'] = " Admin secret password is required.";
            $_SESSION['active_form']  = 'signup';
            header("Location: login_signup.php");
            exit();
        }

        if ($_POST['admin_secret'] !== ADMIN_SECRET) {
            $_SESSION['signup_error'] = " Incorrect admin secret password.";
            $_SESSION['active_form']  = 'signup';
            header("Location: login_signup.php");
            exit();
        }
    }
}


    //  Hash password AFTER validation
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    //  Prevent duplicate emails (SECURE)
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $_SESSION['signup_error'] = "Email already exists!";
        $_SESSION['active_form']  = 'signup';
        header("Location: login_signup.php");
        exit();
    }


    //  Set approval status
if ($role === 'passenger' || $role === 'admin') {
    $status = 'approved';
} else {
    $status = 'pending'; // driver & officer wait for admin
}




    //  Insert user
$insert = $conn->prepare(
    "INSERT INTO users (name, email, password, role, status)
     VALUES (?, ?, ?, ?, ?)"
);
$insert->bind_param("sssss", $name, $email, $hashedPassword, $role, $status);

    $insert->execute();

    $_SESSION['login_error'] = "Account created! Please login.";
    $_SESSION['active_form'] = 'login';
    header("Location: login_signup.php");
    exit();
}


   //login
if (isset($_POST['login'])){
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    
    if (password_verify($password, $user['password'])) {

    if ($user['status'] !== 'approved') {
    $_SESSION['login_error'] = " Your account is waiting for admin approval.";
    $_SESSION['active_form'] = 'login';
    header("Location: login_signup.php");
    exit();
}

        $_SESSION['user_id'] = $user['id']; 
        $_SESSION['name'] = $user['name'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'];

        switch ($user['role']) {
                case 'admin':     header("Location: admin_page.php"); break;
                case 'driver':    header("Location: driver_page.php"); break;
                case 'passenger': header("Location: passenger_page.php"); break;
                case 'officer':   header("Location: officer_page.php"); break;
            }
            exit();
        }
    } 
    $_SESSION['login_error'] = "Invalid email or password!";
    $_SESSION['active_form'] = 'login';
    header("Location: login_signup.php");
    exit();
}

?>


    