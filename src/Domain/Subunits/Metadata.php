<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Subunits;

use Domain\Subunits\DataStorage\SubunitsRepository;

class Metadata
{
    private $repo;

    public function __construct(SubunitsRepository $repository)
    {
        $this->repo = $repository;
    }

    public function types(): array
    {
        static $types = [];
        if (!$types) {
             $types = $this->repo->types();
        }
        return $types;
    }
}
