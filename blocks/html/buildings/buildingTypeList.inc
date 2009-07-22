<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param BuildingTypeList $this->buildingTypeList
 */
?>
<div class="interfaceBox">
	<h1>
		<?php
			if (userIsAllowed('BuildingType')) {
				echo "<a class=\"add button\" href=\"".BASE_URL."/buildings/addBuildingType.php\">Add</a>";
			}
		?>
		Building Types
	</h1>
	<ul><?php
			foreach ($this->buildingTypeList as $buildingType) {
				$editButton = '';
				if (userIsAllowed('BuildingType')) {
					$url = new URL(BASE_URL.'/buildings/updateBuildingType.php');
					$url->building_type_id = $buildingType->getId();
					$editButton = "<a class=\"edit button\" href=\"$url\">Edit</a>";
				}
				echo "<li>$editButton $buildingType</li>";
			}
		?>
	</ul>
</div>
