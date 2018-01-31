<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Addresses;

use Domain\Addresses\DataStorage\AddressesRepository;

class Metadata
{
    private $repo;

    public function __construct(AddressesRepository $repository)
    {
        $this->repo = $repository;
    }

    public function cities(): array
    {
        static $cities = [
            'Bedford',
            'Bloomington',
            'Clear Creek',
            'Ellettsville',
            'Gosport',
            'Harrodsburg',
            'Heltonville',
            'Martinsville',
            'Nashville',
            'Smithville',
            'Spencer',
            'Springville',
            'Stanford',
            'Stinesville',
            'Unionville'
        ];
        if (!$cities) { $cities = $this->repo->cities(); }
        return $cities;
    }

    public function directions(): array
    {
        return [
            'NORTH' => 'N',
            'EAST'  => 'E',
            'SOUTH' => 'S',
            'WEST'  => 'W'
        ];
    }

    public function streetTypes(): array
    {
        static $types;
        if (!$types) { $types = $this->repo->streetTypes(); }
        return $types;
    }

    public function subunitTypes(): array
    {
        static $types;
        if (!$types) { $types = $this->repo->subunitTypes(); }
        return $types;
    }
}
