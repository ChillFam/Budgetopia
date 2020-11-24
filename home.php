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
            <li><b>Budgetopia</b></li>
            <li><a href="home.php">Home</a></li>
            <li><a href="savings.php">Savings</a></li>
            <li><a href="edit.php">Edit</a></li>
			<li><a href="expenses.php">Expenses</a></li>
			<li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
    <div id="main-content">
        <pie-chart id="pieChart">
            <pchart-element name="Savings" value="30" colour="#00A676">
            <pchart-element name="Wants" value="20" colour="#373F51">
            <pchart-element name="Needs" value="50" colour="#008DD5">
        </pie-chart>
    </div>
    <script src="pie-chart-js.js"></script>
    <div class="needs">
        <p id="nPercent">
            Needs: *needs percent*
        </p>
        <p id="nBudgeted">
            Budgeted: *needs budgeted*
        </p>
        <p id="nRemain">
            Remaining: *needs remaining*
        </p>
        <p> ----------- </p>
    </div>
    <div class="wants">
        <p id="wPercent">
            Wants: *wants percent*
        </p>
        <p id="wBudgeted">
            Budgeted: *wants budgeted*
        </p>
        <p id="wRemain">
            Remaining: *wants remaining*
        </p>
        <p> ----------- </p>
    </div>
    <div class="savings">
        <p id="sPercent">
            Savings: *savings percent*
        </p>
        <p id="sBudgeted">
            Budgeted: *savings budgeted*
        </p>
        <p> ----------- </p>
    </div>
    <p>API for graph in the middle/side (formatting will change based on graph)</p>
    <button type="button" onclick="alert('Hello world!')">Input more </button>
    <!-- 
    Notes:
    make header bigger,
    make text bigger, 
    change font?, 
    DOM for variables, 
    connect graph
    -->
    <footer class="prim-text, sec-back">
        <address> Created by the Budgeteers for CSCI 187 Fall 2020</address>
    </footer>

</body>
</html>
