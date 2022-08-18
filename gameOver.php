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
			  color: red;
			  font-size: 200%;
			  padding: 0px;
			  margin: 0px;
			}
			.winner {
			  color: green;
			  font-size: 150%;
			  padding: 0px;
			  margin: 0px;
			  }
			h3 {
			  color: green;
			  font-size: 225%;
			  padding: 0px;
			  margin: 0px;
     		}
			h4 {
			  color: red;
			  font-size: 150%;
			  padding: 0px;
			  margin: 0px;
			}
			h5 {
			  color: blue;
			  font-size: 150%;
			  padding: 0px;
			  margin: 0px;
			}
			h6 {
			  color: green;
			  font-size: 150%;
			  padding: 0px;
			  margin: 0px;
			}
			.lead {
				font-size: 200%;
			}
			.leadsmall {
				font-size: 150%;
			}
			body {
			  font-family: verdana;
			  font-size: 3.5vw;
			  width: 100%;
			}
			article {
			  float: left;
			  width: 100%;
			}
			form {
			  float: left;
			  width: 80px;
			}
			.gameOver {
				float: left;
				width: 100%;
				padding-bottom: 25px;
			}
			.leaderBoard {
				float: left;
				width: 100%;
				padding-bottom: 25px;
				background-color: lightgray;
				font-size: 2vw;
			}
			@media (min-width: 450px) 
			{
				body
				{
					font-size: 1vw;
				}
			    .gameOver 
				{
					float: left;
					width: 30%;
					padding-bottom: 25px;
				}
				.leaderBoard {
					float: left;
					width: 70%;
					padding-bottom: 25px;
					background-color: lightgray;
					font-size: 1vw;
				}
			}
		</style>
	</head>
    <body>
	<section class="gameOver">
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
			
			$selectFinishedSQL = "SELECT FinishedGame FROM Scores WHERE GameNumber = $gameNumber";
			$notFinished = mysqli_query($connection, $selectFinishedSQL);
			$finished = "";
            if($notFinished->num_rows > 0){
                while($row = $notFinished->fetch_assoc()){
                    $finished = $row["FinishedGame"];
                }
            }
 
 			$selectWordGuessedSQL = "SELECT GuessedWord FROM Scores WHERE GameNumber = $gameNumber";
			$notWordGuessed = mysqli_query($connection, $selectWordGuessedSQL);
			
			$wordGuessed = "";
			if($notWordGuessed->num_rows > 0){
				while($row = $notWordGuessed->fetch_assoc()){
					$wordGuessed = $row["GuessedWord"];
				}
			}
			$wordQuery = "SELECT Word FROM Scores WHERE GameNumber = $gameNumber";
            $getWord = $connection->query($wordQuery);
            $words = "";

            if($getWord->num_rows > 0){
                while($row = $getWord->fetch_assoc()){
                    $words = $row["Word"];
                }
            }
			
			$selectGuessesSQL = "SELECT IncorrectGuesses FROM Scores WHERE GameNumber = $gameNumber";
			$notNumGuesses = mysqli_query($connection, $selectGuessesSQL);
			if($notNumGuesses->num_rows > 0){
                while($row = $notNumGuesses->fetch_assoc()){
                    $numGuesses = $row["IncorrectGuesses"];
                }
            }
			
			if($finished === 'N'){
				echo "<h2>&nbsp<u>Game Over</u></h2>";
				echo "<h4>&nbsp&nbsp&nbsp&nbsp&nbspYou lose!</h4><br>";
				echo "<h1>&nbsp&nbsp+---+</h1>";
				echo "<h1>&nbsp&nbsp&nbsp|&nbsp&nbsp&nbsp&nbsp&nbsp|</h1>";
				echo "<h1>&nbsp&nbsp&nbsp|&nbsp&nbsp&nbsp&nbspO</h1>";
				echo "<h1>&nbsp&nbsp&nbsp|&nbsp&nbsp&nbsp/-\</h1>";
				echo "<h1>&nbsp&nbsp&nbsp|&nbsp&nbsp&nbsp&nbsp&nbsp|</h1>";
				echo "<h1>&nbsp&nbsp&nbsp|&nbsp&nbsp&nbsp&nbsp/\</h1>";
				echo "<h1>=====</h1>";
				echo "<br><h4>&nbsp&nbsp" . $wordGuessed . "</h4><br>";
			}
			else {
				echo "<div class="."winner"."><h6>&nbsp<u>Game Over</u><h6></div>";
				echo "<h6>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbspYou win!</h6><br>";
				echo "<h1>&nbsp&nbsp+---+</h1>";
				echo "<h1>&nbsp&nbsp&nbsp|&nbsp&nbsp&nbsp&nbsp&nbsp|</h1>";
				echo "<h1>&nbsp&nbsp&nbsp|&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</h1>";
				echo "<h1>&nbsp&nbsp&nbsp|&nbsp&nbsp&nbsp&nbsp\O/</h1>";
				echo "<h1>&nbsp&nbsp&nbsp|&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp|</h1>";
				echo "<h1>&nbsp&nbsp&nbsp|&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp/\</h1>";
				echo "<h1>======</h1>";
			}
			echo "<h5><br>The word was <u>" . $words . "</u></h5>";

		    $connection->close();

		?>
		</section>
		<section class="leaderBoard">
		<?php
			session_start();
			$servername = "sql100.epizy.com";
			$usernames = "epiz_31813469";
			$passwords = "wp7kvg4j";
			$dbname = "epiz_31813469_HangManDB";

			$connection = new mysqli($servername, $usernames, $passwords, $dbname);
			
			$gameNumber = $_SESSION["gameNumber"];
			
			$wordLengthSQL = "SELECT NumLetters FROM Scores WHERE GameNumber = $gameNumber";
			$notLength = mysqli_query($connection, $wordLengthSQL);
            $length = "";
            if($notLength->num_rows > 0){
                while($row = $notLength->fetch_assoc()){
                    $length = $row["NumLetters"];
                }
            }
			
			$selectFinishedSQL = "SELECT FinishedGame FROM Scores WHERE GameNumber = $gameNumber";
			$notFinished = mysqli_query($connection, $selectFinishedSQL);
			$finished = "";
            if($notFinished->num_rows > 0){
                while($row = $notFinished->fetch_assoc()){
                    $finished = $row["FinishedGame"];
                }
            }
			
			echo "<div class="."lead"."><br><b>&nbsp&nbsp<u>Leaderboard</b></u></div>";
			echo "<div class="."leadsmall".">&nbsp&nbsp&nbsp".$length . " letter words</div>";
			echo "<div class="."leadsmall"."><br><hr>&nbsp&nbspPlace&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbspWord&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbspIncorrect Guesses&nbsp&nbsp&nbsp&nbsp&nbsp&nbspUsername</div>";

			$selectScoresSQL = "SELECT CONCAT('&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp',Word,'&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp',IncorrectGuesses,'&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp',Username) AS Result
								FROM Scores
								WHERE FinishedGame = 'Y'
								  AND NumLetters = $length 
								ORDER BY Guesses	
								LIMIT 10;";
			$tenScores = mysqli_query($connection, $selectScoresSQL);
			$oneScore = "";
			$i = 1;
			if($tenScores->num_rows > 0){
					while($row = $tenScores->fetch_assoc()){
						$oneScore = $row["Result"];
						echo "<hr><div class="."leadsmall".">&nbsp&nbsp".$i.". " . $oneScore . "</div>";
						$i++;
                }
			}

		?>
		</section>
		<br>
		<br>
		<article>
			<form action="logout.php">
				<input type="submit" value="Logout" />
			</form>
			<form action="createnewgame.php">
				<input type="submit" value="New Game" />
			</form>
		</article>
    </body>
</html>