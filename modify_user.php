<?php
			
			session_start();
			require_once("mysqli_connect.php");

            // Define variables and initialize with empty values
            $username="";
            $username_err="";
             
            // Processing form data when form is submitted
            if($_SERVER["REQUEST_METHOD"] == "POST"){
             
                // Validate username
                if(empty(trim($_POST["uname"]))){
                    $username_err = "Please enter a username.";
          
                }else{
                    $username=$_SESSION['username'];
                    if(trim($_POST["uname"]!=$_SESSION["username"])){
                    
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
                    }
                

 
                
                // Check input errors before inserting in database
                if(empty($username_err)){
                    
                    $first_name=trim($_POST['first']);
                    $last_name=trim($_POST['last']);
                    $email=trim($_POST['email']);
                    $gender=trim($_POST['gender']);
                    
                    // Prepare an insert statement
                    $sql = "
                    UPDATE jzusers 
                    SET username = '$username', email='$email', gender='$gender', first_name='$first_name', last_name='$last_name'
                    WHERE username='" . $_SESSION['username'] . "'
                    ";
                     
                    if($stmt = mysqli_prepare($link, $sql)){
                        
                        // Attempt to execute the prepared statement
                        if(mysqli_stmt_execute($stmt)){
                            // Redirect to login page
                            $_SESSION['username']=$username;
                            header("location: profile.php");
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
				<h4><b><p class="text-inverse"><a href= "profile.php">Personal Info</p><b></h4>
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
			<h1>MODIFY USER DATA</h1>
		
			<div class="row">
     
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Username<sup>*</sup></label>
                <input type="text" name="uname"class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>   

            <label><b>Change First Name</b></label>
            <input type="text" placeholder="Enter First Name" name="first">

            <label><b>Change Last Name</b></label>
            <input type="text" placeholder="Enter Last Name" name="last">

            <label><b>Change Email</b></label>
            <input type="text" placeholder="Enter Email ID" name="email">

            <label><b>Gender</b></label>
                <select name="gender">
                <option value="male">Male</option>
                <option value="female">Female</option>
                <option value="other">Other</option>
                </select>

                <button type="submit">Modify</button>

                <button onclick="window.location.href='profile.php'" type="button" class="cancelbtn">Cancel</button>


    </div>
        </div>

    	<div class="footer">
	<p><h4><p class="text-muted">© Jai Khanna | <a href="imprint.php">Imprint</a> | <a href = "maintainence.php">Maintainence</a></p></h4></p>
	</div>

	</body>
</html>