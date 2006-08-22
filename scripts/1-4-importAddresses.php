<?php
	mysql_connect(":/tmp/mysql.sock","username","password") or die(mysql_error());
	mysql_select_db("master_address") or die(mysql_error());

	$NO_SEGMENT_DATA = fopen("./noSegmentData.txt","w");

	# We need to go through all the places we have and bring in
	# all the different street numbers they have
	$sql = "select id from places";
	$places = mysql_query($sql) or die($sql.mysql_error());
	while(list($place_id) = mysql_fetch_array($places))
	{
		$sql = "select l.street_address_id,active,street_id,street_number,address_type,c.id as city_id,zip,zipplus4,plat_lot_number,notes
				from oldAddressData.address_location l left join oldAddressData.mast_address a using (street_address_id)
				left join cities c on city=c.name
				where location_id=$place_id";
		$addresses = mysql_query($sql) or die($sql.mysql_error());
		while($address = mysql_fetch_array($addresses))
		{
			$street_number = explode(" ",trim($address['street_number']));
			$number = $street_number[0];
			if (isset($street_number[1])) { $suffix = $street_number[1]; } else { $suffix = ""; }

			# Try and find the segment tag for this streetID and number
			$sql = "select segment_id from street_segments left join segments on segment_id=segments.id
					where street_id=$address[street_id]
					and lowAddressNumber<=$number and highAddressNumber>=$number";
			$temp = mysql_query($sql) or die("Location: $place_id\n$sql\n".mysql_error());
			if (mysql_num_rows($temp))
			{
				list($segment_id) = mysql_fetch_array(mysql_query($sql)) or die($sql.mysql_error());
			}
			else
			{
				# there's no segment data for that street
				# Look up the street name for easier reference
				$temp = "select concat_ws(' ',street_direction_code,street_name,street_type_suffix_code,post_direction_suffix_code)
						from oldAddressData.mast_street_names where street_id=$address[street_id]";
				list($street_name) = mysql_fetch_array(mysql_query($temp));

				$query = ereg_replace("[\t\n]"," ",$sql);
				fwrite($NO_SEGMENT_DATA,"$place_id|$address[street_id]|$address[street_number]|$street_name|$query\n");

				$segment_id = "null";
			}

			$sql = "insert addresses set place_id=$place_id,street_id=$address[street_id],segment_id=$segment_id,number=$number,
					addressType='$address[address_type]',city_id=$address[city_id],zip='$address[zip]',active='$address[active]'";
			if ($suffix) { $sql.=",suffix='$suffix'"; }
			if ($address['zipplus4']) { $sql.=",zipplus4=$address[zipplus4]"; }
			if ($address['notes']) { $sql.=",notes='$address[notes]'"; }

			# If there's an existing lot number, update the place with the lot number
			if ($address['plat_lot_number'])
			{
				$update = "update places set lotNumber=$address[plat_lot_number] where id=$place_id";
				mysql_query($update) or die($update.mysql_error());
			}

			# Look up the status for this address
			$temp = "select s.id as status_id,start_date,end_date from oldAddressData.mast_address_status
					left join oldAddressData.mast_address_status_lookup using (status_code)
					left join statuses s on description=status
					where street_address_id=$address[street_address_id]";
			$statusResult = mysql_query($temp) or die($sql.mysql_error());
			if (mysql_num_rows($statusResult))
			{
				list($status_id,$startDate,$endDate)  = mysql_fetch_array($statusResult);
				$sql.=",status_id=$status_id,startDate='$startDate'";
				if ($endDate) { $sql.=",endDate='$endDate'"; }
			}

			mysql_query($sql) or die($sql.mysql_error());
		}
	}
?>