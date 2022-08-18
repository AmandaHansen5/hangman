<?php session_start(); ?>

<!DOCTYPE html>
<html>
<body>
	<?php
		session_start();
		$servername = "sql100.epizy.com";
		$usernames = "epiz_31813469";
		$passwords = "wp7kvg4j";
		$dbname = "epiz_31813469_HangManDB";

		$connection = new mysqli($servername, $usernames, $passwords, $dbname);
		if($connection->connect_errno ) 
		{
               printf("Connect failed: %s<br />", $connection->connect_error);
               exit();
        }
		else {
			printf('Connected successfully.<br />');
		}
		
 
		$password = $_POST["Password"]; 
		echo "Password entered: " . $password . "<br>";
		$username = $_POST["Username"]; 
		echo "Username entered: " . $username . "<br>";
		$selectSaltSQL = "SELECT Salt FROM Users WHERE Username = '$username'";
		$salt = $connection->query($selectSaltSQL);
		
		$notSalt = "";

		if($salt->num_rows > 0){
		  while($row = $salt->fetch_assoc()){
			$notSalt = $row["Salt"];
		  }
		}
		
		echo "salt stored: " . $notSalt . "<br>";
		$hashedPassword = hash('sha256', $notSalt . $password );
		echo "Hashed Password: " . $hashedPassword . "<br>";
		
		
		//create session variable for username
		$_SESSION["username"] = $username;

		//see if username already exists, if so route to create account page again w/ error message, if not create account and begin game
		$selectSQL = "SELECT * FROM Users WHERE Username = '$username'";
		$selectPasswordSQL = "SELECT Password FROM Users WHERE Username = '$username'";
		$select = mysqli_query($connection, $selectSQL);
		$selectPassword = mysqli_query($connection, $selectPasswordSQL);
		
		$notSelectedPassword = "";
		
		if($selectPassword->num_rows > 0){
		  while($row = $selectPassword->fetch_assoc()){
			$notSelectedPassword = $row["Password"];
		  }
		}
		
		echo "Password from DB: " . $notSelectedPassword . "<br>";

		
		echo "Starting password comparison" . "<br>";
		
		if(!mysqli_num_rows($select)) {
			echo "No user exists" . "<br>";
            header("Location: welcomeNoUser.html");
		}
		else {
			echo "User exists" . "<br>";
		}
		
		if(mysqli_num_rows($select)==1) {
			//if password doesn't match kick error
			if($hashedPassword === $notSelectedPassword){
				echo "Right Password" . "<br>";
				header("Location: createnewgame.php");
			}
			//otherwise login and start game
			if($hashedPassword != $notSelectedPassword){
				echo "Wrong Password" . "<br>";
				header("Location: welcomeNoUser.html");
			}
						
		}
		
		echo "Made it to the end of the page";
		
		$connection->close();

		
    ?>
</body>
</html>