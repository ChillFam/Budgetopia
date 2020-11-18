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
            <li>Budgetopia</li>
            <li><a href="home.php">Home</a></li>
            <li><a href="savings.php">Savings</a></li>
            <li><a href="edit.php">Edit</a></li>
			<li><a href="expenses.php">Expenses</a></li>
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
        <p>
            Change your information here: (it would be really cool to be able to have these preset as the current info and they just change anything that is wrong, but I don't know how to do that rn)
        </p>
        <form action=>
	
            <label for="income">Income each pay period:</label><br>
            <input type="number" id="income" name="income" min="0"><br><br>
            
            <label for="frequency">Frequency of pay period:</label><br>
            <input type="radio" id="monthly" name="frequency">
            <label for="monthly">Monthly</label><br>
            <input type="radio" id="biweekly" name="frequency">
            <label for="biweekly">Biweekly (every 2 weeks)</label><br>
            <input type="radio" id="weekly" name="frequency">
            <label for="weekly">Weekly</label><br><br>
            
            <label for="percents">These percents should add up to 100:</label><br><br>
            <label for="Npercent">What percent of your income do you spend on necessities each month?</label><br>
            <input type="number" id="Npercent" name="Npercent" min= "0" max="100" required><br>
            <label for="Wpercent">What percent of your income do you spend on wants each month?</label><br>
            <input type="number" id="Wpercent" name="Wpercent" min= "0" max="100" required><br>
            <label for="Spercent">What percent of your income do you want to save each month?</label><br>
            <input type="number" id="Spercent" name="Spercent" min= "0" max="100" required><br><br>
            
            
            <input type="submit" value="Submit">
        </form> <br><br>
        <p>New savings goal(this will replace your previous goal)</p><br>
        <form action=>
	
            <label for="saving">Amount:</label><br>
            <input type="number" id="saving" name="saving" min="0" required><br><br>
            
            <label for="sLabel">What are you saving for?</label><br>
            <input type="text" id="sLabel" name="sLabel" required> <br><br>
            
            <label for="Npercent">How much do you have saved now? (outside what is tracked on this site)</label><br>
            <input type="number" id="Npercent" name="Npercent" min= "0" required><br><br>
            
            <input type="submit" value="Submit">
        </form> 
          
    </div>
    
 
    <footer class="prim-text, sec-back">
        <address> Created by the Budgeteers for CSCI 187 Fall 2020</address>
    </footer>

</body>
</html>

