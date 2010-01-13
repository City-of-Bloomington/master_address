<?php
/**
 * @copyright 2010 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param array $this->zipcodes
 */
$zipcodes = array();
foreach ($this->zipcodes as $zip=>$city) {
	$city = addslashes($city);
	$zipcodes[] = "{\"zip\":\"$zip\",\"city\":\"$city\"}";
}
$zipcodes = implode(',',$zipcodes);
echo "[$zipcodes]";