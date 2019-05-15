<?php
/**
 * Activate a subunit on a location
 *
 * There should only be one active subunit per location.
 *
 * @copyright 2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Subunits\UseCases\Activate;

use Domain\Subunits\DataStorage\SubunitsRepository;
use Domain\Logs\Entities\ChangeLogEntry;
use Domain\Logs\Metadata as Log;

class Command
{
    private $repo;

    public function __construct(SubunitsRepository $repository)
    {
        $this->repo = $repository;
    }

    public function __invoke(Request $req): Response
    {
        $errors = $this->validate($req);
        if ($errors) { return new Response(null, $req->subunit_id, $errors); }

        try {
            $this->repo->activate($req->subunit_id, $req->location_id);

            $entry = new ChangeLogEntry([
                'action'     => Log::ACTION_ACTIVATE,
                'entity_id'  => $req->subunit_id,
                'person_id'  => $req->user_id,
                'contact_id' => $req->contact_id,
                'notes'      => $req->change_notes
            ]);
            $log_id = $this->repo->logChange($entry, $this->repo::LOG_TYPE);
            return new Response($log_id, $req->subunit_id);
        }
        catch (\Exception $e) {
            return new Response(null, $req->subunit_id, [$e->getMessage()]);
        }
    }

    private function validate(Request $req): array
    {
        $errors = [];
        if (!$req->location_id) { $errors[] = 'missingLocation'; }
        if (!$req-> subunit_id) { $errors[] = 'missingSubunit';  }
        return $errors;
    }
}
