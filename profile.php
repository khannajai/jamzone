<?php
// Initialize the session
session_start();

// If session variable is not set it will redirect to login page
if(!isset($_SESSION['username']) || empty($_SESSION['username'])){
	header("location: login.php");
	exit;
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
			<h1>PERSONAL INFO</h1>
			
			<?php
			
			session_start();
			require_once("mysqli_connect.php");
			$sql = "SELECT user_ID, username, first_name, last_name, email, gender FROM jzusers WHERE username='" . $_SESSION['username'] . "'";
			$result = mysqli_query($link, $sql);
			if (mysqli_num_rows($result) > 0) {
				// output data of each row
				while($row = mysqli_fetch_assoc($result)) {
					echo "
					Username: ". $row["username"] ." <br>
					Name: ". $row["first_name"]." ". $row["last_name"]."  <br>
					Email: ". $row["email"]." <br>
					Gender: ". $row["gender"]." <br>
					";
				}
			} else {
				echo "0 results";
			}
			
			mysqli_close($link);
			?>

			<button onclick="window.location.href='modify_user.php'" type="button" class="cancelbtn">Modify Data</button>
		    <button onclick="window.location.href='change_password.php'" type="button" class="cancelbtn">Change Password</button>	
		</div>

	</div>


	</body>
	<div class="footer">
	<p><h4><p class="text-muted">© Jai Khanna | <a href="imprint.php">Imprint</a> | <a href = "maintainence.php">Maintainence</a></p></h4></p>
	</div>

</html>