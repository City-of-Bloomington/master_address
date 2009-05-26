<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param StateRoadList $this->stateRoadList
 */
?>
<div class="interfaceBox">
	<h1>
		<?php
			if (userHasRole('Administrator')) {
				echo "<a class=\"add button\" href=\"".BASE_URL."/stateRoads/addStateRoad.php\">Add</a>";
			}
		?>
		State Road
	</h1>
	<table>
		<tr><th></th>
			<th>Description</th>
			<th>Abbreviation</th>
		</tr>
		<?php
			foreach ($this->stateRoadList as $stateRoad) {
				$editButton = '';
				if (userHasRole('Administrator')) {
					$url = new URL(BASE_URL.'/stateRoads/updateStateRoad.php');
					$url->stateRoad_id = $stateRoad->getId();
					$editButton = "<a class=\"edit button\" href=\"$url\">Edit</a>";
				}
				$description = View::escape($stateRoad->getDescription());
				$abbreviation = View::escape($stateRoad->getAbbreviation());
				echo "
				<tr><td>$editButton</td>
					<td>$description</td>
					<td>$abbreviation</td>
				</tr>
				";
			}
		?>
	</table>
</div>
