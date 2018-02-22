<?php
/**
 * Displays the parts of an address for a given string
 *
 * Only the parts of the address that are given in the string are returned
 * Example: $string = "410 W 4th"
 * Returns: array('street_number'=>'410',
 *					'direction'=>'W',
 *					'street_name'=>'4th'
 *				)
 *
 * Example: $string = "401 N Morton St, Bloomington, IN"
 * Returns: array('street_number'=>'401',
 *					'direction'=>'N',
 *					'street_name'=>'Morton',
 *					'streetType'=>'St',
 *					'city'=>'Bloomington',
 *					'state'=>'IN'
 *				)
 *
 *
 * @copyright 2011-2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param ParseResponse $this->parse
 */
echo "<address>";
foreach ($this->parse as $key=>$value) {
	$value = parent::escape($value);
	echo "<$key>$value</$key>";
}
echo "</address>";
