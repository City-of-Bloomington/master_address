<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Subdivisions;

use Domain\Subdivisions\DataStorage\SubdivisionsRepository;

class Metadata
{
    private $repo;

    public function __construct(SubdivisionsRepository $repository)
    {
        $this->repo = $repository;
    }

    public function statuses(): array
    {
        return ['CURRENT', 'RENAMED'];
    }

    public function phases(): array
    {
        return [1, 2, 3, 4, 5, 6, 8];
    }

    public function townships(): array
    {
        static $townships;
        if (!$townships) { $townships = $this->repo->townships(); }
        return $townships;
    }
}
