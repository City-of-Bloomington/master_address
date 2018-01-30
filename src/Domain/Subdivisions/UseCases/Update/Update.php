<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Subdivisions\UseCases\Update;

use Domain\Subdivisions\Entities\Subdivision;
use Domain\Subdivisions\DataStorage\SubdivisionsRepository;
use Domain\Subdivisions\Metadata;
use Domain\Subdivisions\UseCases\Validate\Validate;

class Update
{
    private $repo;

    public function __construct(SubdivisionsRepository $repository)
    {
        $this->repo = $repository;
    }

    public function __invoke(UpdateRequest $req): UpdateResponse
    {
        $validate = new Validate(new Metadata($this->repo));
        $validation =  $validate(new Subdivision((array)$req));
        if ($validation->errors) { return new UpdateResponse(null, $validation->errors); }

        try {
            $id  = $this->repo->save($validation->subdivision);
            $res = new UpdateResponse($id);
        }
        catch (\Exception $e) {
            $res = new UpdateResponse(null, [$e->getMessage()]);
        }
        return $res;
    }
}
