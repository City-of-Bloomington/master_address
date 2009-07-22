<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param PrecinctList $this->precinctList
 */
?>
<div class="interfaceBox">
	<h1>
		<?php
			if (userIsAllowed('Precinct')) {
				echo "<a class=\"add button\" href=\"".BASE_URL."/precincts/addPrecinct.php\">Add</a>";
			}
		?>
		Precincts
	</h1>
	<table>
		<tr><th></th>
			<th>Code</th>
			<th>Name</th>
			<th>Active</th>
		</tr>
		<?php
			foreach ($this->precinctList as $precinct) {
				$editButton = '';
				if (userIsAllowed('Precinct')) {
					$url = new URL(BASE_URL.'/precincts/updatePrecinct.php');
					$url->precinct_id = $precinct->getId();
					$editButton = "<a class=\"edit button\" href=\"$url\">Edit</a>";
				}
				$code = View::escape($precinct->getCode());
				$name = View::escape($precinct->getName());
				$active = View::escape($precinct->getActive());
				echo "
				<tr><td>$editButton</td>
					<td>$code</td>
					<td>$name</td>
					<td>$active</td>
				</tr>
				";
			}
		?>
	</table>
</div>