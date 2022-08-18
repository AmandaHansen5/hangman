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
			
			$wordQuery = "SELECT Word FROM Words ORDER BY RAND() LIMIT 1";

            $getWord = $connection->query($wordQuery);
            $words = "";

            if($getWord->num_rows > 0){
                while($row = $getWord->fetch_assoc()){
                    $words = $row["Word"];
                }
            }

			$numLetters = strlen($words);
						
			$username = $_SESSION["username"];
			
			$guessedWord = "";
			for($i = 0; $i < $numLetters; $i++){
				$guessedWord = $guessedWord . "_ ";
			}
			        			
			$insertSQL = "INSERT INTO Scores(GameNumber, Username, NumLetters, Word, GuessedWord, Guesses, IncorrectGuesses, FinishedGame) VALUES (0,'$username',$numLetters,'$words','$guessedWord',0,0,'N')";
			$connection->query($insertSQL);
			
			$selectSQL = "SELECT MAX(GameNumber) AS GameNumber FROM Scores;";
			$gameNumber = mysqli_query($connection, $selectSQL);
			
			$_SESSION["gameNumber"] = "";

			if($gameNumber->num_rows > 0){
                while($row = $gameNumber->fetch_assoc()){
                    $_SESSION["gameNumber"] = $row["GameNumber"];
                }
            }
			
			header("Location: game.php");

		    $connection->close();

		?>

    </body>
</html>