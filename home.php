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
            <li><a href="income.php">Income</a></li>
			<li><a href="expenses.php">Expenses</a></li>
			<li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
    <div class = page>
      <div class = "full">
        <div class = "head window-medium">
          Budget
        </div>
        <div class = "space window-medium">
    
    <br>
    <div class="needs"> 
		<?php
			$sql = "SELECT need, budget, spent FROM needs WHERE userID = " . $_SESSION["userID"];
			$stmt = mysqli_query($link, $sql);
			if (mysqli_num_rows($stmt) > 0) {
                $row = mysqli_fetch_assoc($stmt);
                $needsSpent = $row["spent"];
				$needsPercent = $row["need"];
				$needsBudgeted =  $row["budget"];
                $needsRemaining =  number_format($needsBudgeted - $needsSpent, 2);

				echo <<<GFG
					<div class = "lower-border">
						<br>
                        <div class="needs lower-border">
                            <p class = "sublabel3" id="nPercent">
                                Needs: $needsPercent %
                            </p>
                            <p class = "sublabel5" id="nBudgeted">
                                Budgeted: $$needsBudgeted
                            </p>
                            <p class = "sublabel5" id="nRemain">
                                Remaining: $$needsRemaining
                            </p>
                        </div>
					</div>
				GFG;
			}
			else {
				echo '<div class = "lower-border">';
				echo '<h3> No needs data found </h3>';
				echo '</div>';
			}
		?>
	</div>
    <div> 
		<?php
			$sql = "SELECT want, budget, spent FROM wants WHERE userID = " . $_SESSION["userID"];
			$stmt = mysqli_query($link, $sql);
			if (mysqli_num_rows($stmt) > 0) {
                $row = mysqli_fetch_assoc($stmt);
                $wantsSpent = $row["spent"];
                $wantsPercent = $row["want"];
				$wantsBudgeted =  $row["budget"];
                $wantsRemaining =  number_format($wantsBudgeted - $wantsSpent, 2);
            
				echo <<<GFG
					<div class = "lower-border">
						<br>
						<div class="wants lower-border">
                            <p class = "sublabel3" id="wPercent">
                                Wants: $wantsPercent %
                            </p>
                            <p class = "sublabel5" id="wBudgeted">
                                Budgeted: $$wantsBudgeted
                            </p>
                            <p class = "sublabel5" id="wRemain">
                                Remaining: $$wantsRemaining
                            </p>
                        </div>
					</div>
				GFG;
			}
			else {
				echo '<div class = "lower-border">';
				echo '<h3> No wants data found </h3>';
				echo '</div>';
			}
		?>
	</div>
    <div class="savings"> 
		<?php
			$sql = "SELECT saving, budget FROM savings WHERE userID = " . $_SESSION["userID"];
			$stmt = mysqli_query($link, $sql);
			if (mysqli_num_rows($stmt) > 0) {
                $row = mysqli_fetch_assoc($stmt);
                $savingsPercent = $row["saving"];
                $savingsBudgeted =  $row["budget"];
                
				echo <<<GFG
					<div class = "lower-border">
						<br>
                        <div class="savings lower-border">
                            <p class = "sublabel3" id="sPercent">
                                Savings: $savingsPercent %
                            </p>
                            <p class = "sublabel5" id="sBudgeted">
                                Budgeted: $$savingsBudgeted
                            </p>
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
    
    <p>API for graph in the middle/side (formatting will change based on graph)</p>
	<div id="main-content">
        <pie-chart id="pieChart">
            <pchart-element name="Savings" value="30" colour="#00A676">
            <pchart-element name="Wants" value="20" colour="#373F51">
            <pchart-element name="Needs" value="50" colour="#008DD5">
        </pie-chart>
		<!-- <script src="pie-chart-js.js"></script> -->
    </div>
    <div>
	<input class = "sub" type="submit" value="Change Password" onclick="window.location = 'changePassword.php';" >
	</div>
    <!--
    Notes:
    make header bigger,
    make text bigger,
    change font?,
    DOM for variables,
    connect graph
    -->
  </div>
  </div>
</div>
    <footer class="prim-text, sec-back top-bottom">
        <address> Created by the Budgeteers for CSCI 187 Fall 2020</address>
    </footer>

</body>
</html>

