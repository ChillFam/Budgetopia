<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

// Prepare a select statement
    $sql = "SELECT * FROM expenses WHERE userID = ?";
	if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "i", $_SESSION["userID"]);
		mysqli_stmt_store_result($stmt);
		
		/*
		function display_data($data) {
			$output = '<table>';
			foreach($data as $key => $var) {
				$output .= '<tr>';
				foreach($var as $k => $v) {
					if ($key === 0) {
						$output .= '<td><strong>' . $k . '</strong></td>';
					} else {
						$output .= '<td>' . $v . '</td>';
					}
				}
				$output .= '</tr>';
			}
			$output .= '</table>';
			echo $output;
		}
		*/
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
			<li><a href="expenses.php">Expenses</a></li>
			<li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
	
	<h1><b>Show users expenses</b></h1>
	<div class="form-group">
		<button type="button" onclick="window.location='addExpense.php';">Add Expense</button>
		<button type="button" onclick="alert('Hello world!')">Delete Expense</button>
	</div>
 
    <footer class="prim-text, sec-back">
        <address> Created by the Budgeteers for CSCI 187 Fall 2020</address>
    </footer>

</body>
</html>
 