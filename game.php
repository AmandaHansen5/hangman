<?php session_start(); ?>

<!DOCTYPE html>
<html>
	<head>
		<meta name="viewport" content="width=device-width" />
		<style>
			h1 {
			  color: blue;
			  font-size: 300%;
			  padding: 0px;
			  margin: 0px;
			}
			h2 {
			  color: blue;
			  font-size: 300%;
			  padding: 0px;
			  margin: 0px;
			  -webkit-text-stroke-width: 3px;
			  -webkit-text-stroke-color: green;
			}
			h3 {
			  color: green;
			}
			h4 {
			  color: green;
			  font-size: 225%;
			  padding: 0px;
			  margin: 0px;
			}
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
		</style>
	</head>
    <body>
		<h2>Hangman</h2>
		<h3>Guess the word by entering a letter below!</h3>
		
		<form action="game.php" method="post">
			<p><input type="text" name="Guess" maxlength = "1"/></p>
			<input type="submit" value="Submit"/>
		</form>
		<br>

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
			$gameNumber = $_SESSION["gameNumber"];
			$Guess = $_POST["Guess"]; 
			$Guess = strtolower($Guess);
			$wordQuery = "SELECT Word FROM Scores WHERE GameNumber = $gameNumber";
			
            $getWord = $connection->query($wordQuery);
            $words = "";

            if($getWord->num_rows > 0){
                while($row = $getWord->fetch_assoc()){
                    $words = $row["Word"];
                }
            }

			$selectLettersGuessedSQL = "SELECT Letter FROM Guesses WHERE GameNumber = $gameNumber";
			$lettersGuessed = mysqli_query($connection, $selectLettersGuessedSQL);
			
			$selectWordGuessedSQL = "SELECT GuessedWord FROM Scores WHERE GameNumber = $gameNumber";
			$notWordGuessed = mysqli_query($connection, $selectWordGuessedSQL);
			
			$wordGuessed = "";
			if($notWordGuessed->num_rows > 0){
				while($row = $notWordGuessed->fetch_assoc()){
					$wordGuessed = $row["GuessedWord"];
				}
			}
			
			echo "<b>Letters Guessed: " . $Guess;
			if($lettersGuessed->num_rows > 0){
				while($row = $lettersGuessed->fetch_assoc()){
					$letter = $row["Letter"];
					echo ", " . $letter;
				}
			}
		
			echo "</b><br>";
			
			$selectGuessesSQL = "SELECT IncorrectGuesses FROM Scores WHERE GameNumber = $gameNumber";
			$notNumGuesses = mysqli_query($connection, $selectGuessesSQL);
			$otherSelectGuessesSQL = "SELECT Guesses FROM Scores WHERE GameNumber = $gameNumber";
			$otherNotNumGuesses = mysqli_query($connection, $otherSelectGuessesSQL);
			
			//if guess is not null, insert guess into db and increment Guesses in Scores Table
			if($Guess != ""){
				$insertSQL = "INSERT INTO Guesses(GameNumber, Letter) VALUES ('$gameNumber', '$Guess')";
				$connection->query($insertSQL);
				
				$arr = str_split(trim($Guess));
				$parts = str_split($words);
				
				$variable = "";
				foreach ($parts as $letter) {
					foreach ($arr as $arrs){
						if($letter === $arrs){
							$variable = "inTheWord";
						}
					}
				}
				
				//if letter not in the word increment incorrectGuesses
				if($variable != "inTheWord"){
					if($notNumGuesses->num_rows > 0){
						while($row = $notNumGuesses->fetch_assoc()){
							$numGuesses = $row["IncorrectGuesses"];
						}
					}
					
					$numGuesses = intval($numGuesses) + 1;
					$updateSQL = "UPDATE Scores SET IncorrectGuesses = $numGuesses WHERE GameNumber = $gameNumber";
					mysqli_query($connection, $updateSQL);
				}
				//otherwise increment total guessed and add letter to GuessedWord
				if($otherNotNumGuesses->num_rows > 0){
					while($row = $otherNotNumGuesses->fetch_assoc()){
						$otherNumGuesses = $row["Guesses"];
					}
				}
				
				$otherNumGuesses = intval($otherNumGuesses) + 1;
				$otherUpdateSQL = "UPDATE Scores SET Guesses = $otherNumGuesses WHERE GameNumber = $gameNumber";
				mysqli_query($connection, $otherUpdateSQL);
								
				$j = 0;
				foreach ($parts as $letter) {
					foreach ($arr as $arrs){
						if($letter === $arrs){
							$wordGuessed = substr_replace($wordGuessed,$Guess,$j,1);
						}
						$j = $j + 2;
					}
				}
				$updateWordGuessedSQL = "UPDATE Scores SET GuessedWord = '$wordGuessed' WHERE GameNumber = $gameNumber";
				mysqli_query($connection, $updateWordGuessedSQL);
			}
			
			$notNumGuesses = mysqli_query($connection, $selectGuessesSQL);
			if($notNumGuesses->num_rows > 0){
                while($row = $notNumGuesses->fetch_assoc()){
                    $numGuesses = $row["IncorrectGuesses"];
                }
            }
						
			$otherNotNumGuesses = mysqli_query($connection, $otherSelectGuessesSQL);
			if($otherNotNumGuesses->num_rows > 0){
                while($row = $otherNotNumGuesses->fetch_assoc()){
                    $otherNumGuesses = $row["Guesses"];
                }
            }
			
			echo "<h4>&nbsp" . $wordGuessed . "</h4><br>";
			if($numGuesses === '7'){
				echo "<h1>&nbsp&nbsp+---+</h1>";
				echo "<h1>&nbsp&nbsp&nbsp|&nbsp&nbsp&nbsp&nbsp&nbsp|</h1>";
				echo "<h1>&nbsp&nbsp&nbsp|&nbsp&nbsp&nbsp&nbspO</h1>";
				echo "<h1>&nbsp&nbsp&nbsp|&nbsp&nbsp&nbsp/-\</h1>";
				echo "<h1>&nbsp&nbsp&nbsp|&nbsp&nbsp&nbsp&nbsp&nbsp|</h1>";
				echo "<h1>&nbsp&nbsp&nbsp|&nbsp&nbsp&nbsp&nbsp/\</h1>";
				echo "<h1>=====</h1>";
			}
			if($numGuesses === '6'){
				echo "<h1>&nbsp&nbsp+---+</h1>";
				echo "<h1>&nbsp&nbsp&nbsp|&nbsp&nbsp&nbsp&nbsp&nbsp|</h1>";
				echo "<h1>&nbsp&nbsp&nbsp|&nbsp&nbsp&nbsp&nbspO</h1>";
				echo "<h1>&nbsp&nbsp&nbsp|&nbsp&nbsp&nbsp/-\</h1>";
				echo "<h1>&nbsp&nbsp&nbsp|&nbsp&nbsp&nbsp&nbsp&nbsp|</h1>";
				echo "<h1>&nbsp&nbsp&nbsp|&nbsp&nbsp&nbsp&nbsp/</h1>";
				echo "<h1>=====</h1>";
			}
			if($numGuesses === '5'){
				echo "<h1>&nbsp&nbsp+---+</h1>";
				echo "<h1>&nbsp&nbsp&nbsp|&nbsp&nbsp&nbsp&nbsp&nbsp|</h1>";
				echo "<h1>&nbsp&nbsp&nbsp|&nbsp&nbsp&nbsp&nbspO</h1>";
				echo "<h1>&nbsp&nbsp&nbsp|&nbsp&nbsp&nbsp/-\</h1>";
				echo "<h1>&nbsp&nbsp&nbsp|&nbsp&nbsp&nbsp&nbsp&nbsp|</h1>";
				echo "<h1>&nbsp&nbsp&nbsp|</h1>";
				echo "<h1>=====</h1>";
			}
			if($numGuesses === '4'){
				echo "<h1>&nbsp&nbsp+---+</h1>";
				echo "<h1>&nbsp&nbsp&nbsp|&nbsp&nbsp&nbsp&nbsp&nbsp|</h1>";
				echo "<h1>&nbsp&nbsp&nbsp|&nbsp&nbsp&nbsp&nbspO</h1>";
				echo "<h1>&nbsp&nbsp&nbsp|&nbsp&nbsp&nbsp/-\</h1>";
				echo "<h1>&nbsp&nbsp&nbsp|</h1>";
				echo "<h1>&nbsp&nbsp&nbsp|</h1>";
				echo "<h1>=====</h1>";
			}
			if($numGuesses === '3'){
				echo "<h1>&nbsp&nbsp+---+</h1>";
				echo "<h1>&nbsp&nbsp&nbsp|&nbsp&nbsp&nbsp&nbsp&nbsp|</h1>";
				echo "<h1>&nbsp&nbsp&nbsp|&nbsp&nbsp&nbsp&nbspO</h1>";
				echo "<h1>&nbsp&nbsp&nbsp|&nbsp&nbsp&nbsp/-</h1>";
				echo "<h1>&nbsp&nbsp&nbsp|</h1>";
				echo "<h1>&nbsp&nbsp&nbsp|</h1>";
				echo "<h1>=====</h1>";
			}
			if($numGuesses === '2'){
				echo "<h1>&nbsp&nbsp+---+</h1>";
				echo "<h1>&nbsp&nbsp&nbsp|&nbsp&nbsp&nbsp&nbsp&nbsp|</h1>";
				echo "<h1>&nbsp&nbsp&nbsp|&nbsp&nbsp&nbsp&nbspO</h1>";
				echo "<h1>&nbsp&nbsp&nbsp|&nbsp&nbsp&nbsp&nbsp&nbsp-</h1>";
				echo "<h1>&nbsp&nbsp&nbsp|</h1>";
				echo "<h1>&nbsp&nbsp&nbsp|</h1>";
				echo "<h1>=====</h1>";
			}
			if($numGuesses === '1'){
				echo "<h1>&nbsp&nbsp+---+</h1>";
				echo "<h1>&nbsp&nbsp&nbsp|&nbsp&nbsp&nbsp&nbsp&nbsp|</h1>";
				echo "<h1>&nbsp&nbsp&nbsp|&nbsp&nbsp&nbsp&nbspO</h1>";
				echo "<h1>&nbsp&nbsp&nbsp|</h1>";
				echo "<h1>&nbsp&nbsp&nbsp|</h1>";
				echo "<h1>&nbsp&nbsp&nbsp|</h1>";
				echo "<h1>=====</h1>";
			}
			if($numGuesses === '0'){
				echo "<h1>&nbsp&nbsp+---+</h1>";
				echo "<h1>&nbsp&nbsp&nbsp|&nbsp&nbsp&nbsp&nbsp&nbsp|</h1>";
				echo "<h1>&nbsp&nbsp&nbsp|</h1>";
				echo "<h1>&nbsp&nbsp&nbsp|</h1>";
				echo "<h1>&nbsp&nbsp&nbsp|</h1>";
				echo "<h1>&nbsp&nbsp&nbsp|</h1>";
				echo "<h1>=====</h1>";
			}
			
			
			//finished game logic
			$wordGuessed = str_replace(' ', '', $wordGuessed);
			if($words === $wordGuessed){
				$updateFinishedGameSQL = "UPDATE Scores SET FinishedGame = 'Y' WHERE GameNumber = $gameNumber";
				mysqli_query($connection, $updateFinishedGameSQL);
			}
			if($words === $wordGuessed || $numGuesses === '7'){
				$deleteGuessesSQL = "DELETE FROM Guesses WHERE GameNumber = $gameNumber";
				mysqli_query($connection, $deleteGuessesSQL);
				header("Location: gameOver.php");
			}
            $connection->close();
        ?>
		
		<p><b>Done playing? Logout <a href="logout.php">here</a></b></p>

    </body>
</html>