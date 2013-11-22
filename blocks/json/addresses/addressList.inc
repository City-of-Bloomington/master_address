<?php
/**
 * @copyright 2009-2013 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param AddressList $this->addressList
 */
$block = new Block('addresses/addressInfo.inc');

$addresses = [];
foreach ($this->addressList as $address) {
	$block->address = $address;
	$addresses[] = $block->render('json');
}
echo '['.implode($addresses,',').']';
