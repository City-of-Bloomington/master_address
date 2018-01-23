<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Towns\UseCases\Update;

use Domain\Towns\DataStorage\TownsRepository;

class Update
{
    private $repo;

    public function __construct(TownsRepository $repository)
    {
        $this->repo = $repository;
    }

    public function __invoke(UpdateRequest $req): UpdateResponse
    {
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
