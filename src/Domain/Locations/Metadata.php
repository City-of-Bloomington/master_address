<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Locations;

use Domain\Locations\DataStorage\LocationsRepository;

class Metadata
{
    private $repo;

    public function __construct(LocationsRepository $repository)
    {
        $this->repo = $repository;
    }

    public function types(): array
    {
        static $a;
        if   (!$a) { $a = $this->repo->types(); }
        return $a;
    }
}
