<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl-3.0.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Streets\Designations\UseCases\Update;

use Domain\Logs\Entities\ChangeLogEntry;
use Domain\Logs\Metadata as ChangeLog;
use Domain\Streets\DataStorage\StreetsRepository;

class Update
{
    private $repo;

    public function __construct(StreetsRepository $repository)
    {
        $this->repo = $repository;
    }

    public function __invoke(UpdateRequest $req): UpdateResponse
    {
        $errors = $this->validate($req);
        if ($errors) {
            return new UpdateResponse(null, $req->designation_id, $errors);
        }

        try {
            // Load the existing designation from the database, so we
            // have the street_id for writing the change log message.
            $designation = $this->repo->loadDesignation($req->designation_id);

            $this->repo->updateDesignation($req);

            $log_id = $this->repo->logChange(new ChangeLogEntry([
                'action'     => ChangeLog::$actions['update'],
                'entity_id'  => $designation->street_id,
                'person_id'  => $req->user_id,
                'contact_id' => $req->contact_id,
                'notes'      => $req->change_notes
            ]));

            return new UpdateResponse($log_id, $req->designation_id);
        }
        catch (\Exception $e) {
            return new UpdateResponse(null, $req->designation_id, [$e->getMessage()]);
        }
    }

    private function validate(UpdateRequest $req): array
    {
        $errors = [];
        if (!$req->designation_id) { $errors[] = 'designations/missingId';   }
        if (!$req->type_id       ) { $errors[] = 'designations/missingType'; }
        if (!$req->start_date    ) { $errors[] = 'missingStartDate';         }
        return $errors;
    }
}
