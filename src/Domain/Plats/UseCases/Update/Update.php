<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Plats\UseCases\Update;

use Domain\Plats\Entities\Plat;
use Domain\Plats\DataStorage\PlatsRepository;

class Update
{
    private $repo;

    public function __construct(PlatsRepository $repository)
    {
        $this->repo = $repository;
    }

    public function __invoke(UpdateRequest $req): UpdateResponse
    {
        $errors = $this->validate($req);
        if ($errors) { return new UpdateResponse(null, $errors); }

        try {
            $id  = $this->repo->save(new Plat((array)$req));
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
        if (!$req->name       ) { $errors[] = 'missingName';     }
        if (!$req->plat_type  ) { $errors[] = 'missingType';     }
        if (!$req->township_id) { $errors[] = 'missingTownship'; }
        return $errors;
    }
}
