<?php
/*
	AJAX Support script
	Produces Name information in JSON format

	$_GET variables:	name[	direction_id		town_id
								name
								suffix_id
								postDirection_id
							]
*/
	$search = array();
	foreach($_GET['name'] as $field=>$value) { if ($value) { $search[$field] = $value; } }
	if (count($search))
	{
		$nameList = new NameList($search);
		switch (count($nameList))
		{
			case 0:
				echo "null";
			break;

			default:
				$results = array();
				foreach($nameList as $name) { $results[] = "{\"id\":\"{$name->getId()}\",\"name\":\"{$name->getFullname()}\"}"; }
				$results = implode(",",$results);
				echo "[$results]";
		}
	}
?>