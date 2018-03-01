<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Streets\Names\UseCases\Correct;

use Domain\Streets\Entities\Name;
use Domain\Streets\Names\DataStorage\NamesRepository;

class Correct
{
    private $repo;

    public function __construct(NamesRepository $repository)
    {
        $this->repo = $repository;
    }

    public function __invoke(CorrectRequest $request): CorrectResponse
    {
        try {
            return new CorrectResponse($this->repo->save(new Name((array)$request)));
        }
        catch (\Exception $e) {
            return new CorrectResponse(null, [$e->getMessage()]);
        }
    }
}
