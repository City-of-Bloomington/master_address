<?php
/**
 * @copyright 2018-2021 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Application\Sanitation;

use Application\Controller as BaseController;
use Application\View;

use Domain\Locations\Entities\Sanitation;
use Domain\Locations\UseCases\Find\FindRequest;

class Controller extends BaseController
{
    public function update(array $vars): View
    {
        $location_id = !empty($_REQUEST['location_id']) ? (int)$_REQUEST['location_id'] : null;
        if (isset($_REQUEST['return_url'])) { $_SESSION['return_url'] = $_REQUEST['return_url']; }

        if ($location_id) {
            $sanitation = new Sanitation($_REQUEST);
            $return_url = $_SESSION['return_url'];

            if (isset($_POST['location_id'])) {
                $update   = $this->di->get('Domain\Locations\Sanitation\UseCases\Update\Update');
                $response = $update($sanitation);
                if (!$response->errors) {
                    unset($_SESSION['return_url']);

                    header("Location: $return_url");
                    exit();
                }
            }

            // Load default values from the database
            $load = $this->di->get('Domain\Locations\Sanitation\UseCases\Load\Load');
            $x    = $load($location_id);
            if ($x->sanitation) {
                foreach ($x->sanitation as $k=>$v) {
                    if (!$sanitation->$k) { $sanitation->$k = $v; }
                }
            }

            // Load all the locations with this location_id
            $find = $this->di->get('Domain\Locations\UseCases\Find\Find');
            $res  = $find(new FindRequest(['location_id'=>$location_id]));
            return new Views\UpdateView(
                $sanitation,
                $this->di->get('Domain\Locations\Metadata'),
                $res->locations,
                $return_url
            );
        }
        return new \Application\Views\NotFoundView();
    }
}
