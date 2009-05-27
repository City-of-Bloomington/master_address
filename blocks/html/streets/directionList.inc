<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
?>
<div class="interfaceBox">
	<h1>Directions</h1>
	<table>
		<tr><th></th>
			<th>Code</th>
			<th>Description</th>
		</tr>
		<?php
			foreach ($this->directionList as $direction) {
				$editButton = '';
				if (userHasRole('Administrator')) {
					$url = new URL(BASE_URL.'/streets/updateDirection.php');
					$url->direction_code = $direction->getCode();
					$editButton = "<a class=\"edit button\" href=\"$url\">Edit</a>";
				}
				$description = View::escape($direction->getDescription());
				echo "
				<tr><td>$editButton</td>
					<td>{$direction->getCode()}</td>
					<td>$description</td>
				</tr>
				";
			}
		?>
	</table>
</div>
