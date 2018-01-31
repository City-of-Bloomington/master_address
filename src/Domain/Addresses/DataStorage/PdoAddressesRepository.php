<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Addresses\DataStorage;

use Aura\SqlQuery\Common\SelectInterface;
use Domain\PdoRepository;
use Domain\Addresses\Entities\Address;
use Domain\Addresses\UseCases\Info\InfoRequest;
use Domain\Addresses\UseCases\Search\SearchRequest;
use Domain\Addresses\UseCases\Update\UpdateRequest;
use Domain\Townships\Entities\Township;

class PdoAddressesRepository extends PdoRepository implements AddressesRepository
{
    protected $tablename   = 'addresses';
    protected $entityClass = '\Domain\Addresses\Entities\Address';

    public static $DEFAULT_SORT = ['name'];
    public function columns(): array
    {
        return [
            'a.id',
            'a.street_number_prefix',
            'a.street_number',
            'a.street_number_suffix',
            'a.adddress2',
            'a.address_type',
            'a.street_id',
            'a.jurisdiction_id',
            'a.township_id',
            'a.subdivision_id',
            'a.plat_id',
            'a.section',
            'a.quarter_section',
            'a.plat_lot_number',
            'a.city',
            'a.state',
            'a.zip',
            'a.zipplus4',
            'a.state_plane_x',
            'a.state_plane_y',
            'a.latitude',
            'a.longitude',
            'a.usng',
            'a.notes',
            'j.jurisdiction_name',
            't.township_name',
            'sub.subdivision_name'
        ];
    }

    private function baseSelect(): SelectInterface
    {
        $select = $this->queryFactory->newSelect();
        $select->cols($this->columns())
               ->from("{$this->tablename}    as a")
               ->join('LEFT', 'townships     as t',   'a.township_id=t.id')
               ->join('LEFT', 'jurisdictions as j',   'a.jurisdiction_id=j.id')
               ->join('LEFT', 'subdivisions  as sub', 'a.subdivision_id=sub.id');
        return $select;
    }

    private static function hydrate(array $row): Address
    {
        return new Address($row);
    }

    public function load(InfoRequest $req): Address
    {
        $select = $this->baseSelect();
        $select->where('a.id=?', $req->id);

        $result = $this->performSelect($select);
        if (count($result['rows'])) {
            return self::hydrate($result['rows'][0]);
        }
        throw new \Exception('addresses/unknown');
    }

    public function search(SearchRequest $req): array
    {
        $select = $this->baseSelect();
        foreach (parent::columns() as $f) {
            if (!empty($req->$f)) {
                switch ($f) {
                    case 'street_number':
                        $select->where("$f like ?", $req->$f);
                    break;

                    default:
                        $select->where("$f=?", $req->$f);
                }
            }
        }
        $select->orderBy(self::$DEFAULT_SORT);
        $result = $this->performSelect($select, $req->itemsPerPage, $req->currentPage);

        $addresses = [];
        foreach ($result['rows'] as $r) { $addresses[] = self::hydrate($r); }
        $result['rows'] = $addresses;
        return $result;
    }

    /**
     * Saves a address and returns the ID for the address
     */
    public function save(Address $address): int
    {
        // Remove the fields from foreign key tables
        unset($address->jurisdiction_name);
        unset($address->township_name);
        unset($address->subdivision_name);

        return parent::saveEntity($address);
    }

    //---------------------------------------------------------------
    // Metadata Functions
    //---------------------------------------------------------------
    public function distinct(string $field): array
    {
        $select = $this->queryFactory->newSelect();
        $select->distinct()
               ->cols([$field])
               ->from($this->tablename)
               ->where("$field is not null")
               ->orderBy([$field]);

        $result = $this->pdo->query($select->getStatement());
        return $result->fetchAll(\PDO::FETCH_COLUMN);
    }

    public function cities(): array
    {
        return $this->distinct('city');
    }

    public function townships(): array
    {
        $result = $this->pdo->query('select id, name from townships order by name');
        return $result->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function streetTypes(): array
    {
        $result = $this->pdo->query('select * from street_types order by name');
        return $result->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function subunitTypes(): array
    {
        $result = $this->pdo->query('select * from subunit_types order by name');
        return $result->fetchAll(\PDO::FETCH_ASSOC);
    }
}
