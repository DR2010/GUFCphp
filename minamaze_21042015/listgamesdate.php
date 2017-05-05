<?php
	/*
	Template Name: Games on Date
	*/
	get_header();

	// ----------------------
	// LOCAL values!!! Replace in production
	// ----------------------

	// $db_hostname = 'localhost:3308';
	// $db_username = 'root';
	// $db_password = 'oculos';
	// $db_database = 'gufcdraws';
	
	// This is the official connection string
	$db_hostname = 'gungahlinunitedfc.org.au';
	$db_username = 'gufcweb_dev';
	$db_password = 'deve!oper';
	$db_database = 'gufcweb_player';

	// Ubuntu Hyper V
	// $db_hostname = '192.168.1.12:3306';
	// $db_username = 'danielgufc_user';
	// $db_password = 'danielgufc_password';
	// $db_database = 'gufcdraws';

	$mysqli = new mysqli($db_hostname,$db_username,$db_password, $db_database);

	/* check connection */
	if ($mysqli ->connect_errno) {
		printf("Connect failed: %s\n", $mysqli->connect_error);
		exit();
	}
	
?>

<!DOCTYPE html>
<html lang="en">
    <head>
		<script type=text/javaScript>
			function reload(form)
			{
				var val=document.getElementById('searchDate'); 
				// window.alert(" Date... " + val.value);
				self.location='?page_id=168&dategame=' + val.value ;
			}

		</script>        

		<style>
			table, th, td {
				border: 1px solid black;
			}
		</style>
			
		<meta charset="utf-8" />
        <title>Where is my team playing?</title>
    </head>
    <body>

    	<?php
			@$dategame=$_GET['dategame']; 

			echo "<form method='POST' name='formname' action=''>";
			echo '<br>';
			echo 'Date:';
			echo '<p/>';
			echo "<input type='date' name='dategame' id='searchDate' value='".$dategame."' id=\"searchDate\">";
			echo '<p/>';
			echo "<input type='button' value='Submit' onclick=\"reload(this.form)\">";
			echo "</form>";
			
 			if (! $dategame == "")
			{
				listGames( $dategame ); 
			}
		
		?>

    </body>
</html>

<?php 
do_action('generate_sidebars');
get_footer();

?>

<?php

function listGames( $dategame )
{

		$db_hostname = 'gungahlinunitedfc.org.au';
		$db_username = 'gufcweb_dev';
		$db_password = 'deve!oper';
		$db_database = 'gufcweb_player';

		// $db_hostname = '192.168.1.12:3306';
		// $db_username = 'danielgufc_user';
		// $db_password = 'danielgufc_password';
		// $db_database = 'gufcdraws';
    	$mysqli = new mysqli($db_hostname,$db_username,$db_password, $db_database);
		
		$sqlinner = "
					SELECT 
					 game.gameid 
					,game.fkhometeamid      fkhometeamid
					,game.fkawayteamid      fkawayteamid
					,game.fkagegroupid      fkagegroupid
					,game.fkroundid         fkroundid
					,game.fkgroundplaceid   fkgroundplaceid
					,game.referee   referee
					,game.homejob   homejob
					,game.time      time
					,round.idround          idround
					,round.date             rounddate
					,groundplace.navigate   gpnavigate
					,groundplace.address 	gpaddress 
					,harrisonsfieldschema.fieldid fieldid
					,harrisonsfieldschema.imagelocation locationinfield
					,case 
						when ( round.date >= SUBDATE(now(), INTERVAL 1 DAY) and date(round.date) < ADDDATE( now(), INTERVAL 6 DAY)) then '  <<<< NEXT GAME >>>>'
						else ''
					end as 'next'
				 FROM game, round, groundplace, harrisonsfieldschema
				WHERE 
				       game.fkgroundplaceid = groundplace.idgroundplace 
				   AND game.fkroundid = round.idround 
				   AND game.fkfieldid = harrisonsfieldschema.fieldid
				   AND round.date = '".$dategame."'
				ORDER BY round.date
				";
					
		$r_queryinner = $mysqli->query($sqlinner);

		$todays_date = date("Y-m-d");						

		echo '<table class="table" align="center" border="1" >';
		echo '<th>Age group</th>';
		echo '<th>Next Game</th>';
		echo '<th>Time</th>';
		echo '<th>Round</th>';
		echo '<th>Home</th>';
		echo '<th>Away</th>';
		echo '<th>Referee</th>';
		echo '<th>Home Job</th>';
		echo '<th>Ground Address</th>';

		if ( ! $r_queryinner )
		{
			echo 'No games found on date '.$dategame.' '; 
		}
		else
		{
			while ($rowinner = mysqli_fetch_assoc($r_queryinner))
			{	
				
				echo '<tr>';
				echo '<td>'.$rowinner['fkagegroupid'].'</td>';
				echo '<td>'.$rowinner['rounddate'].'</td>';
				echo '<td>'.$rowinner['time'].'</td>';
				echo '<td>'.$rowinner['idround'].'</td>';
				echo '<td>'.$rowinner['fkhometeamid'].'</td>';
				echo '<td>'.$rowinner['fkawayteamid'].'</td>';
				echo '<td>'.$rowinner['referee'].'</td>';
				echo '<td>'.$rowinner['homejob'].'</td>';
				echo '<td>'.$rowinner['gpaddress'].'</td>';
				echo '</tr>';

			}	

			echo '</table>';
			echo '<p/>';
		
		}
}
?>

