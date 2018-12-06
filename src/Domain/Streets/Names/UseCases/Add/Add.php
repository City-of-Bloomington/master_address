<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Streets\Names\UseCases\Add;

use Domain\Streets\Entities\Name;
use Domain\Streets\Names\DataStorage\NamesRepository;
use Domain\Streets\Names\UseCases\Validate\Validate;

class Add
{
    private $repo;

    public function __construct(NamesRepository $repository)
    {
        $this->repo = $repository;
    }

    public function __invoke(AddRequest $req): AddResponse
    {
        $name       = new Name((array)$req);
        $validate   = new Validate($this->repo);
        $validation = $validate($name);
        if ($validation->errors) {
            return new AddResponse(null, $validation->errors);
        }

        try {
            return new AddResponse($this->repo->save($name));
        }
        catch (\Exception $e) {
            return new AddResponse(null, [$e->getMessage()]);
        }
    }
}
