<?php
require_once __DIR__ . "/bootstrap.php";
?>
<!DOCTYPE html>
<html>
<head>
    <title>Student Recruiter | Company</title>
    <link rel="stylesheet" href="public/css/semantic.min.css">
</head>
<body>
<div class="ui container">
    <h2>Registration</h2>
    <?php include 'alert.php'; ?>
    <form class="ui form" method="post" action="procedures/doRegister.php">
        <div class="field">
            <label>First Name</label>
            <input type="text" name="firstName" placeholder="First Name">
        </div>
        <div class="field">
            <label>Last Name</label>
            <input type="text" name="lastName" placeholder="Last Name">
        </div>
        <div class="field">
            <label>Email</label>
            <input type="email" name="email" placeholder="Email">
        </div>
        <div class="field">
            <label>Password</label>
            <input type="password" name="password" placeholder="Password">
        </div>
        <div class="field">
            <label>Confirm Password</label>
            <input type="password" name="confirm_password" placeholder="Confirm Password">
        </div>
        <button class="ui button" type="submit">Register</button>
    </form>
</div>

<?php include 'footer.php'; ?>