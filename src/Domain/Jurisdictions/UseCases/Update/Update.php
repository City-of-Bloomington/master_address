<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Jurisdictions\UseCases\Update;

use Domain\Jurisdictions\Entities\Jurisdiction;
use Domain\Jurisdictions\DataStorage\JurisdictionsRepository;
use Domain\Jurisdictions\UseCases\Validate\Validate;

class Update
{
    private $repo;

    public function __construct(JurisdictionsRepository $repository)
    {
        $this->repo = $repository;
    }

    public function __invoke(UpdateRequest $req): UpdateResponse
    {
        $validate = new Validate();
        $validation = $validate(new Jurisdiction((array)$req));
        if ($validation->errors) { return new UpdateResponse(null, $validation->errors); }

        try {
            $id  = $this->repo->save($validation->jurisdiction);
            $res = new UpdateResponse($id);
        }
        catch (\Exception $e) {
            $res = new UpdateResponse(null, [$e->getMessage()]);
        }
        return $res;
    }
}
