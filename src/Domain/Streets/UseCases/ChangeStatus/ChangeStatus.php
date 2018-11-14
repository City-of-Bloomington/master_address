<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Streets\UseCases\ChangeStatus;

use Domain\Logs\Entities\ChangeLogEntry;
use Domain\Logs\Metadata as Log;
use Domain\Streets\DataStorage\StreetsRepository;

class ChangeStatus
{
    private $repo;

    // Maps statuses to the log message that gets saved for them
    public static $STATUS_LOG_ACTIONS = [
        Log::STATUS_CURRENT  => Log::ACTION_ACTIVATE,
        Log::STATUS_PROPOSED => Log::ACTION_PROPOSE,
        Log::STATUS_RETIRED  => Log::ACTION_RETIRE
    ];

    public static function statuses(): array { return array_keys(self::$STATUS_LOG_ACTIONS); }

    public function __construct(StreetsRepository $repository)
    {
        $this->repo  = $repository;
    }

    public function __invoke(ChangeStatusRequest $req): ChangeStatusResponse
    {
        $errors = $this->validate($req);
        if ($errors) { return new ChangeStatusResponse(null, $req->street_id, $errors); }

        try {
            $this->repo->saveStatus($req->street_id, $req->status);

            $log_id = $this->repo->logChange(new ChangeLogEntry([
                'action'     => self::$STATUS_LOG_ACTIONS[$req->status],
                'entity_id'  => $req->street_id,
                'person_id'  => $req->user_id,
                'contact_id' => $req->contact_id,
                'notes'      => $req->change_notes
            ]));
            return new ChangeStatusResponse($log_id, $req->street_id);
        }
        catch (\Exception $e) {
            return new ChangeStatusResponse(null, $req->street_id, [$e->getMessage()]);
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
