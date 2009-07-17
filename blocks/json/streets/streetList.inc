<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param StreetList $this->streetList
 */
$streets = array();
foreach ($this->streetList as $street) {
	$name = addslashes($street->getStreetName());
	$town = addslashes($street->getTown()->getDescription());
	$notes = addslashes($street->getNotes());
	$string = <<<EOD
	{
		"id":"{$street->getId()}",
		"direction":"{$street->getDirection()}",
		"name":"$name",
		"postDirection":"{$street->getPostDirection()}",
		"town":"$town",
		"status":"{$street->getStatus_code()}",
		"notes":"$notes"
	}
EOD;
	$streets[] = $string;
}
$streets = implode(",\n",$streets);
echo '{"streets":['.$streets.']}';
