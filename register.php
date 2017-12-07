<?php
// Include config file
require_once('mysqli_connect.php');
 
// Define variables and initialize with empty values
$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate username
    if(empty(trim($_POST["uname"]))){
        $username_err = "Please enter a username.";
    } else{
        // Prepare a select statement
        $sql = "SELECT user_ID FROM jzusers WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = trim($_POST["uname"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "This username is already taken.";
                } else{
                    $username = trim($_POST["uname"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Validate password
    if(empty(trim($_POST["pwd"]))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["pwd"])) < 6){
        $password_err = "Password must have atleast 6 characters.";
    } else{
        $password = trim($_POST['pwd']);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["pwd1"]))){
        $confirm_password_err = 'Please confirm password.';     
    } else{
        $confirm_password = trim($_POST["pwd1"]);
        if($password != $confirm_password){
            $confirm_password_err = 'Password did not match.';
        }
    }
    
    // Check input errors before inserting in database
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){
        
        $first_name=trim($_POST['first']);
        $last_name=trim($_POST['last']);
        $email=trim($_POST['email']);
        $gender=trim($_POST['gender']);
        
        // Prepare an insert statement
        $sql = "INSERT INTO jzusers (username, password, first_name, last_name, email, gender) VALUES (?, ?, '$first_name','$last_name','$email', '$gender' )";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);
            
            // Set parameters
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: login.php");
            } else{
                echo "Something went wrong. Please try again later.";
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
        <h1>SIGN-UP</h1>
            
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Username<sup>*</sup></label>
                <input type="text" name="uname"class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password<sup>*</sup></label>
                <input type="password" name="pwd" class="form-control" value="<?php echo $password; ?>">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label>Confirm Password<sup>*</sup></label>
                <input type="password" name="pwd1" class="form-control" value="<?php echo $confirm_password; ?>">
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>

            <label><b>First Name</b></label>
            <input type="text" placeholder="Enter First Name" name="first" required>

            <label><b>Last Name</b></label>
            <input type="text" placeholder="Enter Last Name" name="last" required>

            <label><b>Email</b></label>
            <input type="text" placeholder="Enter Email ID" name="email" required>

            <label><b>Gender</b></label>
                <select name="gender">
                <option value="male">Male</option>
                <option value="female">Female</option>
                <option value="other">Other</option>
                </select>

                <button type="submit">Sign-up</button>

                <button onclick="window.location.href='index.php'" type="button" class="cancelbtn">Cancel</button>
        
        </form>
        </div>
        </div>

	
    </body>
    
        <div class="footer">
		<p><h4><p class="text-muted">© Jai Khanna | <a href="imprint.php">Imprint</a> | <a href = "maintainence.php">Maintainence</a></p></h4></p>
		</div>

</html>

