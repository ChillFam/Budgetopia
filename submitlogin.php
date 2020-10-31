<?php

$email = $_POST['liemail'];
$pass = $_POST['lipsw'];

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



$sql = "SELECT * FROM login_info WHERE email_address = '$email'";


$result = mysqli_query($conn, $sql);
$row = $result->fetch_array(MYSQLI_ASSOC);
$p = $row["password"];
$fn = $row["first_name"];
$ln = $row["last_name"];
$co = $row["country"];
$ci = $row["city_town"];

if(mysqli_num_rows($result) != 0){



    if (strcmp($p, $pass) == 0){
        echo "FN:$fn,LN:$ln,CO:$co,CI:$ci";
    }
    else{
        echo "Incorrect Password";
    }
}
else{

    echo "Account Does Not Exist";
}

$conn->close();
?>
