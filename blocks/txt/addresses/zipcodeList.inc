<?php
/**
 * @copyright 2010 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param array $this->zipcodes
 */
foreach ($this->zipcodes as $zip=>$city) {
	$city = View::escape($city);
	echo "$zip|$city\n";
}
