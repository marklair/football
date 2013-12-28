<?php
ini_set('display_errors',1); 
 error_reporting(E_ALL);

require('includes/application_top.php');

$weekStats = array();
$playerTotals = array();
$possibleScoreTotal = 0;
calculateStats();


include('includes/header.php');
?>
<h1>Standings</h1>
<h2>Weekly Stats</h2>
<table cellpadding="4" cellspacing="0" class="table1">
	<tr><th align="left">Week</th><th align="left">Winner / Monday Night Hopefuls</th><th>Score</th><th>Winner</th><th>Points</th></tr>
<?php
//print_r($weekStats);

if (isset($weekStats)) {
	$i = 0;
	$mondayNightHopefuls = array();
	$weekwinner = array();
	$weekWinningPoints = 0;
	
	//print_r($weekStats);

	// loops through weeks and sets the hopefuls names in the varialbe 
	foreach($weekStats as $week => $stats) {
		$hopefuls = '';
		unset($mondayNightHopefuls);
		$mondayNightHopefuls = array();
		
		foreach($stats[hopefuls] as $index => $hopefulID) {
			//print_r($stats);
			//$mondayNightHopefuls ;

			array_push($mondayNightHopefuls, $hopefulID);

			$tmpUser = $login->get_user_by_id($hopefulID);

			switch ($user_names_display) {
				case 1:
					$hopefuls .= ((strlen($hopefuls) > 0) ? ', ' : '') . trim($tmpUser->firstname . ' ' . $tmpUser->lastname);
					//$aHopefuls => $tmpUser->userID;
					break;
				case 2:
					$hopefuls .= ((strlen($hopefuls) > 0) ? ', ' : '') . $tmpUser->userName;
					//$aHopefuls => $tmpUser->userID;
					break;
				default: //3
					$hopefuls .= ((strlen($hopefuls) > 0) ? ', ' : '') . '<abbrev title="' . trim($tmpUser->firstname . ' ' . $tmpUser->lastname) . ' - ' . $stats[pointspicked] . '">' . $tmpUser->userName . '</abbrev>';
					//$aHopefuls => $tmpUser->userID;
					break;
			}
		}

		$weekWinner = getWeekWinner($week, $mondayNightHopefuls);
		$weekWinningPoints = getWeekWinner($week, $mondayNightHopefuls, points);
		$tmpUser = $login->get_user_by_id($weekWinner);


		//echo ($weekWinner);	
		//echo "<br><br>";
		//print_r($mondayNightHopefuls);



		// prints the row
		$rowclass = (($i % 2 == 0) ? ' class="altrow"' : '');
		echo '	<tr' . $rowclass . '><td>' . $week . '</td><td>' . $hopefuls . '</td><td align="center">' . $stats[highestScore] . '/' . $stats[possibleScore] . '</td><td>' . $tmpUser->userName . '</td><td>' . ($weekWinningPoints == 0) ? "N/A":$weekWinningPoints .'</td></tr>';
		$i++;
	}
	//print_r($mondayNightHopefuls);	


} else {
	echo '	<tr><td colspan="3">No weeks have been completed yet.</td></tr>' . "\n";
}
?>
</table>
<p>&nbsp;</p>

<h2>User Stats</h2>
<table cellpadding="0" cellspacing="0">
	<tr valign="top">
		<td>
			<b>By Name</b><br />
			<table cellpadding="4" cellspacing="0" class="table1">
				<tr><th align="left">Player</th><th align="left">Wins</th><th>Pick Ratio</th></tr>
			<?php
			if (isset($playerTotals)) {
				//arsort($playerTotals);
				$i = 0;
				foreach($playerTotals as $playerID => $stats) {
					$rowclass = (($i % 2 == 0) ? ' class="altrow"' : '');
					$pickRatio = $stats[score] . '/' . $possibleScoreTotal;
					$pickPercentage = number_format((($stats[score] / $possibleScoreTotal) * 100), 2) . '%';
					switch ($user_names_display) {
						case 1:
							echo '	<tr' . $rowclass . '><td class="tiny">' . $stats[name] . '</td><td class="tiny" align="center">' . $stats[wins] . '</td><td class="tiny" align="center">' . $pickRatio . ' (' . $pickPercentage . ')</td></tr>';
							break;
						case 2:
							echo '	<tr' . $rowclass . '><td class="tiny">' . $stats[userName] . '</td><td class="tiny" align="center">' . $stats[wins] . '</td><td class="tiny" align="center">' . $pickRatio . ' (' . $pickPercentage . ')</td></tr>';
							break;
						default: //3
							echo '	<tr' . $rowclass . '><td class="tiny"><abbrev title="' . $stats[name] . '">' . $stats[userName] . '<abbrev></td><td class="tiny" align="center">' . $stats[wins] . '</td><td class="tiny" align="center">' . $pickRatio . ' (' . $pickPercentage . ')</td></tr>';
							break;
					}
					$i++;
				}
			} else {
				echo '	<tr><td colspan="3">No weeks have been completed yet.</td></tr>' . "\n";
			}
			?>
			</table>
		</td>
		<td>&nbsp;</td>
		<td>
			<b>By Wins</b><br />
			<table cellpadding="4" cellspacing="0" class="table1">
				<tr><th align="left">Player</th><th align="left">Wins</th><th>Pick Ratio</th></tr>
			<?php
			if (isset($playerTotals)) {
				arsort($playerTotals);
				$i = 0;
				foreach($playerTotals as $playerID => $stats) {
					$rowclass = (($i % 2 == 0) ? ' class="altrow"' : '');
					$pickRatio = $stats[score] . '/' . $possibleScoreTotal;
					$pickPercentage = number_format((($stats[score] / $possibleScoreTotal) * 100), 2) . '%';
					switch ($user_names_display) {
						case 1:
							echo '	<tr' . $rowclass . '><td class="tiny">' . $stats[name] . '</td><td class="tiny" align="center">' . $stats[wins] . '</td><td class="tiny" align="center">' . $pickRatio . ' (' . $pickPercentage . ')</td></tr>';
							break;
						case 2:
							echo '	<tr' . $rowclass . '><td class="tiny">' . $stats[userName] . '</td><td class="tiny" align="center">' . $stats[wins] . '</td><td class="tiny" align="center">' . $pickRatio . ' (' . $pickPercentage . ')</td></tr>';
							break;
						default: //3
							echo '	<tr' . $rowclass . '><td class="tiny"><abbrev title="' . $stats[name] . '">' . $stats[userName] . '</abbrev></td><td class="tiny" align="center">' . $stats[wins] . '</td><td class="tiny" align="center">' . $pickRatio . ' (' . $pickPercentage . ')</td></tr>';
							break;
					}
					$i++;
				}
			} else {
				echo '	<tr><td colspan="3">No weeks have been completed yet.</td></tr>' . "\n";
			}
			?>
			</table>
		</td>
		<td>&nbsp;</td>
		<td>
			<b>By Pick Ratio</b><br />
			<table cellpadding="4" cellspacing="0" class="table1">
				<tr><th align="left">Player</th><th align="left">Wins</th><th>Pick Ratio</th></tr>
			<?php
			if (isset($playerTotals)) {
				$playerTotals = sort2d($playerTotals, 'score', 'desc');
				$i = 0;
				foreach($playerTotals as $playerID => $stats) {
					$rowclass = (($i % 2 == 0) ? ' class="altrow"' : '');
					$pickRatio = $stats[score] . '/' . $possibleScoreTotal;
					$pickPercentage = number_format((($stats[score] / $possibleScoreTotal) * 100), 2) . '%';
					switch ($user_names_display) {
						case 1:
							echo '	<tr' . $rowclass . '><td class="tiny">' . $stats[name] . '</td><td class="tiny" align="center">' . $stats[wins] . '</td><td class="tiny" align="center">' . $pickRatio . ' (' . $pickPercentage . ')</td></tr>';
							break;
						case 2:
							echo '	<tr' . $rowclass . '><td class="tiny">' . $stats[userName] . '</td><td class="tiny" align="center">' . $stats[wins] . '</td><td class="tiny" align="center">' . $pickRatio . ' (' . $pickPercentage . ')</td></tr>';
							break;
						default: //3
							echo '	<tr' . $rowclass . '><td class="tiny"><abbrev title="' . $stats[name] . '">' . $stats[userName] . '</abbrev></td><td class="tiny" align="center">' . $stats[wins] . '</td><td class="tiny" align="center">' . $pickRatio . ' (' . $pickPercentage . ')</td></tr>';
							break;
					}
					$i++;
				}
			} else {
				echo '	<tr><td colspan="3">No weeks have been completed yet.</td></tr>' . "\n";
			}
			?>
			</table>
		</td>
	</tr>
</table>
<p>&nbsp;</p>
<?php
include('includes/footer.php');
?>