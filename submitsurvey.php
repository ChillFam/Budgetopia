<?php

$question1 = $_POST['question1'];
$question2 = $_POST['question2'];
$question3 = $_POST['question3'];
$question4 = $_POST['question4'];

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

$sql = "INSERT INTO survey_answers (question_1, question_2, question_3, question_4)
VALUES ('$question1', '$question2', '$question3', '$question4')";

if ($conn->query($sql) === TRUE) {
    echo "     \t\t\t Thank you for filling out our survey!";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
