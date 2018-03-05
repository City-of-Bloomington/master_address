<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Streets\UseCases\Retire;

use Domain\Logs\Entities\ChangeLogEntry;
use Domain\Logs\ChangeLogResponse;
use Domain\Logs\Metadata as Log;
use Domain\Streets\DataStorage\StreetsRepository;

class Retire
{
    private $repo;

    public function __construct(StreetsRepository $repository)
    {
        $this->repo  = $repository;
    }

    public function __invoke(RetireRequest $req): ChangeLogResponse
    {
        try {
            $this->repo->saveStatus($req->street_id, Log::STATUS_RETIRED);

            return new ChangeLogResponse($this->repo->logChange(new ChangeLogEntry([
                'action'     => Log::$actions['retire'],
                'entity_id'  => $req->street_id,
                'person_id'  => $req->user_id,
                'contact_id' => $req->contact_id,
                'notes'      => $req->change_notes
            ])));
        }
        catch (\Exception $e) {
            return new ChangeLogResponse(null, [$e->getMessage()]);
        }
    }
}
