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
				echo "<a class=\"add button\" href=\"".BASE_URL."/locations/addLocationType.php\">Add</a>";
			}
		?>
		Location Types
	</h1>
	<table>
		<tr><th></th>
			<th>Type</th>
			<th>Description</th>
		</tr>
		<?php
			foreach ($this->locationTypeList as $locationType) {
				$editButton = '';
				if (userIsAllowed('LocationType')) {
					$url = new URL(BASE_URL.'/locations/updateLocationType.php');
					$url->locationType_id = $locationType->getId();
					$editButton = "<a class=\"edit button\" href=\"$url\">Edit</a>";
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
	</table>
</div>