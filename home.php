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
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    </head>

<body>
    <nav class="prim-text sec-back top-bottom">
        <ul>
            <li><h2>Budgetopia</h2></li>
            <li><a href="home.php">Home</a></li>
            <li><a href="savings.php">Savings</a></li>
            <li><a href="income.php">Income</a></li>
			<li><a href="expenses.php">Expenses</a></li>
			<li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
    <div class = page>
      <div class = "full">
        <div class = "head window-medium">
          Budget
        </div>
        <div class = "space window-medium">
    
    <br>
    <div class="needs lower-border">
        <p class = "sublabel3" id="nPercent">
            Needs: *needs percent*
        </p>
        <p class = "sublabel5" id="nBudgeted">
            Budgeted: *needs budgeted*
        </p>
        <p class = "sublabel5" id="nRemain">
            Remaining: *needs remaining*
        </p>
    </div>
    <div class="wants lower-border">
        <p class = "sublabel3" id="wPercent">
            Wants: *wants percent*
        </p>
        <p class = "sublabel5" id="wBudgeted">
            Budgeted: *wants budgeted*
        </p>
        <p class = "sublabel5" id="wRemain">
            Remaining: *wants remaining*
        </p>
    </div>
    <div class="savings lower-border">
        <p class = "sublabel3" id="sPercent">
            Savings: *savings percent*
        </p>
        <p class = "sublabel5" id="sBudgeted">
            Budgeted: *savings budgeted*
        </p>
    </div>
    <p>API for graph in the middle/side (formatting will change based on graph)</p>
	<div id="main-content">
        <pie-chart id="pieChart">
            <pchart-element name="Savings" value="30" colour="#00A676">
            <pchart-element name="Wants" value="20" colour="#373F51">
            <pchart-element name="Needs" value="50" colour="#008DD5">
        </pie-chart>
		<!-- <script src="pie-chart-js.js"></script> -->
    </div>
    <div>
	<input class = "sub" type="submit" value="Change Password" onclick="window.location = 'changePassword.php';" >
	</div>
    <!--
    Notes:
    make header bigger,
    make text bigger,
    change font?,
    DOM for variables,
    connect graph
    -->
  </div>
  </div>
</div>
    <footer class="prim-text, sec-back top-bottom">
        <address> Created by the Budgeteers for CSCI 187 Fall 2020</address>
    </footer>

</body>
</html>

