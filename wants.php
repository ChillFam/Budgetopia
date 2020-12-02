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
    <title>Budgetopia Wants</title>
    <link rel="stylesheet" type="text/css" href="budgetopiaStyles.css">
    <script src="DOM.js"></script>
</head>

<body>
    <nav class="prim-text sec-back">
        <ul>
            <li><h2>Budgetopia</h2></li>
            <li><a href="home.php">Home</a></li>
            <li><a href="savings.php">Savings</a></li>
            <li><a href="edit.php">Edit</a></li>
            <li><a href="expenses.php">Expenses</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
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
                            <p class = "sublabel3" id="nPercent">
                                <a href="wants.php">
                                Wants: $wantsPercent %
                                </a>
                            </p>
                            <p class = "sublabel5" id="nBudgeted">
                                Budgeted: $$wantsBudgeted
                            </p>
                            <p class = "sublabel5" id="nRemain">
                                Remaining: $$wantsRemaining
                            </p>
                        </div>
                    </div>
                    <script>
                        var x = 100;
                        var y = 100;
                        var width = 300;
                        var height = 50;
                        var goal = $wantsBudgeted;      //total amount of money their trying to save
                        var progress = $wantsSpent;     //amount of money currently saved

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
                GFG;
            }
            else {
                echo '<div class = "lower-border">';
                echo '<h3> No wants data found </h3>';
                echo '</div>';
            }
        ?>
    </div>
    <div>
        <table>
            <?php
                $sql = "SELECT expenseID, details, amount, frequency, dateAdded FROM expenses WHERE category = 'wants' AND userID = " . $_SESSION["userID"];
                $stmt = mysqli_query($link, $sql);
                if (mysqli_num_rows($stmt) > 0) {
                    // output data of each row
                    echo <<<GFG
                        <p> Wants Expenses </p>
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
