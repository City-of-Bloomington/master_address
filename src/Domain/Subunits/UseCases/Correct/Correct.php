<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Subunits\UseCases\Correct;

use Domain\Logs\Entities\ChangeLogEntry;
use Domain\Logs\Metadata as ChangeLog;
use Domain\Subunits\DataStorage\SubunitsRepository;

class Correct
{
    private $repo;

    public function __construct(SubunitsRepository $repository)
    {
        $this->repo = $repository;
    }

    public function __invoke(CorrectRequest $req): CorrectResponse
    {
        $errors = $this->validate($req);
        if ($errors) { return new CorrectResponse(null, $req->subunit_id, $errors); }

        try {
            $this->repo->correct($req);

            $entry = new ChangeLogEntry([
                'action'    => ChangeLog::$actions[ChangeLog::ACTION_CORRECT],
                'entity_id' => $req->subunit_id,
                'person_id' => $req->user_id,
                'notes'     => $req->change_notes
            ]);
            $log_id = $this->repo->logChange($entry, $this->repo::LOG_TYPE);
            return new CorrectResponse($log_id, $req->subunit_id);
        }
        catch (\Exception $e) {
            return new CorrectResponse(null, $req->subunit_id, [$e->getMessage()]);
        }
    }

    private function validate(CorrectRequest $req): array
    {
        $errors = [];

        if ($this->isDuplicateSubunit($req)) { $errors[] = 'subunits/duplicateSubunit'; }

        return $errors;
    }

    private function isDuplicateSubunit(CorrectRequest $req): bool
    {
        $s = $this->repo->load($req->subunit_id);

        $result = $this->repo->find([
            'address_id' =>   $s->address_id,
            'type_id'    => $req->type_id,
            'identifier' => $req->identifier
        ]);
        foreach ($result['rows'] as $s) {
            if ($s->id != $req->subunit_id) { return true; }
        }
        return false;
    }
}
