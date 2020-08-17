<?php
/**
 * @copyright 2018-2020 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Subunits\UseCases\Add;

use Domain\Logs\Entities\ChangeLogEntry;
use Domain\Logs\Metadata as Log;
use Domain\Subunits\DataStorage\SubunitsRepository;

class Add
{
    private $repo;

    public function __construct(SubunitsRepository $repository)
    {
        $this->repo = $repository;
    }

    public function __invoke(AddRequest $req): AddResponse
    {
        $subunit_id  = null;

        $errors = $this->validate($req);
        if ($errors) { return new AddResponse(null, null, $errors); }

        try {
            $subunit_id = $this->repo->add($req);

            $entry = new ChangeLogEntry([
                'action'    => Log::actionForStatus($req->status),
                'entity_id' => $subunit_id,
                'person_id' => $req->user_id,
                'notes'     => $req->change_notes
            ]);
            $log_id = $this->repo->logChange($entry, $this->repo::LOG_TYPE);
            return new AddResponse($log_id, $subunit_id);
        }
        catch (\Exception $e) {
            return new AddResponse(null, $subunit_id, [$e->getMessage()]);
        }
    }


    /**
     * @param  AddRequest $req
     * @return array            Any errors with the request
     */
    public function validate(AddRequest $req): array
    {
        $errors = [];
        if (!$req->address_id     ) { $errors[] = 'subunits/missingAddress';    }
        if (!$req->type_id        ) { $errors[] = 'subunits/missingType';       }
        if (!$req->identifier     ) { $errors[] = 'subunits/missingIdentifier'; }
        if (!$req->locationType_id) { $errors[] = 'locations/missingType';      }
        if (!$req->status         ) { $errors[] = 'missingStatus';              }

        # If there are required fields missing, we can save time and return right away.
        # Also, the duplicate check depends on some of the required fields.
        # If they are missing, the duplicate check will return false positives.
        if ($errors) { return $errors; }
        if ($this->isDuplicateSubunit($req)) { return ['subunits/duplicateSubunit']; }

        return $this->repo->validate($req);
    }

    private function isDuplicateSubunit(AddRequest $req): bool
    {
        $result = $this->repo->find([
            'address_id' => $req->address_id,
            'type_id'    => $req->type_id,
            'identifier' => $req->identifier
        ]);
        return count($result['rows']) ? true : false;
    }
}
