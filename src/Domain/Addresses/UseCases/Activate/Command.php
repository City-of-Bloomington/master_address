<?php
/**
 * Activate an address on a location
 *
 * There should only be one active address per location.
 *
 * @copyright 2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Addresses\UseCases\Activate;

use Domain\Addresses\DataStorage\AddressesRepository;
use Domain\Logs\Entities\ChangeLogEntry;
use Domain\Logs\Metadata as Log;

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
        if ($errors) { return new Response(null, $req->address_id, $errors); }

        try {
            $this->repo->activate($req->address_id, $req->location_id);

            $entry = new ChangeLogEntry([
                'action'     => Log::ACTION_ACTIVATE,
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
        if (!$req->location_id) { $errors[] = 'missingLocation'; }
        if (!$req-> address_id) { $errors[] = 'missingAddress';  }
        return $errors;
    }
}
