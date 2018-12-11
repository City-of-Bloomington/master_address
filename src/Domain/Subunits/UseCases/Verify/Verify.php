<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Subunits\UseCases\Verify;

use Domain\Logs\Entities\ChangeLogEntry;
use Domain\Logs\Metadata as ChangeLog;
use Domain\Subunits\DataStorage\SubunitsRepository;

class Verify
{
    private $repo;

    public function __construct(SubunitsRepository $repository)
    {
        $this->repo = $repository;
    }

    public function __invoke(VerifyRequest $req): VerifyResponse
    {
        try {
            $entry = new ChangeLogEntry([
                'action'     => ChangeLog::$actions['verify'],
                'entity_id'  => $req->subunit_id,
                'person_id'  => $req->user_id,
                'contact_id' => $req->contact_id,
                'notes'      => $req->change_notes
            ]);
            $log_id = $this->repo->logChange($entry, $this->repo::LOG_TYPE);
            return new VerifyResponse($log_id, $req->subunit_id);
        }
        catch (\Exception $e) {
            return new VerifyResponse(null, $req->subunit_id, [$e->getMessage()]);
        }
    }
}
