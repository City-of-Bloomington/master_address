<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Locations\DataStorage;

use Aura\SqlQuery\Common\SelectInterface;
use Application\PdoRepository;

use Domain\Locations\Entities\Location;
use Domain\Locations\Entities\Sanitation;
use Domain\Streets\Metadata as Street;

class PdoLocationsRepository extends PdoRepository implements LocationsRepository
{
    use \Domain\Logs\DataStorage\StatusLogTrait;

    const TABLE = 'locations';
    const LOG_TYPE = 'location';


    /**
     * Map Location entity properties to database columns
     */
    public static $fieldmap = [
        // property  => [dbColumn info]
        'location_id'  => ['prefix'=>'l',   'dbName'=>'location_id'  ],
        'type_id'      => ['prefix'=>'l',   'dbName'=>'type_id'      ],
        'address_id'   => ['prefix'=>'l',   'dbName'=>'address_id'   ],
        'subunit_id'   => ['prefix'=>'l',   'dbName'=>'subunit_id'   ],
        'mailable'     => ['prefix'=>'l',   'dbName'=>'mailable'     ],
        'occupiable'   => ['prefix'=>'l',   'dbName'=>'occupiable'   ],
        'group_quarter'=> ['prefix'=>'l',   'dbName'=>'group_quarter'],
        'active'       => ['prefix'=>'l',   'dbName'=>'active'       ],
        'trash_day'    => ['prefix'=>'san', 'dbName'=>'trash_day'    ],
        'recycle_week' => ['prefix'=>'san', 'dbName'=>'recycle_week' ],
        'type_code'    => ['prefix'=>'t',   'dbName'=>'code'         ],
        'type_name'    => ['prefix'=>'t',   'dbName'=>'name'         ],
        'status'       => ['prefix'=>'x',   'dbName'=>'status'       ]
    ];

    public function columns(): array
    {
        static $cols = [];
        if (!$cols) {
            foreach (self::$fieldmap as $responseName=>$map) {
                $cols[] = "$map[prefix].$map[dbName] as $responseName";
            }
            $cols[] = "concat_ws(' ', sut.code, sub.identifier) as subunit";
            $cols[] = "concat_ws(' ',  a.street_number_prefix,
                                       a.street_number,
                                       a.street_number_suffix,
                                      sn.direction,
                                      sn.name,
                                      st.code,
                                      sn.post_direction) as address";
        }
        return $cols;
    }

    public function baseSelect(): SelectInterface
    {
        $select = $this->queryFactory->newSelect();
        $select->cols($this->columns())
               ->from(self::TABLE.' l')
               ->join('INNER', 'location_types       t',  't.id = l.type_id')
               ->join('LEFT',  'location_status      x',  'l.location_id=x.location_id and x.start_date <= now() and (x.end_date is null or x.end_date >= now())')
               ->join('LEFT',  'sanitation         san',  'l.location_id=san.location_id')
               ->join('INNER', 'addresses            a',  'a.id = l.address_id')
               ->join('INNER', 'streets              s',  's.id = a.street_id')
               ->join('INNER', 'street_designations sd',  's.id = sd.street_id and sd.type_id='.Street::TYPE_STREET)
               ->join('INNER', 'street_names        sn', 'sn.id = sd.street_name_id')
               ->join('LEFT',  'street_types        st', 'st.id = sn.suffix_code_id')
               ->join('LEFT',  'subunits           sub','sub.id = l.subunit_id')
               ->join('LEFT',  'subunit_types      sut','sut.id = sub.type_id');
        return $select;
    }

    //---------------------------------------------------------------
    // Read functions
    //---------------------------------------------------------------
    /**
     * Finds location rows using exact matching
     *
     * @return array  An array of Location entity objects
     */
    public function find(array $fields): array
    {
        $select = $this->baseSelect();
        foreach ($fields as $f=>$v) {
            if (array_key_exists($f, self::$fieldmap)) {
                $column = self::$fieldmap[$f]['prefix'].'.'.self::$fieldmap[$f]['dbName'];
                switch ($f) {
                    case 'mailable':
                    case 'occupiable':
                    case 'group_quarter':
                    case 'active':
                        $select->where("$column=?", $v ? 'true' : 'false');
                    break;

                    default:
                        if (empty($v)) { $select->where("$column is null"); }
                        else           { $select->where("$column=?", $v  ); }
                }
            }
        }

        $locations = [];
        $query = $this->pdo->prepare($select->getStatement());
        $query->execute($select->getBindValues());
        foreach ($query->fetchAll(\PDO::FETCH_ASSOC) as $row) {
            $locations[] = new Location($row);
        }
        return $locations;
    }

    public function sanitation(int $location_id): Sanitation
    {
        $sql = 'select * from sanitation where location_id=?';
        $query = $this->pdo->prepare($sql);
        $query->execute([$location_id]);
        $result = $query->fetchAll(\PDO::FETCH_ASSOC);

        return count($result)
            ? new Sanitation($result[0])
            : new Sanitation();
    }

    //---------------------------------------------------------------
    // Write functions
    //---------------------------------------------------------------
    /**
     * Creates a new row in the locations table.
     *
     * The locations table contains many rows for each location_id.
     * Each location_id can have multiple addresses and subunits.
     * Each location_id, address_id, subunit_id must be unique.
     * Location IDs will be generated automatically and returned.
     *
     * @return int  The new (or existing) location_id
     */
    public function assign(Location $location): int
    {
        $data = [];
        foreach (self::$fieldmap as $f=>$map) {
            if ($map['prefix'] == 'l' && $location->$f) {
                $data[$f] = $location->$f;
            }
        }

        $insert  = $this->queryFactory->newInsert();
        $insert->into(self::TABLE)->cols($data);
        $sql     = $insert->getStatement();
        $query   = $this->pdo->prepare($sql);
        $success = $query->execute($insert->getBindValues());
        if (!$success) { throw new \Exception('databaseError'); }

        return $location->location_id
             ? $location->location_id
             : (int)$this->pdo->lastInsertId('locations_location_id_seq');
    }

    /**
     * Marks an address as the active address for a location_id.
     *
     * Each location_id will have many address rows.  An address row
     * is where subunit_id is null.  Only one address row for a given
     * location_id should ever be marked as active.
     */
    public function activateAddress(int $location_id, int $address_id)
    {
        $sql = "update locations set active='false'
                where location_id=?
                  and address_id!=?
                  and subunit_id is null";
        $query = $this->pdo->prepare($sql);
        $query->execute([$location_id, $address_id]);

        $sql = "update locations set active='true'
                where location_id=?
                  and address_id=?
                  and subunit_id is null";
        $query = $this->pdo->prepare($sql);
        $query->execute([$location_id, $address_id]);
    }

    /**
     * Marks a subunit as the active subunit for a location_id.
     *
     * Each location_id will have many subunit rows.  A subunit row
     * is where subunit_id is not null.  Only one subunit row for a given
     * location_id should ever be marked as active.
     */
    public function activateSubunit(int $location_id, int $subunit_id)
    {
        $sql = "update locations set active='false'
                where location_id=?
                  and subunit_id!=?
                  and subunit_id is not null";
        $query = $this->pdo->prepare($sql);
        $query->execute([$location_id, $subunit_id]);

        $sql = "update locations set active='true'
                where location_id=?
                  and subunit_id=?";
        $query = $this->pdo->prepare($sql);
        $query->execute([$location_id, $subunit_id]);
    }

    public function updateSanitation(Sanitation $s)
    {
        $old = $this->sanitation($s->location_id);
        if ($old->location_id) {
            // Update
            $query = $this->queryFactory->newUpdate();
            $query->table('sanitation')
                  ->cols(['trash_day'    => $s->trash_day,
                          'recycle_week' => $s->recycle_week])
                  ->where('location_id=?',  $s->location_id);
        }
        else {
            // Insert
            $query  = $this->queryFactory->newInsert();
            $query->into('sanitation')->cols((array)$s);
        }
        $q = $this->pdo->prepare($query->getStatement());
        $q->execute($query->getBindValues());
    }

    //---------------------------------------------------------------
    // Metadata functions
    //---------------------------------------------------------------
    public function types(): array
    {
        $sql = "select * from location_types";
        $result = $this->pdo->query($sql);
        return $result->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function trashDays(): array
    {
        $sql = "select distinct trash_day
                from sanitation
                where trash_day is not null
                order by trash_day";
        $result = $this->pdo->query($sql);
        return $result->fetchAll(\PDO::FETCH_COLUMN);
    }

    public function recycleWeeks(): array
    {
        $sql = "select distinct recycle_week
                from sanitation
                where recycle_week is not null
                order by recycle_week";
        $result = $this->pdo->query($sql);
        return $result->fetchAll(\PDO::FETCH_COLUMN);
    }
}
