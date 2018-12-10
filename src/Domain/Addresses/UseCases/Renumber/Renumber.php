<?php
/**
 * Action to change the street numbers for many addresses at once
 *
 * @copyright 2018 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl-3.0.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Addresses\UseCases\Renumber;

use Domain\Addresses\DataStorage\AddressesRepository;
use Domain\Logs\Entities\ChangeLogEntry;
use Domain\Logs\Metadata as ChangeLog;

class Renumber
{
    private $repo;

    public function __construct(AddressesRepository $repository)
    {
        $this->repo = $repository;
    }

    public function __invoke(RenumberRequest $req): RenumberResponse
    {
        $errors = $this->validate($req->address_numbers);
        if ($errors) { return new RenumberResponse($errors); }

        $this->repo->renumber($req);
        $logEntry = new ChangeLogEntry([
            'action'     => ChangeLog::$actions[ChangeLog::ACTION_RENUMBER],
            'person_id'  => $req->user_id,
            'contact_id' => $req->contact_id,
            'notes'      => $req->change_notes
        ]);
        foreach ($req->address_numbers as $a) {
            $logEntry->entity_id = $a->address_id;
            $this->repo->logChange($logEntry, $this->repo::LOG_TYPE);
        }
        return new RenumberResponse();
    }

    private function validate(array $address_numbers): array
    {
        $errors = [];
        foreach ($address_numbers as $a) {
            if (!$a->address_id   ) { $errors[] = 'addresses/missingId'; }
            if (!$a->street_number) { $errors[] = 'addresses/missingStreetNumber'; }
        }
        return $errors;
    }
}
