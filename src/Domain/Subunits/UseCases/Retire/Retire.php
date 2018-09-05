<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Subunits\UseCases\Retire;

use Domain\Logs\Entities\ChangeLogEntry;
use Domain\Logs\Metadata as Log;
use Domain\Subunits\DataStorage\SubunitsRepository;

class Retire
{
    private $repo;

    public function __construct(SubunitsRepository $repository)
    {
        $this->repo  = $repository;
    }

    public function __invoke(RetireRequest $req): RetireResponse
    {
        $location_ids = [];
        try {
            $this->repo->saveStatus($req->subunit_id, Log::STATUS_RETIRED);

            foreach ($this->repo->locations($req->subunit_id) as $location) {
                if (   $location->active
                    && $location->status == Log::STATUS_CURRENT) {

                    $locations_ids[] = $location->location_id;
                    $this->repo->saveLocationStatus($location->location_id, Log::STATUS_RETIRED);
                }
            }

            $log_id = $this->repo->logChange(new ChangeLogEntry([
                'action'     => Log::$actions['retire'],
                'entity_id'  => $req->subunit_id,
                'person_id'  => $req->user_id,
                'contact_id' => $req->contact_id,
                'notes'      => $req->change_notes
            ]));
            return new RetireResponse($log_id, $req->subunit_id, $location_ids);
        }
        catch (\Exception $e) {
            return new RetireResponse(null, $req->subunit_id, $location_ids, [$e->getMessage()]);
        }
    }
}
