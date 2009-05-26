<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param PrecinctList $this->precinctList
 */
?>
<div class="interfaceBox">
	<h1>Precincts</h1>
	<table>
		<tr><th></th>
			<th>Code</th>
			<th>Name</th>
			<th>Active</th>
		</tr>
		<?php
			foreach ($this->precinctList as $precinct) {
				$editButton = '';
				if (userHasRole('Administrator')) {
					$url = new URL(BASE_URL.'/precincts/updatePrecinct.php');
					$url->code = $precinct->getCode();
					$editButton = "<a class=\"edit button\" href=\"$url\">Edit</a>";
				}
				$code = View::escape($precinct->getCode());
				$name = View::escape($precinct->getName());
				echo "
				<tr><td>$editButton</td>
					<td>$code</td>
					<td>$name</td>
					<td>{$precinct->getActive()}</td>
				</tr>
				";
			}
		?>
	</table>
</div>
