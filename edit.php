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

$income = $frequency = $Npercent = $Wpercent = $Spercent = "";
$income_err = $frequency_err = $percent_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
	
	// Check income
	if(empty(trim($_POST["income"]))){
		$income_err = "Income Empty";
	}
	elseif(!is_numeric(trim($_POST["income"]))){
		$income_err = "Only decimal values allowed";
	}
	elseif(trim($_POST["income"]) <= 0){
		$income_err = "Income must be greater than $0";
	}
	else{
		$income = round(trim($_POST["income"]), 2);
	}
	
	// Check Frequency
	if(empty(trim($_POST["frequency"]))){
		$frequency_err = "Empty";
	}
	else{
		$frequency = trim($_POST["frequency"]);
	}
	
	// Check Percents
	/* System thinks 0 is empty for some reason??
	if(empty(trim($_POST["Npercent"]))){
		$percent_err = "Necessities Empty";
	}
	elseif(empty(trim($_POST["Wpercent"]))){
		$percent_err = "Wants Empty";
	}
	elseif(empty(trim($_POST["Spercent"]))){
		$percent_err = "Savings Empty";
	}
	*/
	if(!is_numeric(trim($_POST["Npercent"])) || !is_numeric(trim($_POST["Wpercent"])) || !is_numeric(trim($_POST["Spercent"]))){
		$percent_err = "Only decimal values allowed";
	}
	elseif(trim($_POST["Npercent"]) < 0 || trim($_POST["Wpercent"]) < 0 || trim($_POST["Spercent"]) < 0){
		$percent_err = "Must be greater or equal to 0";
	}
	elseif(trim($_POST["Npercent"]) + trim($_POST["Wpercent"]) + trim($_POST["Spercent"]) != 100){
		$percent_err = "Values must add up to 100%";
	}
	else{
		$Npercent = trim($_POST["Npercent"]);
		$Wpercent = trim($_POST["Wpercent"]);
		$Spercent = trim($_POST["Spercent"]);
	}
	
	if(empty($income_err) && empty($frequency_err) && empty($percent_err)){
		
		// Insert for the first time
		$sql = "SELECT * FROM income where userID = " . $_SESSION["userID"];
		$stmt = mysqli_query($link, $sql);
		
		if(mysqli_num_rows($stmt) == 0){
			$sql = "INSERT INTO income (userID, amount, frequency, Npercent, Wpercent, Spercent) VALUES (?, ?, ?, ?, ?, ?)";
			if($stmt = mysqli_prepare($link, $sql)){
				// Bind variables to the prepared statement as parameters
				mysqli_stmt_bind_param($stmt, "iisiii", $_SESSION["userID"], $income, $frequency, $Npercent, $Wpercent, $Spercent);
				
				// Attempt to execute the prepared statement
				if(mysqli_stmt_execute($stmt)){
					echo '<script> alert("Income successfully added") </script>';
					header("location: edit.php");
				} 
				else{
					echo "SQL Error: ". mysqli_error($link);
				}

				// Close statement
				mysqli_stmt_close($stmt);
			}
		}
		// Update existing income
		else{
			$sql = "UPDATE income SET amount = ?, frequency = ?, Npercent = ?, Wpercent = ?, Spercent = ?";
			if($stmt = mysqli_prepare($link, $sql)){
				// Bind variables to the prepared statement as parameters
				mysqli_stmt_bind_param($stmt, "isiii", $income, $frequency, $Npercent, $Wpercent, $Spercent);
				
				// Attempt to execute the prepared statement
				if(mysqli_stmt_execute($stmt)){
					echo '<script> alert("Income successfully updated") </script>';
					header("location: edit.php");
				} 
				else{
					echo "SQL Error: ". mysqli_error($link);
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
    <title>Budgetopia Edit</title>
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
	
	<!--
		<div>
			<p> 
			Username: <?php echo htmlspecialchars($_SESSION["username"]); ?>
			</p>
			<p>
				Income: *income*
			</p>
			<p>
				Percentages:
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
		</div>
	-->
	<?php
		$sql = "SELECT amount, frequency, Npercent, Wpercent, Spercent FROM income WHERE userID = " . $_SESSION["userID"];
		$stmt = mysqli_query($link, $sql);
		
		if(mysqli_num_rows($stmt) > 0){
			$row = mysqli_fetch_assoc($stmt);
			$currentIncome = $row["amount"];
			$currentFrequency = $row["frequency"];
			$currentNpercent = $row["Npercent"];
			$currentWpercent = $row["Wpercent"];
			$currentSpercent = $row["Spercent"];
		}
	?>
	
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
		<div>
			<?php 
				echo <<<GFG
					<label for="income">Income each pay period:</label><br>
					<label>$</label>
					<input type="number" id="income" name="income" min="0" value=$currentIncome required><br>
				GFG;
				
				if(!empty($income_err)) {
					echo <<<GFG
						<p><b>Error: $income_err</b></p>
					GFG;
				}
			?>
		</div><br>
		
		<div>
				<label for="frequency">Frequency of pay period:</label><br>
				<?php
					if($currentFrequency == "monthly"){
						echo "<input type='radio' id='monthly' name='frequency' value='monthly' required checked>";
					}
					else{
						echo "<input type='radio' id='monthly' name='frequency' value='monthly' required>";
					}
				?>
				<label for="monthly">Monthly</label><br>
				<?php
					if($currentFrequency == "biweekly"){
						echo "<input type='radio' id='biweekly' name='frequency' value='biweekly' checked>";
					}
					else{
						echo "<input type='radio' id='biweekly' name='frequency' value='biweekly'>";
					}
				?>
				<label for="biweekly">Biweekly (every 2 weeks)</label><br>
				<?php
					if($currentFrequency == "weekly"){
						echo "<input type='radio' id='weekly' name='frequency' value='weekly' checked>";
					}
					else{
						echo "<input type='radio' id='weekly' name='frequency' value='weekly'>";
					}
				?>
				<label for="weekly">Weekly</label><br>
			<?php 
					if(!empty($frequency_err)) {
						echo <<<GFG
							<p><b>Error: $frequency_err</b></p>
						GFG;
					}
			?>
		</div><br>
		
		<div>
			<?php
				echo <<<GFG
					<label for="percents">These percents should add up to 100:</label><br><br>
					<label for="Npercent">What percent of your income do you spend on necessities each month?</label><br>
					<input type="number" id="Npercent" name="Npercent" min= "0" max="100" step="1" value=$currentNpercent required>
					<label>%</label<br><br>
					<label for="Wpercent">What percent of your income do you spend on wants each month?</label><br>
					<input type="number" id="Wpercent" name="Wpercent" min= "0" max="100" step="1" value=$currentWpercent required>
					<label>%</label<br><br>
					<label for="Spercent">What percent of your income do you want to save each month?</label><br>
					<input type="number" id="Spercent" name="Spercent" min= "0" max="100" step="1" value=$currentSpercent required>
					<label>%</label<br><br>
				GFG;
				if(!empty($percent_err)) {
					echo <<<GFG
						<p><b>Error: $percent_err</b></p>
					GFG;
				}
			?>
		</div><br>
		
		
		<input type="submit" value="Submit">
	</form>

    
 
    <footer class="prim-text, sec-back">
        <address> Created by the Budgeteers for CSCI 187 Fall 2020</address>
    </footer>

</body>
</html>

