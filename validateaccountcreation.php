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
		/*if($connection->connect_errno ) 
		{
               printf("Connect failed: %s<br />", $connection->connect_error);
               exit();
        }
		else {
			printf('Connected successfully.<br />');
		}*/
		
		$password = $_POST["Password"]; 
		$username = $_POST["Username"]; 
		$confirmPassword = $_POST["ConfirmPassword"];
		$randomText = md5(uniqid(rand(), TRUE));
		$salt = substr($randomText, 0, 3);
		$hashedPassword = hash('sha256', $salt . $password );
		
		//create session variable for username
		$_SESSION["username"] = $username;

		//see if username already exists, if so route to create account page again w/ error message, if not create account and begin game
		$selectSQL = "SELECT * FROM Users WHERE Username = '$username'";
		$insertSQL = "INSERT INTO Users(Username, Password, Salt) VALUES ('$username', '$hashedPassword', '$salt');";
		$select = mysqli_query($connection, $selectSQL);
		if(mysqli_num_rows($select)>=1) {
            header("Location: createaccountError.php");
		}
		else {
			//if passwords don't match, kick back to account creation page w/ error
			if($password === $confirmPassword){
				//otherwise insert the record
				if ($connection->query($insertSQL)) 
				{
					header("Location: createnewgame.php");
				}
				if ($connection->errno) 
				{
				   echo "Could not insert record into table:". $connection->error;
				}
				
				$connection->close();
			}
			if($password != $confirmPassword){
				header("Location: createaccountErrorPassword.php");
			}
		}
		
    ?>
</body>
</html>