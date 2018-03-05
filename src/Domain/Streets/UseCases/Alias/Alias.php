<?php
/**
 * Add a new Designation for a Street
 *
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Streets\UseCases\Alias;

use Domain\Streets\DataStorage\StreetsRepository;
use Domain\Logs\ChangeLogResponse;
use Domain\Logs\Entities\ChangeLogEntry;
use Domain\Logs\Metadata as ChangeLog;

class Alias
{
    private $repo;

    public function __construct(StreetsRepository $repository)
    {
        $this->repo = $repository;
    }

    public function __invoke(AliasRequest $req): ChangeLogResponse
    {
        try {
            $errors = $this->validate($req);
            if ($errors) {
                return new ChangeLogResponse(null, $errors);
            }

            $this->repo->addDesignation($req);
            return new ChangeLogResponse($this->repo->logChange(new ChangeLogEntry([
                'action'     => ChangeLog::$actions['alias'],
                'entity_id'  => $req->street_id,
                'person_id'  => $req->user_id,
                'contact_id' => $req->contact_id,
                'notes'      => $req->change_notes
            ])));
        }
        catch (\Exception $e) {
            return new ChangeLogResponse(null, [$e->getMessage()]);
        }

    }

    private function validate(AliasRequest $req): array
    {
        $errors = [];
        if (!$req->street_id || !$req->name_id || !$req->type_id) {
            $errors[] = 'missingRequiredFields';
        }

        // You cannot create an Alias of type STREET
        if ($req->type_id == $this->repo::TYPE_STREET) {
            $errors[] = 'streets/invalidAliasType';
        }
        // Make sure there's no duplicate names
        foreach ($this->repo->designations($req->street_id) as $d) {
            if ($d->name_id == $req->name_id) {
                $errors[] = 'streets/duplicateDesignation';
            }
        }
        return $errors;
    }
}
