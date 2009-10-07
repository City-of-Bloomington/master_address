<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param BuildingTypeList $this->buildingTypeList
 */
?>
<div class="interfaceBox">
	<h1>Building Types</h1>
	<ul><?php
			foreach ($this->buildingTypeList as $buildingType) {
				echo "<li>$buildingType</li>";
			}
		?>
	</ul>
</div>
