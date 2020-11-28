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
    <div class = "page">
      <div class = "full">
        <div class = "head window-medium">
          Income
        </div>
        <div class = "space window-medium">


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


<br>
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
		<div class = "lower-border">
				<?php 
					echo <<<GFG
						<label class = "sublabel3">Income each pay period:</label><br>
						<label class = "sublabel5">$</label>
						<input type="number" id="income" name="income" min="0" value=$currentIncome required><br>
					GFG;
					
					if(!empty($income_err)) {
						echo <<<GFG
							<p><b>Error: $income_err</b></p>
						GFG;
					}
				?>
				
				<label class = "sublabel3" for="frequency">Frequency of pay period:</label><br>
				<?php
					if($currentFrequency == "monthly"){
						echo "<input type='radio' id='monthly' name='frequency' value='monthly' required checked>";
					}
					else{
						echo "<input type='radio' id='monthly' name='frequency' value='monthly' required>";
					}
				?>
				<label class = "sublabel5" for="monthly">Monthly</label><br>
				<?php
					if($currentFrequency == "biweekly"){
						echo "<input type='radio' id='biweekly' name='frequency' value='biweekly' checked>";
					}
					else{
						echo "<input type='radio' id='biweekly' name='frequency' value='biweekly'>";
					}
				?>
				<label class = "sublabel5" for="biweekly">Biweekly (every 2 weeks)</label><br>
				<?php
					if($currentFrequency == "weekly"){
						echo "<input type='radio' id='weekly' name='frequency' value='weekly' checked>";
					}
					else{
						echo "<input type='radio' id='weekly' name='frequency' value='weekly'>";
					}
				?>
				<label class = "sublabel5" for="weekly">Weekly</label><br>
				<?php 
					if(!empty($frequency_err)) {
						echo <<<GFG
							<p><b>Error: $frequency_err</b></p>
						GFG;
					}
				?>
		</div>

		<div class = "lower-border">

					<p class = "sublabel3">Income Distribution:</p>
          <p class = "sublabel4">(these percents should add up to 100)</p>
		  
		  
		  
		  <?php
				echo <<<GFG
					<label class = "sublabel2" for="Npercent">What percent of your income do you spend on necessities each month?</label><br>
					<div>
					<input type="number" id="Npercent" name="Npercent" min= "0" max="100" step="1" value=$currentNpercent required>
					<label class = "sublabel2">%</label<br><br>
					</div>
					
					<label class = "sublabel2" for="Wpercent">What percent of your income do you spend on wants each month?</label><br>
					<div>
					<input type="number" id="Wpercent" name="Wpercent" min= "0" max="100" step="1" value=$currentWpercent required>
					<label class = "sublabel2">%</label<br><br>
					</div>
					
					<label class = "sublabel2" for="Spercent">What percent of your income do you want to save each month?</label><br>
					<div>
					<input type="number" id="Spercent" name="Spercent" min= "0" max="100" step="1" value=$currentSpercent required>
					<label class = "sublabel2">%</label<br><br>
					</div>
				GFG;
				if(!empty($percent_err)) {
					echo <<<GFG
						<p><b>Error: $percent_err</b></p>
					GFG;
				}
			?>

		</div>


		<input class = "sub3" type="submit" value="Submit">
  </div>
	</form>

</div>
</div>
    <footer class="prim-text, sec-back top-bottom">
        <address> Created by the Budgeteers for CSCI 187 Fall 2020</address>
    </footer>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" int></script>

</body>
</html>

