<?php
/**
 * Bulk insert of addresses from CSV file uploads
 *
 * @copyright 2020 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Application\Addresses\Controllers;

use Application\Controller;
use Application\View;
use Application\Views\NotFoundView;

use Aura\Di\Container;

use Application\Addresses\Views\ImportView;
use Domain\Addresses\UseCases\Add\AddRequest;
use Domain\Addresses\UseCases\Import\Request as ImportRequest;
use Domain\Logs\Metadata as Log;

class ImportController extends Controller
{
    // Field order to read from CSV file
    const STATUS         =  0;
    const NUMBER_PREFIX  =  1;
    const NUMBER         =  2;
    const NUMBER_SUFFIX  =  3;
    const ADDRESS2       =  4;
    const TYPE           =  5;
    const JURISDICTION   =  6;
    const TOWNSHIP       =  7;
    const SUBDIVISION    =  8;
    const PLAT           =  9;
    const LOT_NUMBER     = 10;
    const SECTION        = 11;
    const QUARTER        = 12;
    const ZIP            = 13;
    const ZIPPLUS4       = 14;
    const NOTES          = 15;
    const LOCATION_TYPE  = 16;
    const MAILABLE       = 17;
    const OCCUPIABLE     = 18;
    const GROUP_QUARTER  = 19;
    const CONTACT        = 20;
    const CHANGE_NOTES   = 21;

    private $metadata;
    private $statuses      = [];
    private $types         = [];
    private $locationTypes = [];
    private $jurisdictions = [];
    private $townships     = [];
    private $cities        = [];
    private $quarters      = [];
    private $zips          = [];

    /**
     * Load foreign key values into lookups
     *
     * Users will be providing the string values for these foreign keys,
     * instead of the ID values.  We will need to validate the string values
     * they provide as well as convert them to the ID value for the AddRequest.
     */
    public function __construct(Container $di)
    {
        parent::__construct($di);

        $this->metadata = $this->di->get('Domain\Addresses\Metadata');

        $this->statuses = $this->metadata->statuses();
        $this->quarters = $this->metadata->quarterSections();
        $this->cities   = $this->metadata->cities();
        $this->types    = $this->metadata->types();
        foreach ($this->metadata->locationTypes() as $t) { $this->locationTypes[$t['name']] = $t['id'  ]; }
        foreach ($this->metadata->jurisdictions() as $t) { $this->jurisdictions[$t['name']] = $t['id'  ]; }
        foreach ($this->metadata->townships()     as $t) { $this->townships    [$t['name']] = $t['id'  ]; }
        foreach ($this->metadata->zipCodes()      as $t) { $this->zips         [$t['zip' ]] = $t['city']; }
    }

    public function import(array $param): View
    {
        $street_id = !empty($_REQUEST['street_id']) ? (int)$_REQUEST['street_id'] : null;
        if (!$street_id) { return new NotFoundView(); }

        $data   = null;
        $errors = null;

        if (isset($_FILES['csv_file']) && is_uploaded_file($_FILES['csv_file']['tmp_name'])) {
            $data = $this->loadCSVFile($_FILES['csv_file']['tmp_name']);
            if ($data) {
                $errors = $this->checkInputValues($data);
                if (!$errors) {
                    $addRequests = $this->generateRequests($street_id, $data);
                    $import      = $this->di->get('Domain\Addresses\UseCases\Import\Command');
                    $request     = new ImportRequest($addRequests);
                    $response    = $import($request);
                    if (!$response->errors) {
                        header('Location: '.View::generateUrl('streets.view', ['id'=>$street_id]));
                        exit();
                    }
                    foreach ($response->errors as $i=>$e) {
                        if (isset($errors[$i])) { array_merge($errors[$i], $e); }
                        else  { $errors[$i] = $e; }
                    }
                }
            }
            else { $errors = ['invalidFile']; }

            if ($errors) {
                foreach ($errors as $i => $j) {
                    foreach ($j as $e) {
                        $_SESSION['errorMessages'][] = "Line $i: $e";
                    }
                }
            }
        }

        return new ImportView(parent::streetInfo($street_id), $data, $this->metadata, $errors);
    }

    private function loadCSVFile(string $file): array
    {
        $data = [];
        if (($handle = fopen($file, "r")) !== false) {
            while (($line = fgetcsv($handle)) !== false) {
                $data[] = $line;
            }
            fclose($handle);
        }
        // Remove the header line
        unset($data[0]);

        return $data;
    }

    private function checkInputValues(array $data): array
    {
        $errors   = [];

        foreach ($data as $i => $row) {
            if ($row[self::CONTACT      ] && !is_numeric($row[self::CONTACT    ]))            { $errors[$i][] = 'invalid contact id';      }
            if ($row[self::PLAT         ] && !is_numeric($row[self::PLAT       ]))            { $errors[$i][] = 'invalid plat id';         }
            if ($row[self::SUBDIVISION  ] && !is_numeric($row[self::SUBDIVISION]))            { $errors[$i][] = 'invalid subdivision id';  }
            if ($row[self::STATUS       ] && !in_array($row[self::STATUS ], $this->statuses)) { $errors[$i][] = 'invalid status';          }
            if ($row[self::QUARTER      ] && !in_array($row[self::QUARTER], $this->quarters)) { $errors[$i][] = 'invalid quarter section'; }
            if ($row[self::TYPE         ] && !in_array($row[self::TYPE   ], $this->types   )) { $errors[$i][] = 'invalid type';            }
            if ($row[self::LOCATION_TYPE] && !in_array($row[self::LOCATION_TYPE], array_keys($this->locationTypes))) { $errors[$i][] = 'invalid location type'; }
            if ($row[self::JURISDICTION ] && !in_array($row[self::JURISDICTION ], array_keys($this->jurisdictions))) { $errors[$i][] = 'invalid jurisdiction';  }
            if ($row[self::TOWNSHIP     ] && !in_array($row[self::TOWNSHIP     ], array_keys($this->townships    ))) { $errors[$i][] = 'invalid township';      }
            if ($row[self::ZIP          ] && !in_array($row[self::ZIP          ], array_keys($this->zips         ))) { $errors[$i][] = 'invalid zip';           }
        }
        return $errors;
    }

    private function generateRequests(int $street_id, array $data): array
    {
        global $DEFAULTS;
        $requests = [];
        foreach ($data as $r) {
            $requests[] = new AddRequest($_SESSION['USER']->id, [
                'street_id'            => $street_id,
                'state'                => $DEFAULTS['state'],
                'action'               => Log::ACTION_ADD,
                'status'               => $r[self::STATUS       ],
                'street_number_prefix' => $r[self::NUMBER_PREFIX],
                'street_number'        => $r[self::NUMBER       ],
                'street_number_suffix' => $r[self::NUMBER_SUFFIX],
                'address2'             => $r[self::ADDRESS2     ],
                'address_type'         => $r[self::TYPE         ],
                'subdivision_id'       => $r[self::SUBDIVISION  ],
                'plat_id'              => $r[self::PLAT         ],
                'section'              => $r[self::SECTION      ],
                'quarter_section'      => $r[self::QUARTER      ],
                'plat_lot_number'      => $r[self::LOT_NUMBER   ],
                'zip'                  => $r[self::ZIP          ],
                'zipplus4'             => $r[self::ZIPPLUS4     ],
                'notes'                => $r[self::NOTES        ],
                'jurisdiction_id'      => $r[self::JURISDICTION ] ? $this->jurisdictions[$r[self::JURISDICTION ]] : null,
                'township_id'          => $r[self::TOWNSHIP     ] ? $this->townships    [$r[self::TOWNSHIP     ]] : null,
                'city'                 => $r[self::ZIP          ] ? $this->zips         [$r[self::ZIP          ]] : null,
                'locationType_id'      => $r[self::LOCATION_TYPE] ? $this->locationTypes[$r[self::LOCATION_TYPE]] : null,
                'mailable'             => self::bool_value($r[self::MAILABLE     ]),
                'occupiable'           => self::bool_value($r[self::OCCUPIABLE   ]),
                'group_quarter'        => self::bool_value($r[self::GROUP_QUARTER]),
                'contact_id'           => $r[self::CONTACT     ],
                'change_notes'         => $r[self::CHANGE_NOTES]
            ]);
        }
        return $requests;
    }

    private static function bool_value(string $s): bool
    {
        return in_array($s, ['y', 'Y', '1']) ? true : false;
    }
}
