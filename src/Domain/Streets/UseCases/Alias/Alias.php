<?php
/**
 * Add a new Designation for a Street
 *
 * @copyright 2018-2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Streets\UseCases\Alias;

use Domain\Streets\DataStorage\StreetsRepository;
use Domain\Streets\Entities\Designation;
use Domain\Streets\Metadata as Street;
use Domain\Logs\Entities\ChangeLogEntry;
use Domain\Logs\Metadata as ChangeLog;

class Alias
{
    private $repo;

    public function __construct(StreetsRepository $repository)
    {
        $this->repo = $repository;
    }

    public function __invoke(AliasRequest $req): AliasResponse
    {
        try {
            $errors = $this->validate($req);
            if ($errors) {
                return new AliasResponse(null, $req->street_id, null, $errors);
            }

            $des            = new Designation((array)$req);
            $des->rank      = $this->repo->nextDesignationRank($req->street_id);
            $designation_id = $this->repo->addDesignation($des);

            $entry = new ChangeLogEntry([
                'action'     => ChangeLog::$actions['alias'],
                'entity_id'  => $req->street_id,
                'person_id'  => $req->user_id,
                'contact_id' => $req->contact_id,
                'notes'      => $req->change_notes
            ]);
            $log_id = $this->repo->logChange($entry, $this->repo::LOG_TYPE);
            return new AliasResponse($log_id, $req->street_id, $designation_id);
        }
        catch (\Exception $e) {
            return new AliasResponse(null,    $req->street_id, $designation_id, [$e->getMessage()]);
        }
    }

    private function validate(AliasRequest $req): array
    {
        $errors = [];
        if (!$req->street_id) { $errors[] = 'missingRequiredFields'; }
        if (!$req->name_id  ) { $errors[] = 'missingName'; }
        if (!$req->type_id  ) { $errors[] = 'designations/missingType'; }

        // You cannot create an Alias of type STREET
        if ($req->type_id == Street::TYPE_STREET) {
            $errors[] = 'streets/invalidAliasType';
        }
        // Make sure there's no duplicate names
        foreach ($this->repo->findDesignations(['street_id' => $req->street_id]) as $d) {
            if ($d->name_id == $req->name_id) {
                $errors[] = 'streets/duplicateDesignation';
            }
        }
        return $errors;
    }
}
