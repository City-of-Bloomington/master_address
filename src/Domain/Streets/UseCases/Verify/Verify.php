<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Streets\UseCases\Verify;

use Domain\Logs\Entities\ChangeLogEntry;
use Domain\Logs\Metadata as ChangeLog;
use Domain\Streets\DataStorage\StreetsRepository;

class Verify
{
    private $repo;

    public function __construct(StreetsRepository $repository)
    {
        $this->repo = $repository;
    }

    public function __invoke(VerifyRequest $req): VerifyResponse
    {
        try {
            $log_id = $this->repo->logChange(new ChangeLogEntry([
                'action'     => ChangeLog::$actions['verify'],
                'entity_id'  => $req->street_id,
                'person_id'  => $req->user_id,
                'contact_id' => $req->contact_id,
                'notes'      => $req->change_notes
            ]));
            return new VerifyResponse($log_id, $req->street_id);
        }
        catch (\Exception $e) {
            return new VerifyResponse(null, $req->street_id, [$e->getMessage()]);
        }
    }
}
