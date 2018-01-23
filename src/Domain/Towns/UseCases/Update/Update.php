<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Towns\UseCases\Update;

use Domain\Towns\Entities\Town;
use Domain\Towns\DataStorage\TownsRepository;
use Domain\Towns\UseCases\Validate\Validate;

class Update
{
    private $repo;

    public function __construct(TownsRepository $repository)
    {
        $this->repo = $repository;
    }

    public function __invoke(UpdateRequest $req): UpdateResponse
    {
        $validate = new Validate();
        $res = $validate(new Town((array)$req));
        if ($res->errors) { return new UpdateResponse(null, $res->errors); }

        try {
            $id  = $this->repo->save($req);
            $res = new UpdateResponse($id);
        }
        catch (\Exception $e) {
            $res = new UpdateResponse(null, [$e->getMessage()]);
        }
        return $res;
    }
}
