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
 * @copyright 2011 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param array $this->address
 */
echo json_encode($this->address);
