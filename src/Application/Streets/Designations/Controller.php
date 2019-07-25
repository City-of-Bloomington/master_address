<?php
/**
 * @copyright 2018-2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl-3.0.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Application\Streets\Designations;

use Application\Controller as BaseController;
use Application\View;

use Domain\Streets\Designations\UseCases\Update\UpdateRequest;
use Domain\Streets\Metadata as Street;

class Controller extends BaseController
{
    public function update(array $params): View
    {
        if (!empty($_REQUEST['id'])) {

            $load   = $this->di->get('Domain\Streets\Designations\UseCases\Load\Load');
            $update = $this->di->get('Domain\Streets\Designations\UseCases\Update\Update');

            $r = $load((int)$_REQUEST['id']);
            if ($r->errors) {
                $_SESSION['errorMessages'] = $r->errors;
                return new \Application\Views\NotFoundView();
            }

            $designation = $r->designation;
            $street_id   = $designation->street_id;

            if ($designation->type_id == Street::TYPE_STREET) {
                $_SESSION['errorMessages'] = ['designations/cannotUpdateStreetType'];
                header('Location: '.View::generateUrl('streets.view', ['id' => $street_id]));
                exit();
            }

            if (isset($_POST['id'])) {
                $request  = new UpdateRequest(
                    $designation->id,
                    $_SESSION['USER']->id,
                    parent::readDate('start_date'),
                    $_POST
                );
                $response = $update($request);
                if (!$response->errors) {
                    header('Location: '.View::generateUrl('streets.view', ['id'=>$street_id]));
                    exit();
                }
                $_SESSION['errorMessages'] = $response->errors;
            }
            if (!isset($request)) {
                $request = new UpdateRequest(
                    $designation->id,
                    $_SESSION['USER']->id,
                    $designation->start_date,
                    (array)$designation
                );
            }

            return new Views\UpdateView(
                $request,
                parent::streetInfo($street_id),
                $this->di->get('Domain\Streets\Metadata'),
                !empty($_REQUEST['contact_id']) ? parent::person((int)$_REQUEST['contact_id']) : null
            );
        }
        else {
            return new \Application\Views\NotFoundView();
        }
    }
}
