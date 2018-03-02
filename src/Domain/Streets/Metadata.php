<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Streets;

use Domain\Streets\DataStorage\StreetsRepository;

class Metadata
{
    private $repo;

    public function __construct(StreetsRepository $repository)
    {
        $this->repo = $repository;
    }

    public function statuses(): array
    {
        return ['current', 'corrected', 'proposed', 'retired'];
    }

    public function types(): array
    {
        static $types = [];
        if (!$types) { $types = $this->repo->types(); }
        return $types;
    }

    public function towns(): array
    {
        static $towns = [];
        if (!$towns) { $towns = $this->repo->towns(); }
        return $towns;
    }

    public function designationTypes(): array
    {
        static $types = [];
        if (!$types) { $types = $this->repo->designationTypes(); }
        return $types;
    }
}
