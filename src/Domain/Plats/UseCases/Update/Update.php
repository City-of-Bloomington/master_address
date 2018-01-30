<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Plats\UseCases\Update;

use Domain\Plats\Entities\Plat;
use Domain\Plats\DataStorage\PlatsRepository;
use Domain\Plats\UseCases\Validate\Validate;

class Update
{
    private $repo;

    public function __construct(PlatsRepository $repository)
    {
        $this->repo = $repository;
    }

    public function __invoke(UpdateRequest $req): UpdateResponse
    {
        $validate = new Validate();
        $validation = $validate(new Plat((array)$req));
        if ($validation->errors) { return new UpdateResponse(null, $validation->errors); }

        try {
            $id  = $this->repo->save($validation->plat);
            $res = new UpdateResponse($id);
        }
        catch (\Exception $e) {
            $res = new UpdateResponse(null, [$e->getMessage()]);
        }
        return $res;
    }
}
