<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param TownshipList $this->townshipList
 */
?>
<div class="interfaceBox">
	<h1><?php
			if (userIsAllowed('Township')) {
				echo "<a class=\"add button\" href=\"".BASE_URL."/townships/addTownship.php\">Add</a>";
			}
		?>
		Townships
	</h1>
	<table>
		<tr><th></th>
			<th>Name</th>
			<th>Abbreviation</th>
			<th>Quarter</th>
		</tr>
		<?php
			foreach ($this->townshipList as $township) {
				$editButton = '';
				if (userIsAllowed('Township')) {
					$url = new URL(BASE_URL.'/townships/updateTownship.php');
					$url->township_id = $township->getTownship_id();
					$editButton = "<a class=\"edit button\" href=\"$url\">Edit</a>";
				}
				$name = View::escape($township->getName());
				$abbreviation = View::escape($township->getAbbreviation());
				$quarter = View::escape($township->getQuarter_code());
				echo "
				<tr><td>$editButton</td>
					<td>$name</td>
					<td>$abbreviation</td>
					<td>$quarter</td>
				</tr>
				";
			}
		?>
	</table>
</div>
