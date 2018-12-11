<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Streets\Names\UseCases\Load;

use Domain\Streets\Names\DataStorage\NamesRepository;

class Load
{
    private $repo;

    public function __construct(NamesRepository $repository)
    {
        $this->repo = $repository;
    }

    public function __invoke(int $name_id): LoadResponse
    {
        try {
            return new LoadResponse($this->repo->load($name_id));
        }
        catch (\Exception $e) {
            return new LoadResponse(null, [$e->getMessage()]);
        }
    }
}
