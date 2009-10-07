<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param SubunitTypeList $this->subunitTypeList
 */
?>
<div class="interfaceBox">
	<h1>
		<?php
			if (userIsAllowed('SubunitType')) {
				echo "
				<button type=\"button\" class=\"add\" onclick=\"document.location.href='".BASE_URL."/subunits/addSubunitType.php';\">
					Add
				</button>
				";
			}
		?>
		Subunit Types
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
			foreach ($this->subunitTypeList as $subunitType) {
				$editButton = '';
				if (userIsAllowed('SubunitType')) {
					$url = new URL(BASE_URL.'/subunits/updateSubunitType.php');
					$url->subunitType_id = $subunitType->getId();
					$editButton = "
					<button type=\"button\" class=\"edit\" onclick=\"document.location.href='$url';\">
						Edit
					</button>
					";
				}
				$type = View::escape($subunitType->getType());
				$description = View::escape($subunitType->getDescription());
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