<?php
/*
	$this->streetNameList
	$this->response
*/
	switch (count($this->streetNameList))
	{
		case 0:
			echo "<p>No Streets Found</p>";
			if (userHasRole("Administrator")) echo "<p>Do you want to <a href=\"".BASE_URL."/names\">add a new Name?</a></p>";
		break;

		default:
			echo "<table>";
			foreach($this->streetNameList as $streetName)
			{
				$this->response->parameters['street_id'] = $streetName->getStreet_id();
				echo "
				<tr><td><a href=\"{$this->response->getURL()}\">{$streetName->getStreet_id()}</a></td>
					<td><a href=\"{$this->response->getURL()}\">$streetName</a></td>
					<td>{$streetName->getTown()}</td>
					<td>{$streetName->getStreetNameType()}</td>
				</tr>
				";
			}
			echo "</table>";
	}
?>