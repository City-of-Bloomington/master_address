<?php
	mysql_connect(":/tmp/mysql.sock","username","password") or die(mysql_error());
	mysql_select_db("master_address") or die(mysql_error());

	$sql = "select location_id,old_location_id from oldAddressData.mast_address_location_change";
	$result = mysql_query($sql) or die($sql.mysql_error());

	while(list($newID,$oldID) = mysql_fetch_array($result))
	{
		# If the old location id is already in there, just delete the new one, otherwise,
		# set the new location id back to the old one.

		#addr_location_purposes
		$sql = "select * from oldAddressData.addr_location_purposes where location_id=$oldID";
		$temp = mysql_query($sql) or die($sql.mysql_error());

		if (mysql_num_rows($temp)) { $sql = "delete from oldAddressData.addr_location_purposes where location_id=$newID"; }
		else  { $sql = "update oldAddressData.addr_location_purposes set location_id=$oldID where location_id=$newID"; }

		mysql_query($sql) or die($sql.mysql_error());

		#mast_address_assignment_hist
		$sql = "select * from oldAddressData.mast_address_assignment_hist where location_id=$oldID";
		$temp = mysql_query($sql) or die($sql.mysql_error());

		if (mysql_num_rows($temp)) { $sql = "delete from oldAddressData.mast_address_assignment_hist where location_id=$newID"; }
		else  { $sql = "update oldAddressData.mast_address_assignment_hist set location_id=$oldID where location_id=$newID"; }

		mysql_query($sql) or die($sql.mysql_error());

		#mast_address_location_status
		$sql = "select * from oldAddressData.mast_address_location_status where location_id=$oldID";
		$temp = mysql_query($sql) or die($sql.mysql_error());

		if (mysql_num_rows($temp)) { $sql = "delete from oldAddressData.mast_address_location_status where location_id=$newID"; }
		else  { $sql = "update oldAddressData.mast_address_location_status set location_id=$oldID where location_id=$newID"; }

		mysql_query($sql) or die($sql.mysql_error());

		#building_address_location
		$sql = "select * from oldAddressData.building_address_location where location_id=$oldID";
		$temp = mysql_query($sql) or die($sql.mysql_error());

		if (mysql_num_rows($temp)) { $sql = "delete from oldAddressData.building_address_location where location_id=$newID"; }
		else  { $sql = "update oldAddressData.building_address_location set location_id=$oldID where location_id=$newID"; }

		mysql_query($sql) or die($sql.mysql_error());

		#locations_classes
		$sql = "select * from oldAddressData.locations_classes where location_id=$oldID";
		$temp = mysql_query($sql) or die($sql.mysql_error());

		if (mysql_num_rows($temp)) { $sql = "delete from oldAddressData.locations_classes where location_id=$newID"; }
		else  { $sql = "update oldAddressData.locations_classes set location_id=$oldID where location_id=$newID"; }

		mysql_query($sql) or die($sql.mysql_error());

		#address_location
		$sql = "select * from oldAddressData.address_location where location_id=$oldID";
		$temp = mysql_query($sql) or die($sql.mysql_error());

		if (mysql_num_rows($temp)) { $sql = "delete from oldAddressData.address_location where location_id=$newID"; }
		else  { $sql = "update oldAddressData.address_location set location_id=$oldID where location_id=$newID"; }

		mysql_query($sql) or die($sql.mysql_error());
	}
?>
