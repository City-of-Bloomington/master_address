<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */

$addressStatusList = new AddressStatusList();
$addressStatusList->find();
$addrLocationTypeList = new AddrLocationTypeList();
$addrLocationTypeList->find();
$addrLocationPurposeList = new AddrLocationPurposeList();
$addrLocationPurposeList->find();
$addressList = new AddressList();
$addressList->find(null,null,15); // first 15 

$template = new Template();
$template->blocks[] = new Block('addresses/addressStatusList.inc',
								array('addressStatusList'=>$addressStatusList));
$template->blocks[] = new Block('addresses/addrLocationTypeList.inc',
								array('addrLocationTypeList'=>$addrLocationTypeList));
$template->blocks[] = new Block('addresses/addrLocationPurposeList.inc',
								array('addrLocationPurposeList'=>$addrLocationPurposeList));
$template->blocks[] = new Block('addresses/addressList.inc', array('addressList'=>$addressList));
echo $template->render();
