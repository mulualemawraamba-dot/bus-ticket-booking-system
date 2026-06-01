<?php
session_start();
require_once 'connection.php';

$adminExists = false;

$checkAdmin = $conn->query("SELECT id FROM users WHERE role = 'admin' LIMIT 1");
if ($checkAdmin && $checkAdmin->num_rows > 0) {
    $adminExists = true;
}

$errors = [
    'login' => $_SESSION['login_error'] ?? '',
    'signup'  => $_SESSION['signup_error'] ?? ''
];
$activeForm = $_SESSION['active_form'] ?? 'login';

//session_unset();//to remove all session variables

function showError($error) {
    return !empty($error) ? "<p class='error-message'>$error</p>" : '';
}//function to show error messages in a form

function isActiveForm($formName, $activeForm) {
    return $formName === $activeForm ? 'active' : '';
}//
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
    

<main>

        
   <div class="form-group <?= isActiveForm('login', $activeForm); ?>" id="login-form">
    
    <form name="form" action="login.php" method="post">
        <h1>Login form</h1>
        <?= showError($errors['login']); ?>
    
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" required><br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br>
        
        
        <div class="form-buttons">
            <input type="submit" name="login" value="Login">
            <p>Don't have an account? <a href="#" onclick="showForm('signup-form')">Signup</a></p>
            
        </div>
    </form>
   </div>


   
   

   <div class="form-group <?= isActiveForm('signup', $activeForm); ?>" id="signup-form">
    
    <form name="form" action="login.php" method="post">
        <h1>Signup form</h1>
        <?= showError($errors['signup']); ?>

            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required pattern="[A-Za-z\s]{2,}" title="Please enter at least 2 letters (A-Z) and spaces only." placeholder="Enter your name"><br>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" required><br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br>
            
            Role:
            <select name="role" id="role" required>
                <option value="" disabled selected>Select Role</option>
                <option value="driver">Driver</option>
                <option value="passenger">passenger</option>
                <option value="officer">Officer</option>
                
                <?php if (!$adminExists): ?>
                    <option value="admin">Admin</option>
                <?php endif; ?>

            </select>
            <div id="admin-secret-box" style="display:none;">
                <label for="admin_secret">Admin Secret:</label>
                <input type="password" id="admin_secret" name="admin_secret" placeholder="Enter admin secret">
            </div>

        <div class="form-buttons">
            <input type="submit" name="signup" value="signup">
            <p>Already have an account? <a href="#" onclick="showForm('login-form')">Login</a></p>
        </div>

    </form>
    </div>

    </div> 
</main>

    

    <script src="Bus_Ticket.js"></script>

    <?php
// clear flash messages AFTER displaying
unset($_SESSION['login_error']);
unset($_SESSION['signup_error']);
unset($_SESSION['active_form']);
?>
      
</body>
</html>