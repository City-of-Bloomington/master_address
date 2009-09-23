<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param PlatList $this->platList
 */
$plats = array();
foreach ($this->platList as $plat) {
	$name = addslashes($plat->getName());
	$township = $plat->getTownship_id() ? addslashes($plat->getTownship()->getName()) : '';
	$plats[] = "{\"id\":\"{$plat->getId()}\",\"name\":\"$name\",\"township\":\"$township\"}";
}
$plats = implode(",\n",$plats);
echo '{"plats":['.$plats.']}';