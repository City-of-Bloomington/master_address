<?php
/*
	$this->streetList
	$this->response
*/
	switch (count($this->streetList))
	{
		case 0:
			echo "<p>No Streets Found</p>";
			if (userHasRole("Administrator")) echo "<p>Do you want to <a href=\"".BASE_URL."/names\">add a new Name?</a></p>";
		break;

		default:
			echo "<table>";
			foreach($this->streetList as $street)
			{
				$this->response->parameters['street_id'] = $street->getId();
				echo "
				<tr><td><a href=\"{$this->response->getURL()}\">{$street->getId()}</a></td>
					<td><a href=\"{$this->response->getURL()}\">{$street->getFullStreetName()}</a></td></tr>
				";
			}
			echo "</table>";
	}
?>