<?php
/**
 * Choose a new designation for a street.  The old designation becomes HISTORIC
 *
 * @copyright 2018-2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl-3.0.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Streets\UseCases\ChangeName;

use Domain\Logs\Entities\ChangeLogEntry;
use Domain\Logs\Metadata as ChangeLog;

use Domain\Streets\DataStorage\StreetsRepository;
use Domain\Streets\Entities\Designation;
use Domain\Streets\Designations\UseCases\Update\UpdateRequest;
use Domain\Streets\Metadata;
use Domain\Streets\UseCases\Alias\AliasRequest;

class ChangeName
{
    private $repo;

    public function __construct(StreetsRepository $repository)
    {
        $this->repo = $repository;
    }

    public function __invoke(ChangeNameRequest $request): ChangeNameResponse
    {
        $errors = $this->validate($request);
        if ($errors) {
            return new ChangeNameResponse(null, null, $errors);
        }

        try {
            // Make room in the ranks for the new STREET designation
            $designations = $this->repo->findDesignations(
                ['street_id'=>$request->street_id],
                ['rank desc']
            );
            foreach ($designations as $d) {
                $this->repo->updateDesignation(new UpdateRequest(
                    $d->id,
                    $request->user_id,
                    $d->start_date,
                    [
                        // Set any existing STREET designations to HISTORIC
                        'type_id' => $d->type_id==Metadata::TYPE_STREET ? Metadata::TYPE_HISTORIC : $d->type_id,
                        'rank'    => $d->rank + 1
                    ]
                ));
            }

            // Add the new STREET designation
            $designation_id = $this->repo->addDesignation(new Designation([
                'street_id'  => $request->street_id,
                'start_date' => $request->start_date,
                'type_id'    => Metadata::TYPE_STREET,
                'name_id'    => $request->name_id,
                'rank'       => 1
            ]));

            $entry = new ChangeLogEntry(['action'     => ChangeLog::ACTION_CHANGE,
                                         'entity_id'  => $request->street_id,
                                         'person_id'  => $request->user_id,
                                         'contact_id' => $request->contact_id,
                                         'notes'      => $request->change_notes]);
            $log_id = $this->repo->logChange($entry, $this->repo::LOG_TYPE);
            return new ChangeNameResponse($log_id, $designation_id);
        }
        catch (\Exception $e) {
            return ChangeNameResponse(null, null, [$e->getMessage()]);
        }
    }

    private function validate(ChangeNameRequest $request): array
    {
        $errors = [];
        if (!$request->street_id ) { $errors[] = 'missingRequiredFields'; }
        if (!$request->name_id   ) { $errors[] = 'missingName';           }
        if (!$request->start_date) { $errors[] = 'missingStartDate';      }
        return $errors;
    }
}
