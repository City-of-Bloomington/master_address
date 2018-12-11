<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Streets\UseCases\Update;

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
        $street_id = $req->street_id;
        try {
            $this->repo->update($req);

            $entry = new ChangeLogEntry([
                'action'     => ChangeLog::$actions['update'],
                'entity_id'  => $street_id,
                'person_id'  => $req->user_id,
                'contact_id' => $req->contact_id,
                'notes'      => $req->change_notes
            ]);
            $log_id = $this->repo->logChange($entry, $this->repo::LOG_TYPE);
            return new UpdateResponse($log_id, $street_id);
        }
        catch (\Exception $e) {
            return new UpdateResponse(null, $street_id, [$e->getMessage()]);
        }
    }
}
