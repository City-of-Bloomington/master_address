<?php
/*
	$this->nameList
	$this->response
*/
	switch (count($this->nameList))
	{
		case 0:
			echo "<p>No Names Found.</p>";
		break;

		default:
			echo "
			<table>
			<tr><th>ID</th><th>Name</th></tr>
			";
			foreach($this->nameList as $name)
			{
				$this->response->parameters['name_id'] = $name->getId();
				echo "
				<tr><td><a href=\"{$this->response->getURL()}\">{$name->getId()}</a></td>
					<td><a href=\"{$this->response->getURL()}\">{$name->getFullname()}</a></td>
				</tr>
				";
			}
			echo "</table>";
	}
?>