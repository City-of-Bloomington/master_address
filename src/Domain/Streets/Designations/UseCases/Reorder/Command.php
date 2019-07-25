<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Streets\Designations\UseCases\Reorder;

use Domain\Streets\DataStorage\StreetsRepository;
use Domain\Streets\Entities\Designation;

class Command
{
    private $repo;

    public function __construct(StreetsRepository $repository)
    {
        $this->repo = $repository;
    }

    public function __invoke(Request $req): Response
    {
        $errors = $this->validate($req);
        if ($errors) {
            return new Response($req->street_id, $errors);
        }

        try {
            $this->repo->reorderDesignations($req->street_id, $req->designation_ids);
        }
        catch (\Exception $e) {
            return new Response($req->street_id, [$e->getMessage()]);
        }

        return new Response($req->street_id);

    }

    private function validate(Request $req): array
    {
        $errors = [];

        if (!$req->street_id) { $errors[] = 'missingRequiredFields'; }

        foreach ($req->designation_ids as $i => $id) {
            $d = $this->repo->loadDesignation($id);

            // Make sure all the designations are for the declared street_id
            if ($d->street_id != $req->street_id) {
                $errors[] = 'designationReorder/invalidDesignation';
            }

            // There can only be one STREET designation, and it must be first
            if (!$this->streetTypeIsFirst($i, $d)) {
                $errors[] = 'designationReorder/streetNotFirst';
            }
        }

        return $errors;
    }

    private function streetTypeIsFirst(int $position, Designation $designation): bool
    {
        return ($designation->type_id == $this->repo::TYPE_STREET)
            ?  ($position == 0)
            :  ($position != 0);
   }
}
