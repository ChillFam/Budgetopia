<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

// Include config file
require_once "config.php";

$savingsGoal = $currentSavings = $details = "";
$savingsGoal_err = $currentSavings_err = $details_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
	if(empty(trim($_POST["savingsGoal"]))){
		$savingsGoal_err = "Savings goal can not be empty";
	}
	elseif(!is_numeric(trim($_POST["savingsGoal"]))){
		$savingsGoal_err = "Only decimal values allowed";
	}
	elseif(trim($_POST["savingsGoal"]) <= 0){
		$savingsGoal_err = "Savings goal must be greater than $0";
	}
	else {
		$savingsGoal = round(trim($_POST["savingsGoal"]), 2);
	}
	
	if(empty(trim($_POST["details"]))){
		$details_err = "Details can not be empty";
	}
	else {
		$details = trim($_POST["details"]);
	}
	
	if(empty(trim($_POST["currentSavings"]))){
		$currentSavings_err = "Current savings can not be empty";
	}
	elseif(!is_numeric(trim($_POST["currentSavings"]))){
		$currentSavings_err = "Only decimal values allowed";
	}
	elseif(trim($_POST["currentSavings"]) < 0){
		$currentSavings_err = "Current savings must be greater than $0";
	}
	elseif(trim($_POST["currentSavings"]) >= trim($_POST["savingsGoal"])){
		$currentSavings_err = "Current savings can not be greater than or equal to savings goal";
	}
	else {
		$currentSavings = round(trim($_POST["currentSavings"]), 2);
	}
	
	if(empty($savingsGoal_err) && empty($currentSavings_err) && empty($details_err)){
		$sql = "SELECT * FROM savings where userID = " . $_SESSION["userID"];
		$stmt = mysqli_query($link, $sql);
		if(mysqli_num_rows($stmt) == 0){
			$sql = "INSERT INTO savings (userID, currentSavings, savingsGoal, details, dateAdded) VALUES (?, ?, ?, ?, ?)";
			if($stmt = mysqli_prepare($link, $sql)){
				mysqli_stmt_bind_param($stmt, "iiiss", $_SESSION["userID"], $currentSavings, $savingsGoal, $details, date("Y-m-d"));
				
				if(mysqli_stmt_execute($stmt)){
					header("location: savings.php");
				} 

				// Close statement
				mysqli_stmt_close($stmt);
			}
		}
		else {
			$sql = "UPDATE savings SET currentSavings = ?, savingsGoal = ?, details = ?, dateAdded = ? WHERE userID = " . $_SESSION["userID"];
			if($stmt = mysqli_prepare($link, $sql)){
				mysqli_stmt_bind_param($stmt, "iiss", $currentSavings, $savingsGoal, $details, date("Y-m-d"));
				if(mysqli_stmt_execute($stmt)){
					header("location: savings.php");
				} 
				
				// Close statement
				mysqli_stmt_close($stmt);
			}
		}
	}
	
	// Close connection
    mysqli_close($link);
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
            <li>Budgetopia</li>
            <li><a href="home.php">Home</a></li>
            <li><a href="savings.php">Savings</a></li>
            <li><a href="edit.php">Edit</a></li>
			<li><a href="expenses.php">Expenses</a></li>
			<li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
	<div>
		<?php
			$sql = "SELECT savingsGoal, details, currentSavings, dateAdded FROM savings WHERE userID = " . $_SESSION["userID"];
			$stmt = mysqli_query($link, $sql);
			if (mysqli_num_rows($stmt) > 0) {
				$row = mysqli_fetch_assoc($stmt);
				$savingsGoal = $row["savingsGoal"];
				$details =  $row["details"];
				$currentSavings =  $row["currentSavings"];
				$dateAdded = $row["dateAdded"];
				$leftToSave = number_format($savingsGoal - $currentSavings, 2);
				echo <<<GFG
					<p>
						Savings Goal: $$savingsGoal for $details
					</p>
					<p>
						Current Savings: $$currentSavings
					</p>
					<p>
						Left to Save: $$leftToSave
					</p>
					<p>
						Savings Goal Met in: *(savings goal - savings) / savings/month* months
					</p>
					<p>
						*API for line graph showing progress*
					</p>
				GFG;
			}
			else {
				echo '<p> No savings data found </p>';
			}
		?>
	</div>
	
    <p><b>New savings goal (this will replace your previous goal)</b></p>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
		<label for="details">What are you saving for?</label><br>
        <input type="text" id="details" name="details" required> <br><br>

<<<<<<< Updated upstream
        <label for="savingsGoal">Savings Goal ($):</label><br>
        <input type="number" id="savingsGoal" name="savingsGoal" min="1" required><br><br>
		<?php 
			if(!empty($savingsGoal_err)) {
				echo <<<GFG
					<p><b>Error: $savingsGoal_err</b></p>
				GFG;
			}
		?>
        
        <label for="currentSavings">Current Savings ($):</label><br>
        <input type="number" id="currentSavings" name="currentSavings" min= "0" required><br>
		<?php 
			if(!empty($currentSavings_err)) {
				echo <<<GFG
					<p><b>Error: $currentSavings_err</b></p>
				GFG;
			}
		?>
=======
        <label for="saving">Amount:</label><br>
        <input type="number" id="saving" name="saving" min="-1" required><br><br>
        
        <label for="sLabel">What are you saving for?</label><br>
        <input type="text" id="sLabel" name="sLabel" required> <br><br>
        
        <label for="Npercent">How much do you have saved now? (outside what is tracked on this site)</label><br>
        <input type="number" id="Npercent" name="Npercent" min= "-1" required><br><br>
>>>>>>> Stashed changes
        
        <input type="submit" value="Submit">
    </form> 
   
 
    <footer class="prim-text, sec-back">
        <address> Created by the Budgeteers for CSCI 187 Fall 2020</address>
    </footer>

</body>
</html>
