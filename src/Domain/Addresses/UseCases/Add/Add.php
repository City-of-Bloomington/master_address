<?php
/**
 * @copyright 2018-2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl-3.0.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Addresses\UseCases\Add;

use Domain\Addresses\DataStorage\AddressesRepository;
use Domain\Logs\Entities\ChangeLogEntry;
use Domain\Logs\Metadata as Log;

class Add
{
    public static $validActions = [
        Log::ACTION_ASSIGN,
        Log::ACTION_ADD
    ];

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
            $action = $req->action == Log::ACTION_ASSIGN
                    ? Log::actionForStatus($req->status)
                    : $req->action;

            $entry = new ChangeLogEntry(['action'     => $action,
                                         'entity_id'  => $address_id,
                                         'person_id'  => $req->user_id,
                                         'contact_id' => $req->contact_id,
                                         'notes'      => $req->change_notes]);
            $log_id = $this->repo->logChange($entry, $this->repo::LOG_TYPE);
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

        if (!in_array($req->action, Add::$validActions)) {
            $errors[] = 'invalidAction';
        }
        if ($this->isDuplicateAddress($req)) { $errors[] = 'addresses/duplicateAddress'; }

        if (!$req->street_number  ) { $errors[] = 'addresses/missingStreetNumber'; }
        if (!$req->address_type   ) { $errors[] = 'addresses/missingType';         }
        if (!$req->street_id      ) { $errors[] = 'addresses/missingStreet';       }
        if (!$req->jurisdiction_id) { $errors[] = 'addresses/missingJurisdiction'; }
        if (!$req->state          ) { $errors[] = 'addresses/missingState';        }
        if (!$req->zip            ) { $errors[] = 'addresses/missingZip';          }

        if (!$req->locationType_id) { $errors[] = 'locations/missingType';         }

        return $errors;
    }

    private function isDuplicateAddress(AddRequest $req): bool
    {
        $result = $this->repo->find([
            'street_id'            => $req->street_id,
            'street_number_prefix' => $req->street_number_prefix,
            'street_number'        => $req->street_number,
            'street_number_suffix' => $req->street_number_suffix
        ]);
        return count($result['rows']) ? true : false;
    }
}
