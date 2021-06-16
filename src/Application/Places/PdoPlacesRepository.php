<?php
/**
 * @copyright 2021 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Application\Places;

use Application\PdoRepository;
use Application\Locations\PdoLocationsRepository;

use Aura\SqlQuery\Common\SelectInterface;

use Domain\Places\DataStorage\PlacesRepository;
use Domain\Places\Entities\Place;


class PdoPlacesRepository extends PdoRepository implements PlacesRepository
{
    public const TABLE = 'place.places';

    public static $DEFAULT_SORT = ['name'];
    public static $fieldmap = [
        'id'                => ['prefix'=>'p', 'dbName'=>'id'                  ],
        'name'              => ['prefix'=>'p', 'dbName'=>'place_name'          ],
        'short_name'        => ['prefix'=>'p', 'dbName'=>'short_name'          ],
        'status'            => ['prefix'=>'p', 'dbName'=>'status'              ],
        'landmark_flag'     => ['prefix'=>'p', 'dbName'=>'landmark_flag'       ],
        'vicinity'          => ['prefix'=>'p', 'dbName'=>'vicinity'            ],
        'dispatch_citycode' => ['prefix'=>'p', 'dbName'=>'dispatch_citycode'   ],
        'location_id'       => ['prefix'=>'p', 'dbName'=>'address_location_id' ],
        'description'       => ['prefix'=>'p', 'dbName'=>'location_description'],
        'x'                 => ['prefix'=>'p', 'dbName'=>'x_coordinate'        ],
        'y'                 => ['prefix'=>'p', 'dbName'=>'y_coordinate'        ],
        'latitude'          => ['prefix'=>'p', 'dbName'=>'latitude'            ],
        'longitude'         => ['prefix'=>'p', 'dbName'=>'longitude'           ],
        'entity_id'         => ['prefix'=>'p', 'dbName'=>'entity_id'           ],
        'category_id'       => ['prefix'=>'p', 'dbName'=>'category_id'         ],
        'type'              => ['prefix'=>'p', 'dbName'=>'place_type'          ],
        'map_label1'        => ['prefix'=>'p', 'dbName'=>'map_label1'          ],
        'map_label2'        => ['prefix'=>'p', 'dbName'=>'map_label2'          ],
        'comments'          => ['prefix'=>'p', 'dbName'=>'comments'            ],
        'publish_flag'      => ['prefix'=>'p', 'dbName'=>'publish_flag'        ],
        'subplace_flag'     => ['prefix'=>'p', 'dbName'=>'subplace_flag'       ]
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
               ->from(self::TABLE.' p');

        return $select;
    }

    private static function hydrate(array $row): Place
    {
        return new Place($row);
    }

    public function load(int $place_id): Place
    {
        $select = $this->baseSelect();
        $select->where('p.id=?', $place_id);

        $result = parent::performSelect($select);
        if (count($result['rows'])) {
            return self::hydrate($result['rows'][0]);
        }
        throw new \Exception('places/unknown');
    }

    /**
     * Find places with exact matching of text fields
     */
    public function find(array $fields, ?array $order=null, ?int $itemsPerPage=null, ?int $currentPage=null): array
    {
        $select  = $this->baseSelect();
        $select->cols($this->columns);
        foreach ($fields as $f=>$v) {
            if (!empty($v)) {
                if (array_key_exists($f, self::$fieldmap)) {
                    $column = self::$fieldmap[$f]['prefix'].'.'.self::$fieldmap[$f]['dbName'];
                    switch ($f) {
                        case 'landmark_flag':
                        case 'publish_flag':
                        case 'subplace_flag':
                            if ($v) { $select->where("$column=?",  'Y'); }
                            else    { $select->where("$column!=?", 'Y'); }
                        break;

                        default:
                            $select->where("$column=?", $v);
                    }
                }
            }
        }
        return $this->doSelect($select, $order, $itemsPerPage, $currentPage);
    }

    /**
     * Find places with wildcard matching of text fields
     */
    public function search(array $fields, ?array $order=null, ?int $itemsPerPage=null, ?int $currentPage=null): array
    {
        $select  = $this->baseSelect();
        $select->cols($this->columns());
        foreach ($fields as $f=>$v) {
            if (!empty($v)) {
                if (array_key_exists($f, self::$fieldmap)) {
                    $column = self::$fieldmap[$f]['prefix'].'.'.self::$fieldmap[$f]['dbName'];
                    switch ($f) {
                        case 'landmark_flag':
                        case 'publish_flag':
                        case 'subplace_flag':
                            if ($v) { $select->where("$column=?",  'Y'); }
                            else    { $select->where("$column!=?", 'Y'); }
                        break;

                        case 'name':
                        case 'short_name':
                        case 'dispatch_citycode':
                        case 'description':
                        case 'map_label1':
                        case 'map_label2':
                        case 'comments':
                            $select->where("$column like ?", "%$v%");
                        break;

                        default:
                            $select->where("$column=?", $v);
                    }
                }
            }
        }
        return $this->doSelect($select, $order, $itemsPerPage, $currentPage);
    }

    private function doSelect(SelectInterface $select, ?array $order=null, ?int $itemsPerPage=null, ?int $currentPage=null): array
    {
        $select->orderBy(self::$DEFAULT_SORT);
        $result = parent::performSelect($select, $itemsPerPage, $currentPage);

        $places = [];
        foreach ($result['rows'] as $r) { $places[] = self::hydrate($r); }
        $result['rows'] = $places;
        return $result;
    }

    public function locations(int $location_id): array
    {
        $locationsRepo = new PdoLocationsRepository($this->pdo);
        return $locationsRepo->find(['location_id' => $location_id]);
    }
}
