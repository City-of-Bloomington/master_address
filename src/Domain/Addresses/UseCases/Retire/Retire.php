<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Addresses\UseCases\Retire;

use Domain\Logs\Entities\ChangeLogEntry;
use Domain\Logs\Metadata as Log;
use Domain\Addresses\DataStorage\AddressesRepository;

use Domain\Subunits\UseCases\Retire\Retire        as SubunitRetire;
use Domain\Subunits\UseCases\Retire\RetireRequest as SubunitRetireRequest;

class Retire
{
    private $repo;
    private $subunitRetire;

    public function __construct(AddressesRepository $repository, SubunitRetire $subunitRetire)
    {
        $this->repo          = $repository;
        $this->subunitRetire = $subunitRetire;
    }

    public function __invoke(RetireRequest $req): RetireResponse
    {
        try {
            $this->repo->saveStatus($req->address_id, Log::STATUS_RETIRED);

            $subunit_ids = [];
            $subunits    = $this->repo->subunits($req->address_id);
            if (count($subunits)) {
                $subunitRetire        = $this->subunitRetire;
                $subunitRetireRequest = new SubunitRetireRequest(1, $req->user_id, ['notes' => $req->notes]);
                foreach ($subunits as $subunit) {
                    if ($subunit->status == Log::STATUS_CURRENT) {
                        $subunit_ids[]                    = $subunit->id;
                        $subunitRetireRequest->subunit_id = $subunit->id;
                        $subunitRetire($subunitRetireRequest);
                    }
                }
            }

            $location_ids = [];
            foreach ($this->repo->locations($req->address_id) as $location) {
                if (   $location->active
                    && $location->status == Log::STATUS_CURRENT) {

                    $this->repo->saveLocationStatus($location->location_id, Log::STATUS_RETIRED);
                    $location_ids[] = $location->location_id;
                }
            }

            $log_id = $this->repo->logChange(new ChangeLogEntry([
                'action'     => Log::$actions[Log::ACTION_RETIRE],
                'entity_id'  => $req->address_id,
                'person_id'  => $req->user_id,
                'contact_id' => $req->contact_id,
                'notes'      => $req->change_notes
            ]));
            return new RetireResponse($log_id, $req->address_id, $subunit_ids, $location_ids);
        }
        catch (\Exception $e) {
            return new RetireResponse(null,    $req->address_id, $subunit_ids, $location_ids, [$e->getMessage()]);
        }
    }
}
