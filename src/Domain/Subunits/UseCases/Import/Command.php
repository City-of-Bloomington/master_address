<?php
/**
 * @copyright 2020 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Subunits\UseCases\Import;

class Command
{
    private $repo;

    public function __construct(SubunitsRepository $repository)
    {
        $this->repo = $repository;
    }

    public function __invoke(Request $req): Response
    {
    }
}
