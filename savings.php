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
		Savings Goal
      </div>
      <div class = "space window-medium">
	  
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
				$timeLeft = number_format($leftToSave / $budget); //unsure if there is a budget value in this table
				//if current > goal congrats
				echo <<<GFG
					<div class = "lower-border">
						<br>
						<div class = "lower-border">
							<p class = "sublabel3">
								Current Goal:
							</p>
							<p class = "sublabel5">
								$$savingsGoal for a(n) $details
							</p>
						</div>
						
						<div class = "lower-border">
							<p class = "sublabel3">
								Current Savings:
							</p>
					    	<p class = "sublabel5">
								$$currentSavings
					    	</p>
						</div>
						
						<div class = "lower-border">
							<p class = "sublabel3">
								Left to Save:
							</p>
							<p class = "sublabel5">
								$$leftToSave
							</p>
						</div>
						
						<div>
							<p class = "sublabel3">
								Savings Goal Met in:
							</p>
							<p class = "sublabel5">
								$timeLeft months
							</p>
						
						
							<script>
							  var x = 100;
							  var y = 100;
							  var width = 300;
							  var height = 50;
							  var goal = $savingsGoal;      //total amount of money their trying to save
							  var progress = $currentSavings;     //amount of money currently saved

							  var canvas = document.createElement('canvas'); //Create a canvas element


							  //Set canvas width/height
							  canvas.style.width='100%';
							  canvas.style.height='100%';
							  //Set canvas drawing area width/height
							  canvas.width = window.innerWidth;
							  canvas.height = window.innerHeight;
							  //Position canvas
							  canvas.style.position='absolute';
							  //canvas.style.left=0;
							  //canvas.style.top=0;
							  canvas.style.zIndex=10;
							  canvas.style.pointerEvents='none'; //Make sure you can click 'through' the canvas
							  document.body.appendChild(canvas); //Append canvas to body element

							  document.open();
							  document.write("Savings Goal Progress: " + progress + "/" + goal); //prints header of the graph
							  document.close();


							  var context = canvas.getContext('2d');
							  context.fillStyle = 'Silver';
							  context.fillRect(x, y, width, height);  //draws base rectangle

							  var context1 = canvas.getContext('2d');
							  var currentGoal = progress/goal;
							  context1.fillStyle = 'LawnGreen';
							  context1.fillRect(x, y, width*currentGoal, height); //fills in rectangle to depict progress

							</script>
						</div>

					</div>
				GFG;

			}
			else {
				echo '<div class = "lower-border">';
				echo '<h3> No savings data found </h3>';
				echo '</div>';
			}
		?>
	</div>
    

    <p class = "sublabel3">New Savings Goal</p>
    <p class = "sublabel4">(will replace previous goal)</p>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
		<label class = "sublabel2" for="details">What are you saving for?</label><br>
      <div class = "textbox">
        <input class = "form-control" type="text" id="details" name="details" required>
      </div>
      <br>

        <label class = "sublabel2" for="savingsGoal">Savings Goal ($):</label><br>
        <div class = "textbox">
        <input class = "form-control" type="number" id="savingsGoal" name="savingsGoal" min="1" required>
		<?php 
			if(!empty($savingsGoal_err)) {
				echo <<<GFG
					<p><b>Error: $savingsGoal_err</b></p>
				GFG;
			}
		?>
		</div>
		<br>

        <label class = "sublabel2" for="currentSavings">Current Savings ($):</label><br>
	<div class = "textbox">
        <input class = "form-control" type="number" id="currentSavings" name="currentSavings" min= "0" required><br>
		<?php 
			if(!empty($currentSavings_err)) {
				echo <<<GFG
					<p><b>Error: $currentSavings_err</b></p>
				GFG;
			}
		?>
      </div>


        <input class = "sub" type="submit" value="Add Savings Goal">
    </form>
    <br>
  </div>
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

