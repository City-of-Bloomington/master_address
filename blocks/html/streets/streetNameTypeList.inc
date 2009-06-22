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
				echo "<a class=\"add button\" href=\"".BASE_URL."/streets/addStreetNameType.php\">Add</a>";
			}
		?>
		Street Name Types
	</h1>
	<table>
		<tr><th></th>
			<th>Type</th>
			<th>Description</th>
		</tr>
		<?php
			foreach ($this->streetNameTypeList as $nameType) {
				$editButton = '';
				if (userHasRole('Administrator')) {
					$url = new URL(BASE_URL.'/streets/updateStreetNameType.php');
					$url->id = $nameType->getId();
					$editButton = "<a class=\"edit button\" href=\"$url\">Edit</a>";
				}
				$type = View::escape($nameType->getType());
				$description = View::escape($nameType->getDescription());
				echo "
				<tr><td>$editButton</td>
					<td>$type</td>
					<td>$description</td>
				</tr>
				";
			}
		?>
	</table>
</div>