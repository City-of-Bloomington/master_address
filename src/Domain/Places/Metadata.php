<?php
/**
 * @copyright 2021 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Places;

use Domain\Places\DataStorage\PlacesRepository;

class Metadata
{
    private $repo;

    public function __construct(PlacesRepository $repository)
    {
        $this->repo = $repository;
    }

    public function statuses(): array
    {
        return ['Current', 'Pending', 'Removed', 'Retired' ];
    }

    public function categories(): array
    {
        static $a;
        if   (!$a) { $a = $this->repo->categories(); }
        return $a;
    }

    public function entities(): array
    {
        static $a;
        if   (!$a) { $a = $this->repo->entities(); }
        return $a;
    }

    public function types(): array
    {
        static $a;
        if   (!$a) { $a = $this->repo->types(); }
        return $a;
    }

    public function vicinities(): array
    {
        static $a;
        if   (!$a) { $a = $this->repo->vicinities(); }
        return $a;
    }
}
