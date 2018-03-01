<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Addresses\UseCases\Validate;

use Domain\Addresses\Entities\Address;

class Validate
{
    public function __invoke(Address $address): ValidateResponse
    {
        $errors = [];

		// Check for required fields here.  Throw an exception if anything is missing.
		if ( !$this->street_id || !$this->street_number || !$this->zip
            || !$this->section || !$this->address_type
            || !$this->jurisdiction_id || !$this->township_id) {
			throw new \Exception('missingRequiredFields');
		}

		if (!in_array($this->getTrashDay(),    self::$trash_days   )) { throw new \Exception('addresses/invalidTrashDay'   ); }
		if (!in_array($this->getRecycleWeek(), self::$recycle_weeks)) { throw new \Exception('addresses/invalidRecycleWeek'); }

		// Make sure this is not a duplicate address
		$pdo = Database::getConnection();
		$sql = "select count(*) from addresses
				where street_id=?
				  and street_number_prefix=? and street_number=? and street_number_suffix=?";
        $query = $pdo->prepare($sql)->execute([
			$this->getStreet_id(),
			$this->getStreetNumberPrefix(), $this->getStreetNumber(), $this->getStreetNumberSuffix()
        ]);
        $count = $query->fetchColumn();
		if ((!$this->getId() && $count) || $count>1) {
			throw new \Exception('addresses/duplicateAddress');
		}

        return new ValidateResponse($address, $errors);
    }
}
