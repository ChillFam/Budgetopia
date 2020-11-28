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
       <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
       <style type="text/css">
      body{ font: 14px sans-serif; }
      .wrapper{ width: 350px; padding: 20px; }
  </style>
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
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
		<div class = "head window-wide"><?php echo htmlspecialchars($_SESSION["username"]); ?>'s Expenses:</div>
		<div class = "space window-wide">
		<br>
		<br>
		
		<div>
			<table>
				<?php
					$sql = "SELECT expenseID, details, amount, frequency, category, dateAdded FROM expenses WHERE userID = " . $_SESSION["userID"];
					$stmt = mysqli_query($link, $sql);
					if (mysqli_num_rows($stmt) > 0) {
						// output data of each row
						echo <<<GFG
							<tr class = "sublabel5">
								<th> </th>
								<th><u>Details</u></th>
								<th><u>Amount</u></th>
								<th><u>Frequency</u></th>
								<th><u>Category</u></th>
								<th><u>Date Added</u></th>
							</tr>
								
						GFG;
						while($row = mysqli_fetch_assoc($stmt)) {
							$expenseID = $row["expenseID"];
							$details = $row["details"];
							$amount = $row["amount"];
							$frequency = $row["frequency"];
							$category = $row["category"];
							$dateAdded = $row["dateAdded"];
							echo <<<GFG
								<tr class = "sublabel5">
									<td><input style="border: 0px; width: 100%; height: 1em;" type="radio" id=$expenseID value=$expenseID name="delete" required></td>
									<td>$details</td>
									<td>$$amount</td>
									<td>$frequency</td>
									<td>$category</td>
									<td>$dateAdded</td>		
								</tr>
							GFG;
						}
					} 
					else {
					  echo "<label>No expenses found</label>";
					}
				?>
			</table>
		</div><br>

		<div>
			<button type="button" onclick="window.location='addExpense.php';">Add Expense</button>
			<button type="submit"> Delete Expense</button>
		</div>
	</form>
</div>
</div>
</div>
    <footer class="prim-text, sec-back top-bottom">
        <address> Created by the Budgeteers for CSCI 187 Fall 2020</address>
    </footer>

</body>
</html>

 