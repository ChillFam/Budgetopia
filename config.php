<?php
/* Database credentials. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
define('DB_SERVER', 'database1.crupaj7yefx7.us-west-2.rds.amazonaws.com');
define('DB_USERNAME', 'guest');
define('DB_PASSWORD', 'budg3tP1@nn3r');
define('DB_NAME', 'budgetPlannerDB');
 
/* Attempt to connect to MySQL database */
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
 
// Check connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
} 
?>