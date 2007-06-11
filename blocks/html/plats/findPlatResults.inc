<?php
/*
	$this->platList
	$this->response
*/
	switch (count($this->platList))
	{
		case 0:
			echo "<p>No Plats Found.</p>";
		break;

		default:
			echo "
			<table>
			<tr><th>ID</th>
				<th>Name</th>
				<th>Township</th>
				<th>Type</th>
				<th>Cabinet</th>
				<th>Envelope</th>
			</tr>
			";
			foreach($this->platList as $plat)
			{
				$this->response->parameters['plat_id'] = $plat->getId();
				echo "
				<tr><td><a href=\"{$this->response->getURL()}\">{$plat->getId()}</a></td>
					<td><a href=\"{$this->response->getURL()}\">{$plat->getName()}</a></td>
					<td><a href=\"{$this->response->getURL()}\">{$plat->getTownship()->getName()}</a></td>
					<td><a href=\"{$this->response->getURL()}\">{$plat->getPlatType()->getType()}</a></td>
					<td><a href=\"{$this->response->getURL()}\">{$plat->getCabinet()}</a></td>
					<td><a href=\"{$this->response->getURL()}\">{$plat->getEnvelope()}</a></td>
				</tr>
				";
			}
			echo "</table>";
	}
?>