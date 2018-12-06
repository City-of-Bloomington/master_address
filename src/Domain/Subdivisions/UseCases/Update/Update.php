<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Subdivisions\UseCases\Update;

use Domain\Subdivisions\Entities\Subdivision;
use Domain\Subdivisions\DataStorage\SubdivisionsRepository;

class Update
{
    private $repo;

    public function __construct(SubdivisionsRepository $repository)
    {
        $this->repo = $repository;
    }

    public function __invoke(UpdateRequest $req): UpdateResponse
    {
        $errors = $this->validate($req);
        if ($errors) { return new UpdateResponse(null, $errors); }

        try {
            $id  = $this->repo->save(new Subdivision((array)$req));
            $res = new UpdateResponse($id);
        }
        catch (\Exception $e) {
            $res = new UpdateResponse(null, [$e->getMessage()]);
        }
        return $res;
    }

    private function validate(UpdateRequest $req): array
    {
        $errors = [];
        if (!$req->name) { $errors[] = 'missingName'; }
        if (!$req->status) { $errors[] = 'missingStatus'; }
        return $errors;
    }
}
