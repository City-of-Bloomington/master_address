<?php
/*
	AJAX support script.
	Produces street data in JSON format.

	$_GET variables:	name[	direction_id
								name
								suffix_id
								postDirection_id
								town_id
							]
*/
	$search = array();
	foreach($_GET['name'] as $field=>$value) { if ($value) { $search[$field] = $value; } }
	if (count($search))
	{
		$streetList = new StreetList($search);
		switch (count($streetList))
		{
			case 0:
				echo "null";
			break;

			default:
				$results = array();
				foreach($streetList as $street) { $results[] = "{\"id\":\"{$street->getId()}\",\"name\":\"{$street->getFullStreetName()}\"}"; }
				$results = implode(",",$results);
				echo "[$results]";
		}
	}
?>