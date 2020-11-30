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
    <script src="DOM.js"></script>
</head>

<body>
    <nav class="prim-text sec-back">
        <ul>
            <li><a href="home.php">Budgetopia</a></li>
            <li><a href="home.php">Home</a></li>
            <li><a href="savings.php">Savings</a></li>
            <li><a href="edit.php">Edit</a></li>
			<li><a href="expenses.php">Expenses</a></li>
			<li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
    <div id="main-content">
        <pie-chart id="pieChart">
            <pchart-element name="Savings" value="30" colour="#00A676">
            <pchart-element name="Wants" value="20" colour="#373F51">
            <pchart-element name="Needs" value="50" colour="#008DD5">
        </pie-chart>
    </div>
    <script src="pie-chart-js.js"></script>
    <div>
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
                                <a href="needs.php">
                                Needs: $needsPercent %
                                </a>
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
        <table>
            <?php
                $sql = "SELECT expenseID, details, amount, frequency, dateAdded FROM expenses WHERE category = 'needs' AND userID = " . $_SESSION["userID"];
                $stmt = mysqli_query($link, $sql);
                if (mysqli_num_rows($stmt) > 0) {
                    // output data of each row
                    echo <<<GFG
                        <tr class = "sublabel5">
                            <th> </th>
                            <th><u>Details</u></th>
                            <th><u>Amount</u></th>
                            <th><u>Frequency</u></th>
                            <th><u>Date Added</u></th>
                        </tr>
                            
                    GFG;
                    while($row = mysqli_fetch_assoc($stmt)) {
                        $expenseID = $row["expenseID"];
                        $details = $row["details"];
                        $amount = $row["amount"];
                        $frequency = $row["frequency"];
                        $dateAdded = $row["dateAdded"];
                        echo <<<GFG
                            <tr class = "sublabel5">
                                <td><input style="border: 0px; width: 100%; height: 1em;" type="radio" id=$expenseID value=$expenseID name="delete" required></td>
                                <td>$details</td>
                                <td>$$amount</td>
                                <td>$frequency</td>
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
    <footer class="prim-text, sec-back">
        <address> Created by the Budgeteers for CSCI 187 Fall 2020</address>
    </footer>

</body>
</html>
