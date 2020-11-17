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

if($_SERVER["REQUEST_METHOD"] == "POST"){
	//echo "Delete expenseID: " . trim($_POST["delete"]);
	if(empty(trim($_POST["delete"]))) {
		echo '<script>alert("Please select an expense")</script>';
	}
	else {
		$sql = "Delete from expenses where expenseID = ?";
		if($stmt = mysqli_prepare($link, $sql)){
			mysqli_stmt_bind_param($stmt, "i", trim($_POST["delete"]));
			if(mysqli_stmt_execute($stmt)){
				echo '<script>alert("Expense deleted successfully!")</script>'; 
			} 
			else{
				echo "SQL Error: ". mysqli_error($link);
			}
			mysqli_stmt_close($stmt);
		}
	}
	// Close connection
    mysqli_close($link);
	header("Refresh:0");
	
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
		<h3>Expenses for <?php echo htmlspecialchars($_SESSION["username"]); ?>:</h3>
		<div>
			<?php
				$sql = "SELECT expenseID, details, amount, frequency, category, dateAdded FROM expenses WHERE userID = " . $_SESSION["userID"];
				$stmt = mysqli_query($link, $sql);
				if (mysqli_num_rows($stmt) > 0) {
					// output data of each row
					while($row = mysqli_fetch_assoc($stmt)) {
						//echo $row["expenseID"];
						$expenseID = $row["expenseID"];
						echo <<<GFG
							<input type="radio" id=$expenseID value=$expenseID name="delete" required>
						GFG;
						echo nl2br("Expense: " . $row["details"] . "\nAmount: $" . $row["amount"] . "\nFrequency: " . $row["frequency"] . "\nCategory: " . $row["category"] . "\nDate Added: " . $row["dateAdded"] . "\n\n");
					}
				} 
				else {
				  echo "No expenses found";
				}
			?>
		</div><br>
		
		<div>
			<button type="button" onclick="window.location='addExpense.php';">Add Expense</button>
			<button type="submit"> Delete Expense</button>
		</div>
	</form>
 
    <footer class="prim-text, sec-back">
        <address> Created by the Budgeteers for CSCI 187 Fall 2020</address>
    </footer>

</body>
</html>
 