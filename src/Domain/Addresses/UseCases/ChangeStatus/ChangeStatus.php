<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpltxt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Addresses\UseCases\ChangeStatus;

use Domain\Logs\Entities\ChangeLogEntry;
use Domain\Logs\Metadata as Log;
use Domain\Addresses\DataStorage\AddressesRepository;

use Domain\Subunits\UseCases\ChangeStatus\ChangeStatus        as SubunitStatusChange;
use Domain\Subunits\UseCases\ChangeStatus\ChangeStatusRequest as SubunitStatusChangeRequest;

class ChangeStatus
{
    private $repo;
    private $subunitChange;

    // Maps statuses to the log message that gets saved for them
    public static $STATUS_LOG_ACTIONS = [
        Log::STATUS_CURRENT  => Log::ACTION_ACTIVATE,
        Log::STATUS_PROPOSED => Log::ACTION_PROPOSE,
        Log::STATUS_RETIRED  => Log::ACTION_RETIRE
    ];

    public static function statuses() { return array_keys(self::$STATUS_LOG_ACTIONS); }

    public function __construct(AddressesRepository $repository, SubunitStatusChange $subunitChange)
    {
        $this->repo          = $repository;
        $this->subunitChange = $subunitChange;
    }

    public function __invoke(ChangeStatusRequest $req): ChangeStatusResponse
    {
        $errors = $this->validate($req);
        if ($errors) { return new ChangeStatusResponse(null, $req->address_id, $errors); }

        try {
            $this->repo->saveStatus($req->address_id, $req->status, $this->repo::LOG_TYPE);

            if ($req->status == Log::STATUS_RETIRED) {
                // Retire all the current subunits
                // Make sure each subunit gets a changeLog entry for
                $subunits = $this->repo->subunits($req->address_id);
                if (count($subunits)) {
                    $change  = $this->subunitChange;
                    $request = new SubunitStatusChangeRequest(1, $req->user_id, (array)$req);
                    foreach ($subunits as $subunit) {
                        if ($subunit->status != $req->status) {
                            $request->subunit_id = $subunit->id;
                            $change($request);
                        }
                    }
                }
            }

            // Update the status on the active location for this address
            foreach ($this->repo->locations($req->address_id) as $location) {
                if (   $location->active
                    && $location->status != $req->status) {

                    $this->saveStatus($location->location_id, $req->status, 'location');
                }
            }

            $entry = new ChangeLogEntry([
                'action'     => self::$STATUS_LOG_ACTIONS[$req->status],
                'entity_id'  => $req->address_id,
                'person_id'  => $req->user_id,
                'contact_id' => $req->contact_id,
                'notes'      => $req->change_notes
            ]);
            $log_id = $this->repo->logChange($entry, $this->repo::LOG_TYPE);
            return new ChangeStatusResponse($log_id, $req->address_id);
        }
        catch (\Exception $e) {
            return new ChangeStatusResponse(null, $req->id, [$e->getMessage()]);
        }
    }

    private function validate(ChangeStatusRequest $req): array
    {
        $errors = [];
        if (!in_array($req->status, array_keys(self::$STATUS_LOG_ACTIONS))) {
            $errors[] = 'invalidStatus';
        }
        return $errors;
    }
}
