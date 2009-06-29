<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param BuildingList $this->buildingList
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
	  <table>
		<tr><th></th>
			<th>Type</th>
			<th>GIS tag</th>
			<th>Name </th>
		    <th>Effective Start Date</th>
	   	    <th>Effective End Date</th>
			<th>Status</th>
		</tr>
		<?php
			foreach ($this->buildingList as $building) {
				$editButton = '';
				if (userHasRole('Administrator')) {
					$url = new URL(BASE_URL.'/buildings/updateBuilding.php');
					$url->building_id = $building->getBuilding_id();
					$editButton = "<a class=\"edit button\" href=\"$url\">Edit</a>";
				}
				echo
				  "<tr>
				    <td>$editButton</td>
					<td>{$building->getBuildingType()}</td>
					<td>{$building->getGis_tag()}</td>
					<td>{$building->getBuilding_name()}</td>
					<td>{$building->getEffective_start_date('n/j/Y')}</td>
					<td>{$building->getEffective_end_date('n/j/Y')}</td>
					<td>{$building->getBuildingStatus()}</td>
				   </tr>
				";
			}
		?>
	</table>
</div>
