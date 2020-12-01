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
					<div> 
						<?php
							$sql = "SELECT amount, frequency FROM expenses WHERE category = 'needs' AND userID = " . $_SESSION["userID"];	
							$stmt = mysqli_query($link, $sql);							
							if (mysqli_num_rows($stmt) > 0) {
								$needsBudgeted = $needsSpent = 0;
								
								while ($row = mysqli_fetch_assoc($stmt)) {
									if ($row["frequency"] == "daily") {
										$needsSpent = $needsSpent + ($row["amount"] * 30);
									}
									elseif ($row["frequency"] == "weekly") {
										$needsSpent = $needsSpent + ($row["amount"] * 4);
									}
									else {
										$needsSpent = $needsSpent + $row["amount"];
									}
								}
								
								$sql = "SELECT amount, frequency, NPercent FROM income WHERE userID = " . $_SESSION["userID"];
								$stmt = mysqli_query($link, $sql);
								if (mysqli_num_rows($stmt) > 0) {
									$row = mysqli_fetch_assoc($stmt);
									$frequency = $row["frequency"];
									$NPercent = $row["NPercent"];
									$income = 0;
									
									if ($frequency == "weekly") {
										$income = $row["amount"] * 4;
									}
									elseif ($frequency == "biweekly") {
										$income = $row["amount"] * 2;
									}
									else {
										$income = $row["amount"];
									}
									
									$needsBudgeted = round(($NPercent / 100) * $income);
									$needsRemaining = $needsBudgeted - $needsSpent;
									
									if ($needsRemaining < 0) {
										$overbudget = abs($needsRemaining);
										echo <<<GFG
											<div class="needs lower-border">
                                                <p class = "sublabel3" id="nPercent">
                                                    <a href="needs.php">
                                                        Needs: $NPercent%
                                                    </a>    
                                                </p>
                                                <p class = "sublabel5">
                                                    <b>Over Budget by $$overbudget!</b>
                                                </p>
											</div>
										GFG;
									}
									else {
										echo <<<GFG
											<div class="needs lower-border">
												<p class = "sublabel3" id="wPercent">
													<a class = "sub" href="needs.php">
														Needs: $NPercent%
													</a>    
												</p>
												<p class = "sublabel5" id="nBudgeted">
													<b> Budgeted: $$needsBudgeted </b>
												</p>
												<p class = "sublabel5" id="nSpent">
													<b> Spent: $$needsSpent </b>
												</p>
												<p class = "sublabel5" id="nRemain">
													<b> Remaining: $$needsRemaining </b>
												</p>
											</div>
										GFG;
									}
								}
							}
							else {
								echo '<div class = "lower-border">';
								echo '<h3> No data found - Head to Income tab to get started</h3>';
								echo '</div>';
							}
						
							$sql = "SELECT amount, frequency FROM expenses WHERE category = 'wants' AND userID = " . $_SESSION["userID"];	
							$stmt = mysqli_query($link, $sql);							
							if (mysqli_num_rows($stmt) > 0) {
								$wantsBudgeted = $wantsSpent = 0;
								
								while ($row = mysqli_fetch_assoc($stmt)) {
									if ($row["frequency"] == "daily") {
										$wantsSpent = $wantsSpent + ($row["amount"] * 30);
									}
									elseif ($row["frequency"] == "weekly") {
										$wantsSpent = $wantsSpent + ($row["amount"] * 4);
									}
									else {
										$wantsSpent = $wantsSpent + $row["amount"];
									}
								}
								
								$sql = "SELECT amount, frequency, WPercent FROM income WHERE userID = " . $_SESSION["userID"];
								$stmt = mysqli_query($link, $sql);
								if (mysqli_num_rows($stmt) > 0) {
									$row = mysqli_fetch_assoc($stmt);
									$frequency = $row["frequency"];
									$WPercent = $row["WPercent"];
									$income = 0;
									
									if ($frequency == "weekly") {
										$income = $row["amount"] * 4;
									}
									elseif ($frequency == "biweekly") {
										$income = $row["amount"] * 2;
									}
									else {
										$income = $row["amount"];
									}
									
									$wantsBudgeted = round(($WPercent / 100) * $income);
									$wantsRemaining = $wantsBudgeted - $wantsSpent;
												
									if ($wantsRemaining < 0) {
										$overbudget = abs($wantsRemaining);
										echo <<<GFG
											<div class="wants lower-border">
											<p class = "sublabel3" id="wPercent">
											<a href="wants.php">
											Wants: $WPercent%
											</a>    
											</p>
											<p class = "sublabel5"><b>Over Budget by $$overbudget!</b></p>
											</div>
										GFG;
									}
									else {
										echo <<<GFG
											<div class="wants lower-border">
											<p class = "sublabel3" id="wPercent">
											<a class = "sub" href="wants.php">
											Wants: $WPercent%
											</a>    
											</p>
											<p class = "sublabel5" id="wBudgeted">
											<b> Budgeted: $$wantsBudgeted </b>
											</p>
											<p class = "sublabel5" id="wSpent">
											<b> Spent: $$wantsSpent </b>
											</p>
											<p class = "sublabel5" id="wRemain">
											<b> Remaining: $$wantsRemaining </b>
											</p>
											</div>
										GFG;
									}
								}
							}
							else {
								echo '<div class = "lower-border">';
								echo '<h3> No data found </h3>';
								echo '</div>';
							}
						
							$sql = "SELECT amount, frequency, SPercent FROM income WHERE userID = " . $_SESSION["userID"];
							$stmt = mysqli_query($link, $sql);
							if (mysqli_num_rows($stmt) > 0) {
								$row = mysqli_fetch_assoc($stmt);
								$frequency = $row["frequency"];
								$SPercent = $row["SPercent"];
								$income = 0;
					
								if ($frequency == "weekly") {
									$income = $row["amount"] * 4;
								}
								elseif ($frequency == "biweekly") {
									$income = $row["amount"] * 2;
								}
								else {
									$income = $row["amount"];
								}
								
								$savingsBudgeted = round($income * ($SPercent / 100));
								
								echo <<<GFG
									<div class="savings lower-border">
									<p class = "sublabel3" id="sPercent">
									<a class = "sub" href="savings.php">
									Savings: $SPercent%
									</a>
									</p>
									<p class = "sublabel5" id="sBudgeted">
									<b> Budgeted: $$savingsBudgeted </b>
									</p>
									</div>
								GFG;
								
							}
							else {
								echo '<div class = "lower-border">';
								echo '<h3> No data found </h3>';
								echo '</div>';
							}
						
							echo <<<GFG
								<div id="main-content">
									<pie-chart id="pieChart">
											echo '<pchart-element name="Savings" value=$SPercent colour="#00A676">';
											echo '<pchart-element name="Wants" value=$WPercent colour="#373F51">';
											echo '<pchart-element name="Needs" value=$NPercent colour="#008DD5">';
									</pie-chart>
									<script src="pie-chart-js.js"></script>
								</div>
							GFG;
						?>
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

