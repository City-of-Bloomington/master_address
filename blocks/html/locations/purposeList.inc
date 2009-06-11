<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param PurposeList $this->purposeList
 */
?>
<div class="interfaceBox">
	<h1><?php
			if (userHasRole('Administrator')) {
				echo "<a class=\"add button\" href=\"".BASE_URL."/locations/addPurpose.php\">Add</a>";
			}
		?>
		Purposes
	</h1>
	<table>
		<tr><th></th>
			<th>Type</th>
			<th>Description</th>
		</tr>
		<?php
			foreach ($this->purposeList as $purpose) {
				$editButton = '';
				if (userHasRole('Administrator')) {
					$url = new URL(BASE_URL.'/locations/updatePurpose.php');
					$url->purpose_id = $purpose->getId();
					$editButton = "<a class=\"edit button\" href=\"$url\">Edit</a>";
				}
				$type = View::escape($purpose->getType());
				$description = View::escape($purpose->getDescription());
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
