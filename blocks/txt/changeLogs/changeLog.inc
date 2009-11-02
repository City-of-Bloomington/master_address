<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param array $this->changeLog (An array of ChangeLogEntry's)
 */
$columnHeadings = array('Action Date','Action','Notes',
						'Street Number','Zip','Jurisdiction','Plat','Lot','Location Type',
						'Subunit Type','Subunit Identifier',
						'Street ID','Town','Street Status',
						'Street Name Type','Direction','Street Name','Street Type','Post Direction',
						'Street Name Start','Street Name End');
echo implode('|',$columnHeadings)."\n";
foreach ($this->changeLog as $logEntry) {
	$target = $logEntry->getTarget();
	switch (get_class($target)) {
		case 'Address':
			$address = $target;
			$street = $address->getStreet();
			$subunit = new Subunit();
			break;
		case 'Subunit':
			$address = $target->getAddress();
			$street = $address->getStreet();
			$subunit = $target;
			break;
		case 'Street':
			$address = new Address();
			$street = $target;
			$subunit = new Subunit();
			break;
	}
	$streetName = $street->getStreetName();
	$locationType = $address->getLocation() ? $address->getLocation()->getLocationType($address) : '';

	$notes = preg_replace('/\n/',' ',$logEntry->notes);
	$line = array($logEntry->action_date,$logEntry->action,$notes,
					$address->getStreet_number(),$address->getZip(),$address->getJurisdiction(),
						$address->getPlat(),$address->getPlat_lot_number(),$locationType,
					$subunit->getType(),$subunit->getIdentifier(),
					$street->getId(),$street->getTown(),$street->getStatus(),
					$streetName->getStreetNameType(),$streetName->getDirection(),$streetName->getName(),
						$streetName->getStreetType(),$streetName->getPostDirection(),
						$streetName->getStartDate('n/j/Y'),$streetName->getEndDate('n/j/Y'));

	echo implode('|',$line)."\n";
}