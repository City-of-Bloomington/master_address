<?php
/**
 * @copyright 2020 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Application\Subunits\Controllers;

use Application\Subunits\Views\ImportView;
use Application\Controller;
use Application\View;

use Aura\Di\Container;

use Domain\Subunits\UseCases\Add\AddRequest;
use Domain\Subunits\UseCases\Import\Request as ImportRequest;

class ImportController extends Controller
{
    // Field order to read from CSV file
    const STATUS        = 0;
    const TYPE          = 1;
    const IDENTIFIER    = 2;
    const NOTES         = 3;
    const LOCATION_TYPE = 4;
    const MAILABLE      = 5;
    const OCCUPIABLE    = 6;
    const GROUP_QUARTER = 7;
    const CONTACT       = 8;
    const CHANGE_NOTES  = 9;

    private $metadata;
    private $statuses      = [];
    private $types         = [];
    private $locationTypes = [];

    public function __construct(Container $di)
    {
        parent::__construct($di);

        $this->metadata = $this->di->get('Domain\Subunits\Metadata');
        $this->statuses = $this->metadata->statuses();
        foreach ($this->metadata->types()         as $t) { $this->types        [$t['code']] = $t['id']; }
        foreach ($this->metadata->locationTypes() as $t) { $this->locationTypes[$t['name']] = $t['id']; }
    }

    public function import(array $params): View
    {
        $address_id = !empty($_REQUEST['address_id']) ? (int)$_REQUEST['address_id'] : null;
        if (!$address_id) { return new \Application\Views\NotFoundView(); }

        $addressInfo = parent::addressInfo($address_id);
        $data        = null;
        $errors      = null;

        if (isset($_FILES['csv_file']) && is_uploaded_file($_FILES['csv_file']['tmp_name'])) {
            $data = $this->loadCSVFile($_FILES['csv_file']['tmp_name']);

            if ($data) {
                $errors = $this->checkInputValues($data);
                if (!$errors) {
                    $addRequests = $this->generateRequests($address_id, $data);
                    $import      = $this->di->get('Domain\Subunits\UseCases\Import\Command');
                    $request     = new ImportRequest($addRequests);
                    $response    = $import($request);
                    if (!$response->errors) {
                        header('Location: '.View::generateUrl('addresses.view', ['id'=>$address_id]));
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

        return new ImportView($addressInfo, $data, $this->metadata, $errors);
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
            if (!is_numeric($row[self::CONTACT]))               { $errors[$i][] = 'invalid contact id';    }
            if (!in_array($row[self::STATUS], $this->statuses)) { $errors[$i][] = 'invalid status';        }
            if (!in_array($row[self::TYPE],          array_keys($this->types        ))) { $errors[$i][] = 'invalid subunit type';  }
            if (!in_array($row[self::LOCATION_TYPE], array_keys($this->locationTypes))) { $errors[$i][] = 'invalid location type'; }
        }
        return $errors;
    }

    private function generateRequests(int $address_id, array $data): array
    {
        $requests = [];
        foreach ($data as $r) {
            $requests[] = new AddRequest($_SESSION['USER']->id, [
                'address_id'      => $address_id,
                'status'          => $r[self::STATUS],
                'type_id'         => $this->types[$r[self::TYPE]],
                'identifier'      => $r[self::IDENTIFIER],
                'notes'           => $r[self::NOTES],
                'locationType_id' => $this->locationTypes[$r[self::LOCATION_TYPE]],
                'mailable'        => self::bool_value($r[self::MAILABLE     ]),
                'occupiable'      => self::bool_value($r[self::OCCUPIABLE   ]),
                'group_quarter'   => self::bool_value($r[self::GROUP_QUARTER]),
                'contact_id'      => $r[self::CONTACT],
                'change_notes'    => $r[self::CHANGE_NOTES]
            ]);
        }
        return $requests;
    }

    private static function bool_value(string $s): bool
    {
        return in_array($s, ['y', 'Y', '1']) ? true : false;
    }
}
