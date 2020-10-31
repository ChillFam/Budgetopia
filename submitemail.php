<?php

$email = $_POST['email'];


$servername = "localhost";
$username = "anselmorris";
$password = "psw";
$dbname = "db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "INSERT INTO email_subscription (email_address)
VALUES ('$email')";

if ($conn->query($sql) === TRUE) {
    echo "\t\t\t\t\t    Subscribed!";
} else {

    echo "\t\t This email address is already subscribed.";

}

$conn->close();
?>
