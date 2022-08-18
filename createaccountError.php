
<!DOCTYPE html>
<html>
	<head>
		<meta name="viewport" content="width=device-width" />
		<style>
			body {
			  font-family: verdana;
			  font-size: 3.5vw;
			}
			
			 @media (min-width: 450px) 
			 {
				body
				{
					font-size: 1vw;
				}
			 }
			h3 {
			  font-size: 200%;
			  color: green;
			  -webkit-text-stroke-width: 1px;
			  -webkit-text-stroke-color: blue;
			  padding: 0px;
			  margin: 0px;
			}
			h4 {
			  color: red;
			  font-size: 150%;
			  padding: 0px;
			  margin: 0px;
			  margin-top: 10px;
			}
		</style>
	</head>
	<body>
		  <h3>Create your account below:</h3>
		  <h4>Username already in use!</h4>
		  <form action="validateaccountcreation.php" method="post">
			<p>Username: <input type="text" name="Username"/></p>
			<p>Password: <input type="text" name="Password"/></p>
			<p>Confirm Password: <input type="text" name="ConfirmPassword"/></p>
			<input type="submit" value="Submit"/>
		  </form>
		  <p><b>Already have an account? Login <a href="welcome.html">here</a></b></p>

	</body>
</html>