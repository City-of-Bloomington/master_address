<?php
/**
 * @copyright 2018-2020 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Subunits;

use Domain\Subunits\DataStorage\SubunitsRepository;
use Domain\Logs\Metadata as Log;

class Metadata
{
    private $repo;

    public function __construct(SubunitsRepository $repository)
    {
        $this->repo = $repository;
    }

    public function statuses(): array { return Log::$statuses; }

    public function types(): array
    {
        static $types = [];
        if   (!$types) { $types = $this->repo->types(); }
        return $types;
    }

    public function locationTypes(): array
    {
        static $types = [];
        if   (!$types) { $types = $this->repo->locationTypes(); }
        return $types;
    }
}
