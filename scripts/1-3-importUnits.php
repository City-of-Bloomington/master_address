<?php
	mysql_connect(":/tmp/mysql.sock","username","password") or die(mysql_error());
	mysql_select_db("master_address") or die(mysql_error());

	# All of the master places should have been imported by now.

	# Just grab all subunits.  We'll go through each one and grab all the other
	# information we need.  We have confirmed that there are no subunits with places
	# We have also confirmed that none of the places involved have multiple addresses
	$sql = "select s.*,mailable_flag,livable_flag,building_id,t.id as unitType_id
			from oldAddressData.mast_address_subunits s
			left join oldAddressData.address_location using (street_address_id,subunit_id)
			left join oldAddressData.building_address_location b using (location_id)
			left join unitTypes t on sudtype=t.type
			group by s.subunit_id";
	$subunits = mysql_query($sql) or die($sql.mysql_error());
	while($subunit = mysql_fetch_array($subunits))
	{
		# Find the master location for this subunit
		$sql = "select location_id from oldAddressData.address_location where street_address_id=$subunit[street_address_id] and subunit_id is null";
		list($place_id) = mysql_fetch_array(mysql_query($sql)) or die($sql.mysql_error());


		# If this building doesn't exist for this place, yet, add it in
		if ($subunit['building_id'])
		{
			$sql = "select building_id from building_places where place_id=$place_id and building_id=$subunit[building_id]";
			$buildings = mysql_query($sql) or die($sql.mysql_error());
			if (!mysql_num_rows($buildings))
			{
				$sql = "insert building_places set building_id=$subunit[building_id],place_id=$place_id";
				mysql_query($sql) or die($sql.mysql_error());
			}
		}
		else { $subunit['building_id'] = "null"; }



		$sql = "insert units set id=$subunit[subunit_id],place_id=$place_id,unitType_id='$subunit[unitType_id]',
				identifier='$subunit[street_subunit_identifier]',building_id=$subunit[building_id]";
		if ($subunit['mailable_flag']) { $sql.=",mailable=$subunit[mailable_flag]"; }
		if ($subunit['livable_flag']) { $sql.=",livable=$subunit[livable_flag]"; }
		if ($subunit['notes']) { $sql.=",notes='$subunit[notes]'"; }
		mysql_query($sql) or die($sql.mysql_error());
	}
?>