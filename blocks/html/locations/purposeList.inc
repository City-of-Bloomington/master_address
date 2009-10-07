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
			if (userIsAllowed('Purpose')) {
				echo "
				<button type=\"button\" class=\"add\" onclick=\"document.location.href='".BASE_URL."/locations/addPurpose.php';\">
					Add
				</button>
				";
			}
		?>
		Purposes
	</h1>
	<table>
		<thead>
			<tr><th></th>
				<th>Type</th>
				<th>Description</th>
			</tr>
		</thead>
		<tbody>
		<?php
			foreach ($this->purposeList as $purpose) {
				$editButton = '';
				if (userIsAllowed('Purpose')) {
					$url = new URL(BASE_URL.'/locations/updatePurpose.php');
					$url->purpose_id = $purpose->getId();
					$editButton = "
					<button type=\"button\" class=\"edit\" onclick=\"document.location.href='$url';\">
						Edit
					</button>
					";
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
		</tbody>
	</table>
</div>
