<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param BuildingList $this->buildingList
 */
?>
<div class="interfaceBox">
	<h1>Buildings</h1>
	<table>
		<thead>
			<tr><th>Type</th>
				<th>GIS tag</th>
				<th>Name </th>
				<th>Effective Start Date</th>
				<th>Effective End Date</th>
				<th>Status</th>
			</tr>
		</thead>
		<tbody>
		<?php
			foreach ($this->buildingList as $building) {
				echo "
				<tr><td>{$building->getBuildingType()}</td>
					<td>{$building->getGis_tag()}</td>
					<td>{$building->getBuilding_name()}</td>
					<td>{$building->getEffective_start_date('n/j/Y')}</td>
					<td>{$building->getEffective_end_date('n/j/Y')}</td>
					<td>{$building->getBuildingStatus()}</td>
				</tr>
				";
			}
		?>
		</tbody>
	</table>
</div>
