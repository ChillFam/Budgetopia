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
    <title>Budgetopia Edit</title>
    <link rel="stylesheet" type="text/css" href="budgetopiaStyles.css">
</head>

<body>
    <nav class="prim-text sec-back">
        <ul>
            <li><a href="index.php">Budgetopia</a></li>
            <li><a href="home.php">Home</a></li>
            <li><a href="savings.php">Savings</a></li>
            <li><a href="edit.php">Edit</a></li>
            <li><a href="addExpense.html">Add Expense</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
    <div>
        <p> 
        Username: <?php echo htmlspecialchars($_SESSION["username"]); ?>
        </p>
        <p>
            Income: *income*
        </p>
        <p>
            Percentages: <!--not sure why, but the lists wont work to show the percentages-->
        </p>
        <p>
            -  Needs: *needs percent*
        </p>
        <p>
            -  Wants: *wants percent*
        </p>
        <p>
            -  Savings: *savings percent*
        </p>
          
    </div>
    
 
    <footer class="prim-text, sec-back">
        <address> Created by the Budgeteers for CSCI 187 Fall 2020</address>
    </footer>

</body>
</html>
