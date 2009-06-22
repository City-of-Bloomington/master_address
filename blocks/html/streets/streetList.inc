<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
?>
<div class="interfaceBox">
	<h1>
		<?php
			if (userHasRole('Administrator')) {
				echo "<a class=\"add button\" href=\"".BASE_URL."/streets/addStreet.php\">Add</a>";
			}
		?>
	    Streets
	</h1>
	<table>
		<tr>
		    <th></th>
		    <th>Direction</th>
		    <th>Post Direction</th>
		    <th>Town</th>
		    <th>Status</th>
		    <th>Notes</th>
		</tr>
		<?php
			foreach ($this->streetList as $street) {
				$editButton = '';
				if (userHasRole('Administrator')) {
					$url = new URL(BASE_URL.'/streets/updateStreet.php');
					$url->street_id = $street->getStreet_id();
					$editButton = "<a class=\"edit button\" href=\"$url\">Edit</a>";
				}
				$notes = View::escape($street->getNotes());
				echo "
				<tr>
					<td>$editButton</td>
					<td>{$street->getDirection()->getDescription()}</td>
					<td>{$street->getPostDirection()->getDescription()}</td>
					<td>{$street->getTown()->getDescription()}</td>
					<td>{$street->getStatus()->getDescription()}</td>
					<td>$notes</td>
				</tr>
				";
			}
		?>
	</table>
</div>