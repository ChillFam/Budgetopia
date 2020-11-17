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
				echo '<script>alert("Expense added successfully!")</script>'; 
            } 
			else{
                echo "SQL Error: ". mysqli_error($link);
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
	}
	else {
		echo $amount_err . "\n" . $label_err . "\n" . $category_err . "\n" . $frequency_err;
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
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
	
        <label for="amount">Amount:</label><br>
        <input type="number" id="amount" name="amount"><br><br>
		
        <label for="category">Category:</label><br>
        <input type="radio" id="needs" name="category" value="needs">
        <label for="needs">Needs</label><br>
        <input type="radio" id="wants" name="category" value="wants">
        <label for="wants">Wants</label><br><br>
		
		<div>
			<label for="frequency">Frequency:</label>
			<select name="frequency" id="frequency">
				<option value="once">One time</option>
				<option value="daily">Daily</option>
				<option value="weekly">Weekly</option>
				<option value="monthly">Monthly</option>
			</select>
		</div><br>
		
        <label for="category">Label your expense:</label><br>
        <input type="text" id="label" name="label"><br><br>
        <input type="submit" value="Submit">
    </form> 
        
    <footer class="prim-text, sec-back">
        <address> Created by the Budgeteers for CSCI 187 Fall 2020</address>
    </footer>

</body>
</html>
