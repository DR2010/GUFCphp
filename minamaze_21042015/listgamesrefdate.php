<?php
	/*
	Template Name: Referee Date
	*/
	get_header();

	// ----------------------
	// LOCAL values!!! Replace in production
	// ----------------------

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
		<style>
		table, th, td {
			border: 1px solid black;
		}
		</style>
	
		<script language=JavaScript>
			function reload(form)
			{
				var val=form.refereeselected.options[form.refereeselected.options.selectedIndex].value;
				self.location='?page_id=174&refereeselected=' + val ;
			}

			function findbydate(form)
			{
				var referee=form.refereeselected.options[form.refereeselected.options.selectedIndex].value;
				var date=document.getElementById('searchDate'); 
				self.location='?page_id=174&refereeselected=' + referee + '&dategame=' + date.value ;
			}
			
			
		</script>        
		<meta charset="utf-8" />
        <title>Where is my team playing?</title>
    </head>
    <body>

		<?php

			// Get Age Group
			// -------------
			@$refereeselected=$_GET['refereeselected']; 
			@$searchDate=$_GET['dategame']; 
			
			$sql_referee="select idreferee, name from referee order by idreferee;";

			$query_referee = $mysqli->query($sql_referee);
			
			echo "<form method=post name=f1 action=display()>";

			echo "<p/>";
			echo "<p>Referee:</p> ";
			echo '<label>';
			echo "<select name='refereeselected'>";
			echo '<Option value="">Select Referee</option>';		
			while ($rowinner = mysqli_fetch_assoc($query_referee))
			{
				if ($rowinner['idreferee']==@$refereeselected)
				{
					echo "<option selected value='$rowinner[idreferee]'>$rowinner[idreferee]</option>"."<BR>";
				}
				else
				{
					echo '<Option value="'.$rowinner['idreferee'].'">'.$rowinner['idreferee'].'</option>';	
				}
			}
			echo '</select>';
			echo '</label>';
			echo '<p/>';
			echo '<p/>';
			echo 'Date:';
			echo '<p/>';
			echo "<input type='date' name='dategame' value='".$searchDate."' id=\"searchDate\">";
			echo '<p/>';

			echo "<input type='button' value='Submit' onclick=\"findbydate(this.form)\">";
			
			
			echo "</form>";

			if (!$refereeselected == "")
			{
				listGames($refereeselected, $searchDate ); 
			}
		?>
    </body>
</html>

<?php 
do_action('generate_sidebars');
get_footer();

?>

<?php

function listGames( $refereeselected, $searchDate )
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

		$term  = $refereeselected;
		
		$sqlinner = "
					SELECT 
					 game.gameid 
					,game.fkhometeamid      fkhometeamid
					,game.fkawayteamid      fkawayteamid
					,game.fkagegroupid      fkagegroupid
					,game.fkroundid         fkroundid
					,game.fkgroundplaceid   fkgroundplaceid
					,game.referee   		referee
					,game.homejob   		homejob
					,game.time      		time
					,round.idround          idround
					,round.date             rounddate
					,groundplace.navigate   gpnavigate
					,groundplace.address 	gpaddress 
					,harrisonsfieldschema.fieldid fieldid
					,harrisonsfieldschema.imagelocation locationinfield

				 FROM game, round, groundplace, harrisonsfieldschema
				WHERE 
    				   game.referee = '".$term."'
				   AND game.fkgroundplaceid = groundplace.idgroundplace 
				   AND game.fkroundid = round.idround 
				   AND game.fkfieldid = harrisonsfieldschema.fieldid
				";
				
		if (! $searchDate == "")
		{
			$sqlinner.=" AND round.date = '".$searchDate."'";
		}		
		$sqlinner.=" ORDER BY round.date ";
					
		$r_queryinner = $mysqli->query($sqlinner);

		$todays_date = date("Y-m-d");						

		$msg = 'No games found.';	

		echo '<table class="table" align="center" border="1" >';
		echo '<th>Age group</th>';
		echo '<th>Date</th>';
		echo '<th>Time</th>';
		echo '<th>Round</th>';
		echo '<th>Field</th>';
		echo '<th>Home</th>';
		echo '<th>Away</th>';
		echo '<th>Referee</th>';
		echo '<th>Home Job</th>';
		echo '<th>Ground Address</th>';

		if ( ! $r_queryinner )
		{
			echo 'No games found for '.$refereeselected.' '; 
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
				echo '<td>'.$rowinner['fieldid'].'</td>';
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

