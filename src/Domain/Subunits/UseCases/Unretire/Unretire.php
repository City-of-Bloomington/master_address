<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Subunits\UseCases\Unretire;

use Domain\Logs\Entities\ChangeLogEntry;
use Domain\Logs\Metadata as Log;
use Domain\Subunits\DataStorage\SubunitsRepository;

class Unretire
{
    private $repo;

    public function __construct(SubunitsRepository $repository)
    {
        $this->repo  = $repository;
    }

    public function __invoke(UnretireRequest $req): UnretireResponse
    {
        $location_id = null;
        try {
            $this->repo->saveStatus($req->subunit_id, Log::STATUS_CURRENT);

            foreach ($this->repo->locations($req->subunit_id) as $location) {
                if (   $location->active
                    && $location->status == Log::STATUS_RETIRED) {

                    // Only set one location to current
                    $location_id = $location->location_id;
                    $this->repo->saveLocationStatus($location_id, Log::STATUS_CURRENT);
                    break;
                }
            }

            $log_id = $this->repo->logChange(new ChangeLogEntry([
                'action'     => Log::$actions['unretire'],
                'entity_id'  => $req->subunit_id,
                'person_id'  => $req->user_id,
                'contact_id' => $req->contact_id,
                'notes'      => $req->change_notes
            ]));
            return new UnretireResponse($log_id, $req->subunit_id, $location_id);
        }
        catch (\Exception $e) {
            return new UnretireResponse(null, $req->subunit_id, $location_id, [$e->getMessage()]);
        }
    }
}
