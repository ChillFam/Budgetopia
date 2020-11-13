<?php
// Initialize the session
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<!-- These are comments -->

<head>
    <title>Budgetopia Home</title>
    <link rel="stylesheet" type="text/css" href="budgetopiaStyles.css">
</head>

<body>
    <nav class="prim-text sec-back">
        <ul>
            <li><a href="index.php">Budgetopia</a></li>
			<?php if(isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] == true) : ?>
			<li><a href="home.php">Home</a></li>
            <li><a href="savings.php">Savings</a></li>
            <li><a href="edit.php">Edit</a></li>
			<li><a href="logout.php">Logout</a></li>
			<?php else : ?>
			<li><a href="login.php">Login</a></li>
			<li><a href="register.php">Register</a></li>
			<?php endif; ?>
			
			
			
        </ul>
    </nav>
	
	<h1><b>Default home page for non logged in users</b></h1>
 
    <footer class="prim-text, sec-back">
        <address> Created by the Budgeteers for CSCI 187 Fall 2020</address>
    </footer>

</body>
</html>
 