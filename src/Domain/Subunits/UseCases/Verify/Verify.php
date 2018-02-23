<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Subunits\UseCases\Verify;

use Domain\ChangeLogs\ChangeLogEntry;
use Domain\ChangeLogs\ChangeLogResponse;
use Domain\ChangeLogs\Metadata as ChangeLog;
use Domain\Subunits\DataStorage\SubunitsRepository;

class Verify
{
    private $repo;

    public function __construct(SubunitsRepository $repository)
    {
        $this->repo = $repository;
    }

    public function __invoke(VerifyRequest $req): ChangeLogResponse
    {
        try {
            return new ChangeLogResponse($this->repo->logChange(new ChangeLogEntry([
                'action'    => ChangeLog::$actions['verify'],
                'entity_id' => $req->subunit_id,
                'person_id' => $req->user_id,
                'notes'     => $req->notes
            ])));
        }
        catch (\Exception $e) {
            return new ChangeLogResponse(null, [$e->getMessage()]);
        }
    }
}
