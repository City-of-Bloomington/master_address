<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Addresses;

use Domain\Addresses\DataStorage\AddressesRepository;
use Domain\Logs\Metadata as Log;

class Metadata
{
    private $repo;

    public function __construct(AddressesRepository $repository)
    {
        $this->repo = $repository;
    }

    public static $requiredFields = [
        'street_id', 'street_number', 'zip', 'section',
        'address_type', 'jurisdiction_id', 'township_id'
    ];

    public function trash_days()    { return ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']; }
    public function recycle_weeks() { return ['A', 'B']; }
    public function actions()       { return ['correct','update','readdress','unretire','reassign','retire','verify']; }
    public function statuses()      { return Log::$statuses; }

    public static $directions = [
        'NORTH' => 'N',
        'EAST'  => 'E',
        'SOUTH' => 'S',
        'WEST'  => 'W'
    ];

    public function cities(): array
    {
        static $a;
        if   (!$a) { $a = $this->repo->cities(); }
        return $a;
    }

    public function jurisdictions(): array
    {
        static $a;
        if   (!$a) { $a = $this->repo->jurisdictions(); }
        return $a;
    }

    public function quarterSections(): array
    {
        return ['NE', 'NW', 'SE', 'SW'];
    }

    public function sections(): array
    {
        static $a;
        if   (!$a) { $a = $this->repo->sections(); }
        return $a;
    }

    public function streetTypes(): array
    {
        static $a;
        if   (!$a) { $a = $this->repo->streetTypes(); }
        return $a;
    }

    public function subunitTypes(): array
    {
        static $a;
        if   (!$a) { $a = $this->repo->subunitTypes(); }
        return $a;
    }

    public function townships(): array
    {
        static $a;
        if   (!$a) { $a = $this->repo->townships(); }
        return $a;
    }

    public function types(): array
    {
        static $a;
        if   (!$a) { $a = $this->repo->types(); }
        return $a;
    }

    public function zipCodes(): array
    {
        static $a;
        if   (!$a) { $a = $this->repo->zipCodes(); }
        return $a;
    }

    public function locationTypes(): array
    {
        static $a;
        if   (!$a) { $a = $this->repo->locationTypes(); }
        return $a;
    }
}
