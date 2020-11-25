<?php
// Initialize the session
session_start();
 
// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
  header("location: home.php");
  exit;
}
 
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Check if username is empty
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter username.";
    } else{
        $username = trim($_POST["username"]);
    }
    
    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate credentials
    if(empty($username_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT userID, username, password FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = $username;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);
                
                // Check if username exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $userID, $username, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            // Password is correct, so start a new session
                            session_start();
                            
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["userID"] = $userID;
                            $_SESSION["username"] = $username;                            
                            
                            // Redirect user to welcome page
                            header("location: home.php");
                        } else{
                            // Display an error message if password is not valid
                            $password_err = "Invalid Password.";
                        }
                    }
                } else{
                    // Display an error message if username doesn't exist
                    $username_err = "Account not found.";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($link);
}
?>

<!--
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="budgetopiaStyles.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
	<nav class="prim-text sec-back">
        <ul>
            <li><b>Budgetopia</b></li>
			<li><a href="register.php">Register</a></li>
        </ul>
    </nav>
    <div class="wrapper">
        <h2>Login</h2>
        <p>Please fill in your credentials to login.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Username</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>" required>
            </div>    
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Login">
				<?php 
					if(!empty($password_err) || !empty($username_err)) {
						echo '<p><b>Error: Invalid username or password</b></p>';
					}
				?>
            </div>
            <p>Don't have an account? <a href="register.php">Sign up now</a></p>
        </form>
    </div>    
</body>
</html>
-->

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <title>Login</title>

   <link rel="stylesheet" type="text/css" href="budgetopiaStyles.css">
   <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">



</head>
<body>
 <nav class="prim-text sec-back top-bottom">
       <ul>
            <li><h2>Budgetopia</h2></li>
     <li><a href="register.php">Register</a></li>
       </ul>
   </nav>
   <div class = "page">
     <div class = "full">
       <div class = "head  window-small">
         Login
       </div>
       <div class = "space window-small">
         <div class = "lower-border">
           <br>
       <p class = "sublabel">Please fill in your credentials to login.</p>
     </div>
     <div class = "lower-border">

       <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
           <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
               <label class = "sublabel2">Username</label><br>
               <div class = "textbox">
               <input type="text" name="username" class="form-control" required>
             </div>
           </div>
           <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
               <label class = "sublabel2">Password</label><br>
               <div class = "textbox">
               <input type="password" name="password" class="form-control" required>
             </div>
           </div>

           <div class="form-group">
               <input type="submit" class="btn btn-primary" value="Login">
           </div>
			<?php 
				if(!empty($password_err) || !empty($username_err)) {
					echo '<p><b>Error: Invalid username or password</b></p>';
				}
			?>
         </div>

           <div class = "sublabel2">
             <br>
             Don't have an account?
             <br>
             <div style = "padding-top: 10px;">
            <a class = "sub" href="register.php">
              Sign up now
            </a>
          </div>
          </div>
       </form>
     </div>
   </div>
</div>
   <footer class="prim-text, sec-back top-bottom">
      <address> Created by the Budgeteers for CSCI 187 Fall 2020</address>
  </footer>
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" int></script>

</body>
</html>
