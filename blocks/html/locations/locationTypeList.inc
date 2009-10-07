<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param LocationTypeList $this->locationTypeList
 */
?>
<div class="interfaceBox">
	<h1><?php
			if (userIsAllowed('LocationType')) {
				echo "
				<button type=\"button\" class=\"add\" onclick=\"document.location.href='".BASE_URL."/locations/addLocationType.php';\">
					Add
				</button>
				";
			}
		?>
		Location Types
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
			foreach ($this->locationTypeList as $locationType) {
				$editButton = '';
				if (userIsAllowed('LocationType')) {
					$url = new URL(BASE_URL.'/locations/updateLocationType.php');
					$url->locationType_id = $locationType->getId();
					$editButton = "
					<button type=\"button\" class=\"edit\" onclick=\"document.location.href='$url';\">
						Edit
					</button>
					";
				}
				$type = View::escape($locationType->getId());
				$description = View::escape($locationType->getDescription());
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