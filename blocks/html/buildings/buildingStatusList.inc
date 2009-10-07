<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param BuildingStatusList $this->buildingStatusList
 */
?>
<div class="interfaceBox">
	<h1>Building Statuses</h1>
	<ul><?php
			foreach ($this->buildingStatusList as $buildingStatus) {
				echo "<li>$buildingStatus</li>";
			}
		?>
	</ul>
</div>