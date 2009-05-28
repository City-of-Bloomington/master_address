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
				echo "<a class=\"add button\" href=\"".BASE_URL."/buildings/addBuilding.php\">Add</a>";
			}
		?>
		Buildings
	</h1>
	<ul><?php
			foreach ($this->buildingList as $building) {
				$editButton = '';
				if (userHasRole('Administrator')) {
					$url = new URL(BASE_URL.'/buildings/updateBuilding.php');
					$url->building_id = $building->getBuilding_id();
					$editButton = "<a class=\"edit button\" href=\"$url\">Edit</a>";
				}
				echo "<li>$editButton $building</li>";
			}
		?>
	</ul>
</div>