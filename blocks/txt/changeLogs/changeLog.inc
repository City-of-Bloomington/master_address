<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param array $this->changeLog (An array of ChangeLogEntry's)
 */
$columnHeadings = array('Action Date',
						'Street Number','Direction','Street Name','Street Type','Post Direction',
						'Subunit Type','Subunit Identifier',
						'Zip',
						'Action',
						'Plat','Lot',
						'Old',
						'Jurisdiction','Location Type',
						'Notes',
						'Street ID','Town','Street Status',
						'Street Name Type',
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
	$oldAddress = "{$address->getOldAddress()}";
	$notes = preg_replace('/\n/',' ',$logEntry->notes);
	$line = array($logEntry->action_date,
					$address->getStreet_number(),$streetName->getDirection(),$streetName->getName(),
						$streetName->getStreetType(),$streetName->getPostDirection(),
					$address->getZip(),
					$logEntry->action,
					$subunit->getType(),$subunit->getIdentifier(),
					$address->getPlat(),$address->getPlat_lot_number(),
					$oldAddress,
					$address->getJurisdiction(),$locationType,
					$notes,
					$street->getId(),$street->getTown(),$street->getStatus(),
					$streetName->getStreetNameType(),
					$streetName->getStartDate('n/j/Y'),$streetName->getEndDate('n/j/Y'));

	echo implode('|',$line)."\n";
}