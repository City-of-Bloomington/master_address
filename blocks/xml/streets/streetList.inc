<streets>
<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param StreetList $this->streetList
 */
foreach ($this->streetList as $street) {
	$name = View::escape($street->getStreetName());
	$town = View::escape($street->getTown()->getDescription());
	$notes = View::escape($street->getNotes());
	echo "
	<street>
		<id>{$street->getId()}</id>
		<direction>{$street->getDirection()}</direction>
		<name>$name</name>
		<postDirection>{$street->getPostDirection()}</postDirection>
		<town>$town</town>
		<status>{$street->getStatus_code()}</status>
		<notes>$notes</notes>
	</street>
	";
}
?>
</streets>