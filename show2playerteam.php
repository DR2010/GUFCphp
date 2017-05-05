<?php
	/*
	Template Name: Show 2 Player Team
	*/
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
				var val=form.agegroupselected.options[form.agegroupselected.options.selectedIndex].value;
				self.location='?page_id=489' ;
			}
		</script>     
		<meta charset="utf-8" />
        <title>Where is my team playing?</title>
    </head>
    <body>

		<?php

			echo "<form method=post name=f1 action=''>";

			// echo "The team list has been updated on 29 March 2015 at 11:05PM.";
			echo "The team list has been updated on Monday 06 April 2015 at 06:20PM. (Previously updated on 29/03/15 11:05PM)";
			// echo "This option is under maintenance 06 April 2015 at 05:15PM. It will be back shortly.";
			echo "<p/>";

			echo "<p>FFA Number:</p> ";
			echo '<input type="text" name="ffanumber">';
			echo '<p/>';
			echo "<p>Last Name:</p> ";
			echo '<input type="text" name="lastname">';
			echo '<p/>';
			echo "<p/>";
			echo "<input type=submit value=Submit>";

			echo "</form>";

			$ffanumber = $_POST['ffanumber'];
			$lastname = $_POST['lastname'];
			
			// echo $ffanumber; 
			// echo $lastname; 
			
			if ($ffanumber != "")
			{
				showteam($ffanumber, $lastname); 
			}
			
		?>
    </body>
</html>

<?php 
do_action('generate_sidebars');
get_footer();

?>

<?php

function showteam( $ffanumber, $lastname )
{


	// This is the official connection string
	// $db_hostname = 'gungahlinunitedfc.org.au';

	$db_hostname = 'ub007lcs13.cbr.the-server.net.au';
	$db_username = 'gufcweb_dev';
	$db_password = 'deve!oper';
	$db_database = 'gufcweb_player';

	echo "Inside the function. P1";

	$mysqli = new mysqli($db_hostname,$db_username,$db_password, $db_database);

	echo 'inside showteam';
	echo "<p/>";
	echo $ffanumber;
	echo "<p/>";
	echo $lastname;
	
	$lastnameUpper = strtoupper( $lastname );
	$playerteam = "";
	
	$sqlplayer = "SELECT FirstName, LastName, fkteamid, fkagegroupid  FROM player where FFANumber = ".$ffanumber." and display = 'Y' ";
	// echo $sqlplayer;
	
	$qplayer = $mysqli->query($sqlplayer);
	$namematch = "NO";
	$firstname = "";
	$lastnameDB = "";
	$fkteamid = "";
	$fkagegroupid = "";
	$tbateam = "";


	echo "Inside the function. P2";


}
?>

