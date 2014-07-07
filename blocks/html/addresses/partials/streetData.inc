<?php
/**
 * @copyright 2009-2014 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Address $this->address
 */
$fields = [
	'street_number_prefix', 'street_number', 'street_number_suffix', 'street',
	'city', 'zip', 'zipplus4', 'status'
];
foreach ($fields as $f) {
	$get = 'get'.ucfirst($f);
	$$f = View::escape($this->address->$get());
}
echo "
	<tr><th>Prefix</th>
		<td>$street_number_prefix</td>
	</tr>
	<tr><th>Number</th>
		<td>$street_number</td>
	</tr>
	<tr><th>Suffix</th>
		<td>$street_number_suffix</td>
	</tr>
	<tr><th>Street</th>
		<td>$street</td>
	</tr>
	<tr><th>Zip</th>
		<td>$zip - $zipplus4</td>
	</tr>
	<tr><th>City</th>
		<td>$city</td>
	</tr>
	<tr><th>Status</th>
		<td>$status</td>
	</tr>
";
