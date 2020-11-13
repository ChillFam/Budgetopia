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
    <title>Budgetopia Home</title>
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
    <form action="/action_page.php">
        <label for="amount">Amount:</label><br>
        <input type="text" id="amount" name="amount"><br><br>
        <label for="category">Category:</label><br>
        <input type="radio" id="needs" name="category">
        <label for="needs">Needs</label><br>
        <input type="radio" id="female" name="category">
        <label for="wants">Wants</label><br>
        <input type="radio" id="savings" name="category">
        <label for="savings">Savings</label><br><br>
        <label for="category">Label your expense:</label><br>
        <input type="text" id="label" name="label"><br><br>
        <input type="submit" value="Submit">
      </form> 
      <!DOCTYPE html>
    <html lang="en">
        
    <footer class="prim-text, sec-back">
        <address> Created by the Budgeteers for CSCI 187 Fall 2020</address>
    </footer>

</body>
</html>
