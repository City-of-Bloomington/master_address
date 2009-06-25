<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param AddressList $this->addressList
 */
echo "<addresses>";
foreach ($this->addressList as $address) {
	$block = new Block('addresses/addressInfo.inc',array('address'=>$address));
	echo $block->render('xml');
}
echo "</addresses>";