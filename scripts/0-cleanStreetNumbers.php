<?php
	mysql_connect(":/tmp/mysql.sock","username","password") or die(mysql_error());
	mysql_select_db("oldAddressData") or die(mysql_error());

	$sql = "select street_address_id,street_number from mast_address where street_number regexp '[0-9]+[A-Z]'";
	$result = mysql_query($sql) or die($sql.mysql_error());
	while(list($street_address_id,$street_number) = mysql_fetch_array($result))
	{
		$street_number = preg_replace("/(\d+)(\w)/","$1 $2",$street_number);

		$sql = "update mast_address set street_number='$street_number' where street_address_id=$street_address_id";
		mysql_query($sql) or die($sql.mysql_error());
	}
?>
