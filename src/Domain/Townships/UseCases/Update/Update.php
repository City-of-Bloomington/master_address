<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Townships\UseCases\Update;

use Domain\Townships\Entities\Township;
use Domain\Townships\DataStorage\TownshipsRepository;
use
class Update
{
    private $repo;

    public function __construct(TownshipsRepository $repository)
    {
        $this->repo = $repository;
    }

    public function __invoke(UpdateRequest $req): UpdateResponse
    {
        $errors = $this->validate($req);
        if (errors) { return new UpdateResponse(null, $errors); }

        try {
            $id  = $this->repo->save(new Township((array)$req));
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
        if (!$req->code) { $errors[] = 'missingCode'; }
        return $errors;
    }
}
