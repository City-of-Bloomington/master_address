<?php
/**
 * Process a change of address
 *
 * For a change of address, we need to preserve the old address.
 * We retire the old address, and create a new address at the same location
 * The new address must have a different street and street number.
 *
 * The new address will be on the same location as the old address.
 *
 * @copyright 2018-2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Addresses\UseCases\Readdress;

use Domain\Addresses\DataStorage\AddressesRepository;
use Domain\Logs\Entities\ChangeLogEntry;
use Domain\Logs\Metadata as Log;

class Readdress
{
    private $repo;

    public function __construct(AddressesRepository $repository)
    {
        $this->repo = $repository;
    }

    public function __invoke(ReaddressRequest $req): ReaddressResponse
    {
        $errors = $this->validate($req);
        if ($errors) {
            return new ReaddressResponse(null, null, $errors);
        }
        try {
            $old_address_id = $req->address_id;
            $new_address_id = $this->repo->readdress($req);
            $subunits       = $this->repo->findSubunits(['address_id' => $old_address_id]);

            // Log the action on both the old and new address,
            // as well as any subunits affected
            $entry = new ChangeLogEntry(['action'     => Log::ACTION_READDRESS,
                                         'entity_id'  => $old_address_id,
                                         'person_id'  => $req->user_id,
                                         'contact_id' => $req->contact_id,
                                         'notes'      => $req->change_notes]);
            $log_id = $this->repo->logChange($entry, $this->repo::LOG_TYPE);
            $entry->entity_id = $new_address_id;
            $this->repo->logChange($entry, $this->repo::LOG_TYPE);

            // Do something with the subunits
            if (!$req->retireSubunits) {
                $this->repo->moveSubunitsToAddress($old_address_id, $new_address_id);
                foreach ($subunits['rows'] as $s) {
                    $entry->entity_id = $s->id;
                    $this->repo->logChange($entry, 'subunit');
                }
            }
            else {
                $entry->action = Log::ACTION_RETIRE;

                foreach ($subunits['rows'] as $s) {
                    $this->repo->saveStatus($s->id, Log::STATUS_RETIRED, 'subunit');
                    $entry->entity_id = $s->id;
                    $this->repo->logChange($entry, 'subunit');
                }
            }

            return new ReaddressResponse($log_id, $new_address_id);
        }
        catch (\Exception $e) {
            return new ReaddressResponse(null, null, [$e->getMessage()]);
        }
    }

    private function validate(ReaddressRequest $req): array
    {
        $errors = [];
        if (!$req->address_id ) { $errors[] = 'addresses/unknown'; }
        if (!$req->location_id) { $errors[] = 'locations/unknown'; }

        if ($this->isDuplicateAddress($req)) { $errors[] = 'addresses/duplicateAddress'; }

        if (!$req->street_number  ) { $errors[] = 'addresses/missingStreetNumber'; }
        if (!$req->address_type   ) { $errors[] = 'addresses/missingType';         }
        if (!$req->street_id      ) { $errors[] = 'addresses/missingStreet';       }
        if (!$req->jurisdiction_id) { $errors[] = 'addresses/missingJurisdiction'; }
        if (!$req->state          ) { $errors[] = 'addresses/missingState';        }
        if (!$req->zip            ) { $errors[] = 'addresses/missingZip';          }

        return $errors;
    }

    private function isDuplicateAddress(ReaddressRequest $req): bool
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
