<?php
			
			session_start();
			require_once("mysqli_connect.php");

            // Define variables and initialize with empty values
            $oldpassword=$password = $confirm_password = "";
            $oldpassword_err = $confirm_password_err = "";
             
            // Processing form data when form is submitted
            if($_SERVER["REQUEST_METHOD"] == "POST"){
             
                
            // Check if password is empty
            if(empty(trim($_POST['pwd0']))){
                $oldpassword_err = 'Please enter your old password.';
            } else{
                $oldpassword = trim($_POST['pwd0']);
            }
    

                    if(empty($oldpassword_err)){
                        // Prepare a select statement
                        $username = $_SESSION['username'];
                        $sql = "SELECT username, password FROM jzusers WHERE username = '$username'";
                        
                        if($stmt = mysqli_prepare($link, $sql)){
                            // Bind variablesto the prepared statement as parameters
                            
                            // Set parameters
                            
                            // Attempt to execute the prepared statement
                            if(mysqli_stmt_execute($stmt)){
                                // Store result
                                mysqli_stmt_store_result($stmt);
                                
                                // Check if username exists, if yes then verify password
                                if(mysqli_stmt_num_rows($stmt) == 1){                    
                                    // Bind result variables
                                    mysqli_stmt_bind_result($stmt, $username, $hashed_password);
                                    if(mysqli_stmt_fetch($stmt)){
                                        if(password_verify($oldpassword, $hashed_password)){
                                            /* Password is correct, so start a new session and
                                            save the username to the session */
                                        } 
                                        else{
                                            // Display an error message if password is not valid
                                            $oldpassword_err = 'Your old password is invalid. Please re-enter';
                                        }
                                    }
                                } 
                            } else{
                                echo "Oops! Something went wrong. Please try again later.";
                            }
                        }
                        
                        // Close statement
                        mysqli_stmt_close($stmt);
                    }
                    
                  
            }

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
  
                 if(empty($password_err) && empty($confirm_password_err) && empty($oldpassword_err)){
                    $hashedpassword = password_hash($password, PASSWORD_DEFAULT);
        
                    // Prepare an insert statement
                    $sql = "
                    UPDATE jzusers 
                    SET password = '$hashedpassword'
                    WHERE username='$username'
                    ";
                     
                    if($stmt = mysqli_prepare($link, $sql)){
                        // Attempt to execute the prepared statement
                        if(mysqli_stmt_execute($stmt)){
                            // Redirect to login page
                            header("location: logout.php");
                        } else{
                            echo "Something went wrong. Please try again later.";
                        }
                    }
                     
                    // Close statement
                    mysqli_stmt_close($stmt);
                }
                
                // Close connection
                mysqli_close($link);
            
            
            
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
		<nav class="navbar navbar-inverse style="position: fixed; width: 100%; top: 0"">
		  	<div class="container-fluid">
		    	<div class="navbar-header">
		      		<a class="navbar-brand" href="profile.php"><span class="glyphicon glyphicon-music"></a>
	    		</div>
	    		<ul class="nav navbar-nav">
	      			<li class="active"><a href="#">Profile</a></li>
				    <li><a href="search.php">Search</a></li>
				</ul>

	    		<ul class="nav navbar-nav navbar-right">
                <li><a href="profile.php"><b><?php echo $_SESSION['username']; ?></b></a></li>
				<li><a href="logout.php"><span class="glyphicon glyphicon-log-in"></span> Log-out</a></li>
				</ul>
	  		</div>
		</nav>


	<div class="container">
		<div class="row">
		    <div class="col-sm-3" style="background-color:#212121; height: 100%; margin-left: 10px;">
			    <p><img src="images/avatar.png" alt="user icon" height="200" width="200" style="display: block; margin:auto; float:center; background-color:#222; margin-top: 10px;"></p>
				<h2><p style="text-align: center;" class="text-inverse"><?php echo $_SESSION['username']; ?></p></h2>
				<p class="c1">
				<h4><b><p class="text-inverse"><a href= "#">Personal Info</p><b></h4>
				</p>
				<p class="c1">
				<h4><p class="text-inverse"><a href= "instruments.php">Instruments</a></p></h4>
				</p>
				<p class="c1">
				<h4><p class="text-inverse"><a href="artists.php">Artists</a></p></h4>
				</p>
				<p class="c1">
				<h4><p class="text-inverse"><a href="genres.php">Genres</a></p></h4>
				</p>
			</div>
		</div>


		<div class="col-sm-8" style="background-color:#212121; height: 100%; margin-left: 10px;">
			<h1>CHANGE PASSWORD</h1>
		
			<div class="row">
     
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        
        <div class="form-group <?php echo (!empty($oldpassword_err)) ? 'has-error' : ''; ?>">
                <label>Old Password<sup>*</sup></label>
                <input type="password" name="pwd0" class="form-control" value="<?php echo $oldpassword; ?>">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>

        <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>New Password<sup>*</sup></label>
                <input type="password" name="pwd" class="form-control" value="<?php echo $password; ?>">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>

            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label>Confirm New Password<sup>*</sup></label>
                <input type="password" name="pwd1" class="form-control" value="<?php echo $confirm_password; ?>">
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>

            <button type="submit">Modify</button>

                <button onclick="window.location.href='profile.php'" type="button" class="cancelbtn">Cancel</button>

		</div>


        </div>
        </form>

    </body>
    <div class="footer">
	<p><h4><p class="text-muted">© Jai Khanna | <a href="imprint.php">Imprint</a> | <a href = "maintainence.php">Maintainence</a></p></h4></p>
	</div>
</html>