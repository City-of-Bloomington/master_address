<?php
/**
 * Alter descriptive properties for an address
 *
 * Descriptive properties are the fields of the address not used for
 * street number, street name, and zip.  These are the fields such as
 * township, section, plat, jurisdiction, etc.
 *
 * This action is typically taken to fix information we have on record for
 * an address.  We do not need to report address "Updates" to outside agencies.
 *
 * @copyright 2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Addresses\UseCases\Update;

use Domain\Logs\Entities\ChangeLogEntry;
use Domain\Logs\Metadata as ChangeLog;
use Domain\Addresses\DataStorage\AddressesRepository;

class Command
{
    private $repo;

    public function __construct(AddressesRepository $repository)
    {
        $this->repo = $repository;
    }

    public function __invoke(Request $req): Response
    {
        $errors = $this->validate($req);
        if ($errors) {
            return new Response(null, $req->address_id, $errors);
        }

        try {
            $this->repo->update($req);
            $entry = new ChangeLogEntry([
                'action'     => ChangeLog::ACTION_UPDATE,
                'entity_id'  => $req->address_id,
                'person_id'  => $req->user_id,
                'contact_id' => $req->contact_id,
                'notes'      => $req->change_notes
            ]);
            $log_id = $this->repo->logChange($entry, $this->repo::LOG_TYPE);
            return new Response($log_id, $req->address_id);
        }
        catch (\Exception $e) {
            return new Response(null, $req->address_id, [$e->getMessage()]);
        }

    }

    private function validate(Request $req): array
    {
        $errors = [];
        if (!$req->address_id     ) { $errors[] = 'addresses/unknown';             }
        if (!$req->user_id        ) { $errors[] =     'users/unknown';             }
        if (!$req->address_type   ) { $errors[] = 'addresses/missingType';         }
        if (!$req->jurisdiction_id) { $errors[] = 'addresses/missingJurisdiction'; }
        return $errors;
    }
}
