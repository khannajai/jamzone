<?php
// Include config file
require_once ('mysqli_connect.php');
 
// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Check if username is empty
    if(empty(trim($_POST["uname"]))){
        $username_err = 'Please enter username.';
    } else{
        $username = trim($_POST["uname"]);
    }
    
    // Check if password is empty
    if(empty(trim($_POST['pwd']))){
        $password_err = 'Please enter your password.';
    } else{
        $password = trim($_POST['pwd']);
    }
    
    // Validate credentials
    if(empty($username_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT username, password, user_ID FROM jzusers WHERE username = ?";
        
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
                    mysqli_stmt_bind_result($stmt, $username, $hashed_password, $user_ID);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            /* Password is correct, so start a new session and
                            save the username to the session */
                            session_start();
                            $_SESSION['username'] = $username;
                            $_SESSION['user_ID']=$user_ID;

                                  
                            header("location: profile.php");
                        } 
                        else{
                            // Display an error message if password is not valid
                            $password_err = 'The password you entered was not valid.';
                        }
                    }
                } else{
                    // Display an error message if username doesn't exist
                    $username_err = 'No account found with that username.';
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
}
?>

<!DOCTYPE html>
	<head>
	 	<meta charset="utf-8">
	 	<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <link rel="stylesheet" type="text/css" href="css/style.css">
	</head>

	<body>
		<nav class="navbar navbar-inverse" style="position: fixed; width: 100%; top: 0">
		  	<div class="container-fluid">
		    	<div class="navbar-header">
		      		<a class="navbar-brand" href="index.php"><span class="glyphicon glyphicon-music"></a>
	    		</div>
	    		<ul class="nav navbar-nav">
				</ul>

	    		<ul class="nav navbar-nav navbar-right">
				<li><a href="register.php"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li>
				<li><a href="login.php"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
				</ul>
	  		</div>
        </nav>

        <div class="col-sm-3"> </div>
        <div class="col-sm-6">
        <div class="row">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <br><br><br><br><br>
        <h1>LOG IN</h1>
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Username<sup>*</sup></label>
                <input type="text" name="uname"class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>  

            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password<sup>*</sup></label>
                <input type="password" name="pwd" class="form-control">
                <span class="help-block"><?php echo $password_err; ?></span>
            
            </div>

            <button type="submit">Login</button>


                <button onclick="window.location.href='index.php'" type="button" class="cancelbtn">Cancel</button>
                <span class="psw">Forgot <a href="#">password?</a></span>
                <br>
                <span class="psw"> Don't have an account? <a href="register.php">sign up now</a></span>
    
        </div>

         
        
        </form>
        </div>
        </div>
				
        


	
    </body>
    
    <div class="footer">
		<p><h4><p class="text-muted">© Jai Khanna | <a href="imprint.php">Imprint</a> | <a href = "maintainence.php">Maintainence</a></p></h4></p>
		</div>
</html>
