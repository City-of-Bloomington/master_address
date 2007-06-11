<?php
/*
	$this->buildingList
	$this->response
*/
	switch (count($this->buildingList))
	{
		case 0:
			echo "<p>No Buildings Found</p>";
		break;

		default:
			echo "
			<table>
			<tr><th>ID</th>
				<th>Tag</th>
				<th>Name</th>
			</tr>
			";
			foreach($this->buildingList as $building)
			{
				$this->response->parameters['building_id'] = $building->getId();
				echo "
				<tr><td><a href=\"{$this->response->getURL()}\">{$building->getId()}</a></td>
					<td><a href=\"{$this->response->getURL()}\">{$building->getGISTag()}</a></td>
					<td><a href=\"{$this->response->getURL()}\">{$building->getName()}</a></td>
				</tr>
				";
			}
			echo "</table>";
	}
?>