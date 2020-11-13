<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<!-- These are comments -->

<head>
    <title>Budgetopia Savings</title>
    <link rel="stylesheet" type="text/css" href="budgetopiaStyles.css">
</head>

<body>
    <nav class="prim-text sec-back">
        <ul> 
            <li><a href="index.php">Budgetopia</a></li>
            <li><a href="home.php">Home</a></li>
            <li><a href="savings.php">Savings</a></li>
            <li><a href="edit.php">Edit</a></li>
			<li><a href="addExpense.php">Add Expense</a></li>
			<li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
    <div>
        <p>
            Savings Goal: *savings goal* for *savings purpose*
        </p>
        <p>
            Savings: *savings*
        </p>
        <p>
            Left to Save: *savings goal - savings*
        </p>
        <p>
            Savings Goal Met in: *(savings goal - savings) / savings/month* months
        </p>
        <p>
            *API for line graph showing progress*
        </p>
    </div>
   
 
    <footer class="prim-text, sec-back">
        <address> Created by the Budgeteers for CSCI 187 Fall 2020</address>
    </footer>

</body>
</html>
