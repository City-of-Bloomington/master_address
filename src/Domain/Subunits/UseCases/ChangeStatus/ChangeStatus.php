<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl-3.0.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Subunits\UseCases\ChangeStatus;

use Domain\Logs\Entities\ChangeLogEntry;
use Domain\Logs\Metadata as Log;
use Domain\Subunits\DataStorage\SubunitsRepository;

class ChangeStatus
{
    private $repo;

    // Maps statuses to the log message that gets saved for them
    public static $STATUS_LOG_ACTIONS = [
        Log::STATUS_CURRENT  => Log::ACTION_ACTIVATE,
        Log::STATUS_PROPOSED => Log::ACTION_PROPOSE,
        Log::STATUS_RETIRED  => Log::ACTION_RETIRE
    ];

    public static function statuses(): array
    {
        return array_keys(self::$STATUS_LOG_ACTIONS);
    }

    public function __construct(SubunitsRepository $repository)
    {
        $this->repo  = $repository;
    }

    public function __invoke(ChangeStatusRequest $req): ChangeStatusResponse
    {
        $errors = $this->validate($req);
        if ($errors) {
            return new ChangeStatusResponse(null, $req->subunit_id, null, $errors);
        }

        $location_id = null;
        try {
            $this->repo->saveStatus($req->subunit_id, $req->status, $this->repo::LOG_TYPE);

            // Update the status on this subunit's active location
            foreach ($this->repo->locations($req->subunit_id) as $location) {
                if ($location->active) {
                    $locations_id = $location->location_id;
                    $this->repo->saveStatus($location->location_id, $req->status, 'location');
                    break;
                }
            }

            $entry = new ChangeLogEntry([
                'action'     => self::$STATUS_LOG_ACTIONS[$req->status],
                'entity_id'  => $req->subunit_id,
                'person_id'  => $req->user_id,
                'contact_id' => $req->contact_id,
                'notes'      => $req->change_notes
            ]);
            $log_id = $this->repo->logChange($entry, $this->repo::LOG_TYPE);

            return new ChangeStatusResponse($log_id, $req->subunit_id, $location_id);

        }
        catch (\Exception $e) {
            return new ChangeStatusResponse(null, $req->subunit_id, $location_id, [$e->getMessage()]);
        }
    }

    private function validate(ChangeStatusRequest $req): array
    {
        $errors = [];
        if (!in_array($req->status, self::statuses())) {
            $errors[] = 'invalidStatus';
        }
        return $errors;
    }
}
