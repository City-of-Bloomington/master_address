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

use Domain\ChangeLogs\ChangeLogEntry;
use Domain\Townships\Entities\Township;

class PdoAddressesRepository extends PdoRepository implements AddressesRepository
{
    const TYPE_STREET = 1;

    protected $tablename   = 'addresses';
    protected $entityClass = '\Domain\Addresses\Entities\Address';

    public static $DEFAULT_SORT = [
        'street_name',
        'street_suffix_code',
        'street_direction',
        'street_post_direction',
        'street_number'
    ];

    /**
     * Maps response fieldnames to the names used in the database
     */
    public static $fieldmap = [
        'id'                   => ['prefix'=>'a', 'dbName'=>'id'                  ],
        'street_number_prefix' => ['prefix'=>'a', 'dbName'=>'street_number_prefix'],
        'street_number'        => ['prefix'=>'a', 'dbName'=>'street_number'       ],
        'street_number_suffix' => ['prefix'=>'a', 'dbName'=>'street_number_suffix'],
        'adddress2'            => ['prefix'=>'a', 'dbName'=>'adddress2'           ],
        'address_type'         => ['prefix'=>'a', 'dbName'=>'address_type'        ],
        'street_id'            => ['prefix'=>'a', 'dbName'=>'street_id'           ],
        'jurisdiction_id'      => ['prefix'=>'a', 'dbName'=>'jurisdiction_id'     ],
        'township_id'          => ['prefix'=>'a', 'dbName'=>'township_id'         ],
        'subdivision_id'       => ['prefix'=>'a', 'dbName'=>'subdivision_id'      ],
        'plat_id'              => ['prefix'=>'a', 'dbName'=>'plat_id'             ],
        'section'              => ['prefix'=>'a', 'dbName'=>'section'             ],
        'quarter_section'      => ['prefix'=>'a', 'dbName'=>'quarter_section'     ],
        'plat_lot_number'      => ['prefix'=>'a', 'dbName'=>'plat_lot_number'     ],
        'city'                 => ['prefix'=>'a', 'dbName'=>'city'                ],
        'state'                => ['prefix'=>'a', 'dbName'=>'state'               ],
        'zip'                  => ['prefix'=>'a', 'dbName'=>'zip'                 ],
        'zipplus4'             => ['prefix'=>'a', 'dbName'=>'zipplus4'            ],
        'state_plane_x'        => ['prefix'=>'a', 'dbName'=>'state_plane_x'       ],
        'state_plane_y'        => ['prefix'=>'a', 'dbName'=>'state_plane_y'       ],
        'latitude'             => ['prefix'=>'a', 'dbName'=>'latitude'            ],
        'longitude'            => ['prefix'=>'a', 'dbName'=>'longitude'           ],
        'usng'                 => ['prefix'=>'a', 'dbName'=>'usng'                ],
        'notes'                => ['prefix'=>'a', 'dbName'=>'notes'               ],

        'jurisdiction_name'    => ['prefix'=>'j',   'dbName'=>'name'],
        'township_name'        => ['prefix'=>'t',   'dbName'=>'name'],
        'plat_name'            => ['prefix'=>'p',   'dbName'=>'name'],
        'subdivision_name'     => ['prefix'=>'sub', 'dbName'=>'name'],

        'street_direction'      => ['prefix'=>'sn', 'dbName'=>'direction'     ],
        'street_name'           => ['prefix'=>'sn', 'dbName'=>'name'          ],
        'street_post_direction' => ['prefix'=>'sn', 'dbName'=>'post_direction'],
        'street_suffix_code'    => ['prefix'=>'st', 'dbName'=>'code'          ],

        'status' => ['prefix'=>'status', 'dbName'=>'status']
    ];

    public function columns(): array
    {
        static $cols = [];
        if (!$cols) {
            foreach (self::$fieldmap as $responseName=>$map) {
                $cols[] = "$map[prefix].$map[dbName] as $responseName";
            }
        }
        return $cols;
    }

    private function baseSelect(): SelectInterface
    {
        $select = $this->queryFactory->newSelect();
        $select->cols($this->columns())
               ->from("{$this->tablename}    a")
               ->join('LEFT', 'townships     t',   'a.township_id=t.id')
               ->join('LEFT', 'jurisdictions j',   'a.jurisdiction_id=j.id')
               ->join('LEFT', 'plats         p',   'a.plat_id=p.id')
               ->join('LEFT', 'subdivisions  sub', 'a.subdivision_id=sub.id')
               ->join('LEFT', 'streets             s',  'a.street_id=s.id')
               ->join('LEFT', 'street_designations sd', 's.id=sd.street_id and sd.type_id='.self::TYPE_STREET)
               ->join('LEFT', 'street_names        sn', 'sd.street_name_id=sn.id')
               ->join('LEFT', 'street_types        st', 'sn.suffix_code_id=st.id')
               ->join('LEFT', 'address_status  status', 'a.id=status.address_id and status.start_date <= CURRENT_DATE and (status.end_date is null or status.end_date >= CURRENT_DATE)');

        return $select;
    }

    private static function hydrateAddress(array $row): Address
    {
        return new Address($row);
    }

    public function load(InfoRequest $req): Address
    {
        $select = $this->baseSelect();
        $select->where('a.id=?', $req->id);

        $result = $this->performSelect($select);
        if (count($result['rows'])) {
            return self::hydrateAddress($result['rows'][0]);
        }
        throw new \Exception('addresses/unknown');
    }

    public function search(SearchRequest $req): array
    {
        $select = $this->baseSelect();
        foreach (parent::columns() as $f) {
            if (!empty($req->$f)) {
                $column = self::$fieldmap[$f]['prefix'].'.'.self::$fieldmap[$f]['dbName'];
                switch ($f) {
                    case 'street_number':
                        // Postgres requires converting int to varchar before
                        // doing a like comparison.
                        // Unfortunately, the Aura SqlQuery butchers the ANSI-92
                        // cast(street_number as varchar).  So, for now, we're
                        // using the Postgres specific ::varchar syntax for
                        // type casting.
                        $select->where("$column::varchar like ?", "{$req->$f}%");
                    break;
                    case 'street_name':
                        $select->where("$column like ?", "{$req->$f}%");
                    break;

                    default:
                        $select->where("$column=?", $req->$f);
                }
            }
        }
        $select->orderBy(self::$DEFAULT_SORT);
        $result = $this->performSelect($select, $req->itemsPerPage, $req->currentPage);

        $addresses = [];
        foreach ($result['rows'] as $r) { $addresses[] = self::hydrateAddress($r); }
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

    public function changeLog(int $address_id): array
    {
        $changeLog = [];
        $sql = "select l.address_id as entity_id,
                       l.id, l.person_id, l.contact_id, l.action_date, l.action, l.notes,
                       p.firstname as  person_firstname, p.lastname as  person_lastname,
                       c.firstname as contact_firstname, c.lastname as contact_lastname
                from address_change_log l
                left join people        p on l.person_id=p.id
                left join people        c on l.contact_id=p.id
                where address_id=?";

        $query = $this->pdo->prepare($sql);
        $query->execute([$address_id]);
        foreach ($query->fetchAll(\PDO::FETCH_ASSOC) as $row) {
            $changeLog[] = ChangeLogEntry::hydrate($row);
        }
        return $changeLog;
    }

    //---------------------------------------------------------------
    // Metadata Functions
    //---------------------------------------------------------------
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
