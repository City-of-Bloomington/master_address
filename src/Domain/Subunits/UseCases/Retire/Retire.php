<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Subunits\UseCases\Retire;

use Domain\Logs\Entities\ChangeLogEntry;
use Domain\Logs\ChangeLogResponse;
use Domain\Logs\Metadata as Log;
use Domain\Subunits\DataStorage\SubunitsRepository;

class Retire
{
    private $repo;

    public function __construct(SubunitsRepository $repository)
    {
        $this->repo  = $repository;
    }

    public function __invoke(RetireRequest $req): ChangeLogResponse
    {
        try {
            $this->repo->saveStatus($req->subunit_id, Log::STATUS_RETIRED);

            foreach ($this->repo->locations($req->subunit_id) as $location) {
                if (   $location->active
                    && $location->status == Log::STATUS_CURRENT) {

                    $this->repo->saveLocationStatus($location->location_id, Log::STATUS_RETIRED);
                }
            }

            return new ChangeLogResponse($this->repo->logChange(new ChangeLogEntry([
                'action'    => Log::$actions['retire'],
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
