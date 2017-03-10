<meta charset="utf8" />
<?php
include('../config/config.define.php');
include('../config/config.bdd.php');
include('../includes/function/function.global.php');

$sqlGetLog = $bdd->query("SELECT
								*
							FROM
								log
							ORDER BY
								id
							DESC
							");

echo '<table>';
	echo '<thead>';
		echo '<tr>';
			echo '<th>ID</th>';
			echo '<th>Log</th>';
			echo '<th>Personnage</th>';
			echo '<th>Membre</th>';
			echo '<th>Page</th>';
			echo '<th>Line</th>';
			echo '<th>Date</th>';
		echo '</tr>';
	echo '</thead>';

	echo '<tbody>';
		while ($Log = $sqlGetLog->fetch())
		{
			echo '<tr>';
				echo '<td>'.$Log['id'].'</td>';
				echo '<td>'.$Log['log'].'</td>';
				echo '<td>'.$Log['nom_personnage'].'</td>';
				echo '<td>'.$Log['nom_membre'].'</td>';
				echo '<td>'.$Log['file'].'</td>';
				echo '<td>'.$Log['ligne'].'</td>';
				echo '<td>'.mepd($Log['date']).'</td>';
			echo '</tr>';
		}
	echo '</tbody>';
echo '</table>';
?>