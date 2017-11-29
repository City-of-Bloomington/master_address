<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Application\Streets\Designations;

use Blossom\Classes\View;

class DesignationsController
{
    public function update()
    {
        if (!empty($_REQUEST['id'])) {
            try { $designation = new Designation($_REQUEST['id']); }
            catch (\Exception $e) { $_SESSION['errorMessages'][] = $e; }
        }
        elseif (!empty($_REQUEST['street_id'])) {
            try {
                $designation = new Designation();
                $designation->setStreet_id((int)$_REQUEST['street_id']);
            }
            catch (\Exception $e) { $_SESSION['errorMessages'][] = $e; }
        }

        if (isset($designation)) {
            if (isset($_POST['street_id'])) {
                $_POST['user_id'] = $_SESSION['USER']->getId();

                try {
                    $request = new Messages\UpdateRequest($_POST);
                    $designation->update($request);
                    header('Location: '.View::generateUrl('streets.view', ['id'=>$designation->getStreet_id()]));
                    exit();
                }
                catch (\Exception $e) { $_SESSION['errorMessages'][] = $e; }
            }
            else {
                $d = $designation->toArray();
                if (isset($d['start_date'])) { $d['startDate'] = $d['start_date']; }
                if (isset($d['end_date'  ])) { $d['endDate'  ] = $d['end_date'  ]; }

                $request = new Messages\UpdateRequest($d);
            }

            return new Views\UpdateView(['request'=>$request]);
        }

        return new \Application\Views\NotFoundView();
    }
}
