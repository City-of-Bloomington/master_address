<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Streets\UseCases\Add;

use Domain\Logs\Entities\ChangeLogEntry;
use Domain\Logs\Metadata as ChangeLog;
use Domain\Streets\DataStorage\StreetsRepository;
use Domain\Streets\UseCases\Alias\Alias;
use Domain\Streets\UseCases\Alias\AliasRequest;

class Add
{
    private $repo;

    public function __construct(StreetsRepository $repository)
    {
        $this->repo = $repository;
    }

    public function __invoke(AddRequest $req): AddResponse
    {
        $street_id = null;
        try {
            $errors = $this->validate($req);
            if ($errors) { return new AddResponse(null, $street_id, $errors); }

            $street_id = $this->repo->add($req);

            $action = $req->status == ChangeLog::STATUS_PROPOSED
                                    ? ChangeLog::ACTION_PROPOSE
                                    : ChangeLog::ACTION_CREATE;

            $entry = new ChangeLogEntry(['action'     => $action,
                                         'entity_id'  => $street_id,
                                         'person_id'  => $req->user_id,
                                         'contact_id' => $req->contact_id,
                                         'notes'      => $req->change_notes]);
            $log_id = $this->repo->logChange($entry);
            return new AddResponse($log_id, $street_id);
        }
        catch (\Exception $e) {
            return new AddResponse(null, $street_id, [$e->getMessage()]);
        }
    }

    private function validate(AddRequest $req): array
    {
        $errors = [];
        if (!$req->status ) { $errors[] = 'missingStatus'; }
        if (!$req->type_id) { $errors[] = 'designations/missingType'; }
        if (!$req->name_id) { $errors[] = 'missingName'; }

        if (   $req->status != ChangeLog::STATUS_CURRENT
            && $req->status != ChangeLog::STATUS_PROPOSED) {
            $errors[] = 'invalidStatus';
        }
        return $errors;
    }
}
