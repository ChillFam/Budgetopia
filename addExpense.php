<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

// Include config file
require_once "config.php";

$userID = $_SESSION["userID"];
$amount_err = $label_err = $category_err = $frequency_err = "";
$amount = $label = $category = $frequency = "";


// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
	
	if(empty(trim($_POST["amount"]))){
        $amount_err = "Please enter an amount";
    } 
	elseif(!is_numeric(trim($_POST["amount"]))){
		$amount_err = "Only decimal values allowed";
	}
	elseif(trim($_POST["amount"]) <= 0){
		$amount_err = "Amount must be greater than $0";
	}
	else{
		$amount = round(trim($_POST["amount"]), 2);
	}
	
	if(empty(trim($_POST["category"]))){
		$category_err = "Please select a category";
    }
	/*
	elseif(trim($_POST["category"]) != "needs" || trim($_POST["category"]) != "wants") {
		$category_err = "Invalid category";
	}
	*/
	else {
		$category = trim($_POST["category"]);
	}
	
	if(empty(trim($_POST["label"]))){
        $label_err = "Please label your expense";
    } 
	else{
		$label = trim($_POST["label"]);
	}
	
	if(empty($amount_err) && empty($label_err) && empty($category_err) && empty($frequency_err)) {
		$sql = "INSERT INTO expenses (userID, amount, category, details, frequency, dateAdded) VALUES (?, ?, ?, ?, ?, ?)";
		
		if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "iissss", $userID, $amount, $category, $label, $_POST["frequency"], date("Y-m-d"));
			
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
				header("location: expenses.php");
            } 
			else{
                echo "SQL Error: ". mysqli_error($link);
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
	}
	else {
		#echo $amount_err . "\n" . $label_err . "\n" . $category_err . "\n" . $frequency_err;
	}
	
	// Close connection
    mysqli_close($link);
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
            <li><a href="edit.php">Edit</a></li>
			<li><a href="expenses.php">Expenses</a></li>
			<li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
    <div class = "page">
    <div class="full">
  <div class = "head content window-small">
    Add Expense
    </div>
    <div class="space window-small">
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="lower-border top-space">
        <label class = "sublabel" for="category">Label:</label><br>
        <input class = "textbox" type="text" id="label" name="label" required>
        </div>
        <div class="lower-border">
        		<label class = "sublabel" for="amount">Amount ($):</label><br>
                <input class = "textbox" type="number" id="amount" name="amount" min="1" required>
        </div>
        <div class = "lower-border">
          <label class = "sublabel" for="frequency">Frequency:</label>&nbsp;
          <select name="frequency" id="frequency">
            <option value="once">One time</option>
            <option value="daily">Daily</option>
            <option value="weekly">Weekly</option>
            <option value="monthly">Monthly</option>
          </select>
        </div>
        <div class="lower-border">
        <label class = "sublabel" for="category">Type:</label><br>
        <div class="list">
        <input type="radio" id="needs" name="category" value="needs" required>
        <label for="needs">Needs</label><br>
        <input type="radio" id="wants" name="category" value="wants">
        <label for="wants">Wants</label>
        </div>
        </div>
		<?php 
			if(!empty($amount_err)) {
				echo <<<GFG
					<p><b>Error: $amount_err</b></p>
				GFG;
			}
		?>
        <input class = "sub" type="submit" value="Add Expense">
    </form>
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

