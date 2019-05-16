<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Subunits\UseCases\Update;

use Domain\Logs\Entities\ChangeLogEntry;
use Domain\Logs\Metadata as Log;
use Domain\Subunits\DataStorage\SubunitsRepository;

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
        if ($errors) {
            return new Response(null, $req->subunit_id, $errors);
        }

        try {
            $this->repo->update($req);

            $entry = new ChangeLogEntry([
                'action'     => Log::ACTION_UPDATE,
                'entity_id'  => $req->subunit_id,
                'person_id'  => $req->user_id,
                'contact_id' => $req->contact_id,
                'notes'      => $req->change_notes
            ]);
            $log_id = $this->repo->logChange($entry, $this->repo::LOG_TYPE);
            return new Response($log_id, $req->address_id);
        }
        catch (\Exception $e) {
            return new Response(null, $req->subunit_id, [$e->getMessage()]);
        }
    }

    private function validate(Request $req): array
    {
        $errors = [];
        if (!$req->subunit_id) { $errors[] = 'subunits/unknown'; }
        if (!$req->user_id   ) { $errors[] =    'users/unknown'; }
        return $errors;
    }
}
