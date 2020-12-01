<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$username = $password = $confirm_password = $first_name = $last_name = $email = "";
$username_err = $password_err = $confirm_password_err = $name_err = $email_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } else{
        // Prepare a select statement
        $sql = "SELECT userID FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = trim($_POST["username"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "This username is already taken.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password must have atleast 6 characters.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }
	
	// Validate name
	if(empty(trim($_POST["first_name"])) or empty(trim($_POST["last_name"]))){
        $name_err = "First and last name can not be empty.";     
    }
	elseif (!preg_match("/^[a-zA-Z-' ]*$/", trim($_POST["first_name"])) or !preg_match("/^[a-zA-Z-' ]*$/", trim($_POST["last_name"]))) {
		$name_err = "Only letters and white space allowed";
	}
	else {
		$first_name = trim($_POST["first_name"]);
		$last_name = trim($_POST["last_name"]);
	}
	
	// Validate email
	if(empty(trim($_POST["email"]))){
        $email_err = "Please enter an email address.";     
    }
	elseif (!filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL)) {
		$email_err = "Invalid email format";
	}
	else {
		// Prepare a select statement
        $sql = "SELECT email FROM users WHERE email = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", trim($_POST["email"]));
			
			if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $email_err = "This email is already in use";
                } 
				else {
					$email = trim($_POST["email"]);
				}
			}
		}
	}
	
	
	// Get new userID to be assigned
	/*
	$sql_MaxUID = "SELECT userID from users order by userID desc limit 1";
	$newUID = mysqli_prepare($link, $sql_MaxUID);
	if(mysqli_stmt_execute($newUID)){
		mysqli_stmt_store_result($newUID);
		if(mysqli_stmt_num_rows($newUID)==0) {
			$newUID = 1;
		}
		else {
			$newUID = $newUID + 1;
		}
	}
	else {
		echo "Error retrieving max uid";
	}	
	*/
	
    // Check input errors before inserting in database
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($name_err) && empty($email_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO users (username, password, firstName, lastName, dateJoined, email) VALUES (?, ?, ?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssssss", $param_username, $param_password, $param_first_name, $param_last_name, $param_dateJoined, $param_email);
			
            // Set parameters
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
			$param_first_name = $first_name;
            $param_last_name = $last_name;
			$param_dateJoined = date("Y-m-d");
			$param_email = $email;
			
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
				session_start();
                            
				// Store data in session variables
				$_SESSION["loggedin"] = true;
				$_SESSION["userID"] = $userID;
				$_SESSION["username"] = $username; 
                header("location: login.php");
            } else{
                echo "SQL Error: ". mysqli_error($link);
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <title>Sign Up</title>
   <link rel="stylesheet" type="text/css" href="budgetopiaStyles.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

  </head>
<body>
 <nav class="prim-text sec-back top-bottom">
       <ul>
            <li><h2>Budgetopia</h2></li>
			<li><a href="login.php">Login</a></li>
       </ul>
   </nav>
   <div class = "page">
    <div class = "full">
      <div class = "head  window-small">
        Sign up
      </div>
      <div class = "space window-small">
        <div class = "lower-border">
          <br>
          <p class = "sublabel">Please fill in this form to create an account.</p>
        </div>
        <div class = "lower-border">
		<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
           <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
               <label class = "sublabel2">Username</label><br>
               <div class = "textbox">
               <input type="text" name="username" class="form-control" required>
			   <?php 
					if(!empty($username_err)) {
						echo '<p><b>Error: Username is already taken</b></p>';
					}
				?>
             </div>
           </div>

           <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
               <label class = "sublabel2">Password</label><br>
               <div class = "textbox">
               <input type="password" name="password" class="form-control"  required>
			   <?php 
					if(!empty($password_err)) {
						echo '<p><b>Error: Password must contain atleast 6 characters</b></p>';
					}
				?>
             </div>
           </div>

           <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
               <label class = "sublabel2">Confirm Password</label><br>
               <div class = "textbox">
               <input type="password" name="confirm_password" class="form-control"  required>
			   <?php 
					if(!empty($confirm_password_err)) {
						echo '<p><b>Error: Passwords did not match</b></p>';
					}
				?>
             </div>
           </div>

     <div class="form-group <?php echo (!empty($name_err)) ? 'has-error' : ''; ?>">
               <label class = "sublabel2">First Name</label><br>
              <div class = "textbox">
               <input type="text" name="first_name" class="form-control" required>
             </div>
           </div>

     <div class="form-group <?php echo (!empty($name_err)) ? 'has-error' : ''; ?>">
               <label class = "sublabel2">Last Name</label><br>
              <div class = "textbox">
               <input type="text" name="last_name" class="form-control"  required>
			   <?php 
					if(!empty($name_err)) {
						echo '<p><b>Error: Invalid characters in first or last name</b></p>';
					}
				?>
             </div>
           </div>

     <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
               <label class = "sublabel2">Email</label><br>
              <div class = "textbox">
               <input type="text" name="email" class="form-control" required>
			   <?php 
					if(!empty($email_err)) {
						echo '<p><b>Error: Invalid email</b></p>';
					}
				?>
             </div>
           </div>
           <div class="form-group" style = "padding-top:10px;">
               <input type="submit" class="btn btn-primary" value="Submit">
               <!-- <input type="reset" class="btn btn-default" value="Reset"> -->
               <br>

           </div>
         </div>
         <br>
         <div class = "sublabel2">
           Already have an account?
           <br>
           <div style = "padding-top:10px;">
          <a class = "sub" href="login.php">
            Login here
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
