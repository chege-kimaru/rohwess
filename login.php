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
    <h2>Please sign in</h2>
    <?php include 'alert.php';?>
    <form class="ui form" method="post" action="procedures/doLogin.php">
        <div class="field">
            <label>Email</label>
            <input type="text" name="email" placeholder="Email">
        </div>
        <div class="field">
            <label>Password</label>
            <input type="password" name="password" placeholder="Password">
        </div>
        <button class="ui button" type="submit">Sign in</button>
    </form>
</div>

<?php include 'footer.php'; ?>