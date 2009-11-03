<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param StreetList $this->streetList
 */
$columnHeadings = array('Street ID','Town','Street Status',
						'Street Name Type','Direction','Street Name','Street Type','Post Direction',
						'Street Name Start','Street Name End');
echo implode('|',$columnHeadings)."\n";
foreach ($this->streetList as $street) {
	$streetName = $street->getStreetName();
	$line = array($street->getId(),$street->getTown(),$street->getStatus(),
					$streetName->getStreetNameType(),$streetName->getDirection(),$streetName->getName(),
						$streetName->getStreetType(),$streetName->getPostDirection(),
						$streetName->getStartDate('n/j/Y'),$streetName->getEndDate('n/j/Y'));
	echo implode('|',$line)."\n";
}
