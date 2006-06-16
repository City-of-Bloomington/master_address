<?php
/*
	This will load street names, but only for streets that we've got segment data
*/
	mysql_connect(":/tmp/mysql.sock","username","password") or die(mysql_error());
	mysql_select_db("master_address") or die(mysql_error());

	$sql = "select mast_street.street_id,town_id,d.id as direction_id,street_name,s.id as suffix_id,
			p.id as postDirection_id,t.id as streetNameType_id
			from oldAddressData.mast_street_names m left join oldAddressData.mast_street using (street_id)
			left join directions d on m.street_direction_code=d.code
			left join suffixes s on street_type_suffix_code=s.suffix
			left join directions p on m.post_direction_suffix_code=p.code
			left join streetNameTypes t on street_name_type=t.type";
	$result = mysql_query($sql) or die($sql.mysql_error());

	while($row = mysql_fetch_array($result))
	{
		# Make sure this street exists in our data
		$sql = "select id from streets where id=$row[street_id]";
		$temp = mysql_query($sql) or die($sql.mysql_error());
		if (mysql_num_rows($temp))
		{
			$row['street_name'] = addslashes($row['street_name']);
			$sql = "select id from names where name='$row[street_name]'";
			if ($row['town_id']) { $sql.=" and town_id=$row[town_id]"; } else { $sql.=" and town_id is null"; }
			if ($row['direction_id']) { $sql.=" and direction_id=$row[direction_id]"; } else { $sql.=" and direction_id is null"; }
			if ($row['suffix_id']) { $sql.=" and suffix_id=$row[suffix_id]"; } else { $sql.=" and suffix_id is  null"; }
			if ($row['postDirection_id']) { $sql.=" and postDirection_id=$row[postDirection_id]"; } else { $sql.=" and postDirection_id is null"; }
			$temp = mysql_query($sql) or die($sql.mysql_error());


			if (mysql_num_rows($temp))
			{
				list($name_id) = mysql_fetch_array($temp);

				$sql = "insert streetNames values(null,$row[street_id],$name_id,$row[streetNameType_id])";
				mysql_query($sql) or die($sql.mysql_error());
			}
		}
	}
?>