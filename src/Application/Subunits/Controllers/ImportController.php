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

use Domain\Subunits\UseCases\Add\AddRequest;

class ImportController extends Controller
{
    public function import(array $params): View
    {
        $address_id = !empty($_REQUEST['address_id']) ? (int)$_REQUEST['address_id'] : null;
        if (!$address_id) {
            return new \Application\Views\NotFoundView();
        }


        $request     = new Request($address_id, $_SESSION['USER']->id, $_REQUEST);
        $addressInfo = parent::addressInfo($address_id);

        if (is_uploaded_file($_FILES['csv_file']['tmp_name'])) {
            $data = self::loadCSVFile($_FILES['csv_file']['tmp_name']);
            if (!$data) {
                $_SESSION['errorMessages'] = ['invalidFile'];
            }
        }

        return new ImportView($request, $addressInfo);
    }

    private static function loadCSVFile(string $file): array
    {
        $data = [];
        if (($handle = fopen($file, "r")) !== false) {
            while (($line = fgetcsv($handle)) !== false) {
                $data[] = new AddRequest($_SESSION['USER']->id, $line);
            }
            fclose($handle);
        }
        return $data;
    }
}
