<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param ContactList $this->contactList
 */
$contacts = array();
foreach ($this->contactList as $contact) {
	$name = addslashes("{$contact->getFirstname()} {$contact->getLastname()}");
	$phone = addslashes($contact->getPhone_number());
	$type = addslashes($contact->getType());
	$agency = addslashes($contact->getAgency());
	$string = <<<EOD
	{
		"id":"{$contact->getId()}",
		"name":"$name",
		"phone":"$phone",
		"type":"$type",
		"agency":"$agency"
	}
EOD;
	$contacts[] = $string;
}
$contacts = implode(",\n",$contacts);
echo '{"contacts":['.$contacts.']}';
