<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl-3.0.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Addresses\UseCases\Add;

use Domain\Addresses\DataStorage\AddressesRepository;
use Domain\Logs\Entities\ChangeLogEntry;
use Domain\Logs\Metadata as Log;

class Add
{
    private $repo;

    public function __construct(AddressesRepository $repository)
    {
        $this->repo = $repository;
    }

    public function __invoke(AddRequest $req): AddResponse
    {
        $errors = $this->validate($req);
        if ($errors) { return new AddResponse(null, null, $errors); }

        try {
            $address_id = $this->repo->add($req);

            $entry = new ChangeLogEntry(['action'     => Log::actionForStatus($req->status),
                                         'entity_id'  => $address_id,
                                         'person_id'  => $req->user_id,
                                         'contact_id' => $req->contact_id,
                                         'notes'      => $req->change_notes]);
            $log_id = $this->repo->logChange($entry);
            return new AddResponse($log_id, $address_id);

        }
        catch (\Exception $e) {
            return new AddResponse(null, null, [$e->getMessage()]);
        }
    }

    /**
     * Returns any and all errors with the request
     *
     * @return array  Any errors from the request
     */
    private function validate(AddRequest $req): array
    {
        $errors = [];
        if (!$req->street_number  ) { $errors[] = 'addresses/missingStreetNumber'; }
        if (!$req->address_type   ) { $errors[] = 'addresses/missingType';         }
        if (!$req->street_id      ) { $errors[] = 'addresses/missingStreet';       }
        if (!$req->jurisdiction_id) { $errors[] = 'addresses/missingJurisdiction'; }
        if (!$req->state          ) { $errors[] = 'addresses/missingState';        }

        return $errors;
    }
}
