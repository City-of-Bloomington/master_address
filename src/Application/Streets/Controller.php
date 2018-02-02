<?php
/**
 * @copyright 2017-2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Application\Streets;

use Application\Controller as BaseController;
use Application\View;

use Domain\Addresses\Parser;
use Domain\Streets\Entities\Street;
use Domain\Streets\UseCases\Info\InfoRequest;
use Domain\Streets\UseCases\Search\SearchRequest;
use Domain\Streets\UseCases\Update\UpdateRequest;
use Domain\Streets\UseCases\Update\UpdateResponse;

class Controller extends BaseController
{
    const ITEMS_PER_PAGE = 20;

    /**
     * Converts Parser fieldnames to SearchRequest fieldnames
     */
    private static function extractStreetFields(array $parse): array
    {
        $query = [];
        if (!empty($parse[Parser::DIRECTION     ])) { $query['direction'     ] = $parse[Parser::DIRECTION     ]; }
        if (!empty($parse[Parser::STREET_NAME   ])) { $query['name'          ] = $parse[Parser::STREET_NAME   ]; }
        if (!empty($parse[Parser::POST_DIRECTION])) { $query['post_direction'] = $parse[Parser::POST_DIRECTION]; }
        if (!empty($parse[Parser::STREET_TYPE   ])) { $query['suffix_code'   ] = $parse[Parser::STREET_TYPE   ]; }
        return $query;
    }

    public function index(array $params)
    {
		$page   =  !empty($_GET['page']) ? (int)$_GET['page'] : 1;
        $search = $this->di->get('Domain\Streets\UseCases\Search\Search');
        $parser = $this->di->get('Domain\Addresses\Parser');

        $query  = !empty($_GET['street'])
                ? self::extractStreetFields($parser($_GET['street']))
                : [];
        $response = $search(new SearchRequest($query, null, self::ITEMS_PER_PAGE, $page));

        return new Views\SearchView($response, self::ITEMS_PER_PAGE, $page);
    }

    public function view(array $params)
    {
        if (!empty($_REQUEST['id'])) {
            $streetInfo    = $this->di->get('Domain\Streets\UseCases\Info\Info');
            $addressSearch = $this->di->get('Domain\Addresses\UseCases\Search\Search');
            $changeLog     = $this->di->get('Domain\Streets\UseCases\ChangeLog\ChangeLog');

            $infoRequest = new InfoRequest((int)$_REQUEST['id']);

            $info = $streetInfo($infoRequest);
            if ($info->street) {
                $addresses = $addressSearch(new \Domain\Addresses\UseCases\Search\SearchRequest([
                    'street_id'=>$info->street->id
                ]));
                $log = $changeLog(new \Domain\ChangeLogs\ChangeLogRequest($info->street->id));
                return new Views\InfoView($info, $addresses, $log);
            }
            else {
                $_SESSION['errorMessages'] = $info->errors;
            }
        }
        return new \Application\Views\NotFoundView();
    }

    public function verify(array $params)
    {
        if (!empty($_REQUEST['id'])) {
            try { $street = new Street($_REQUEST['id']); }
            catch (\Exception $e) { $_SESSION['errorMessages'][] = $e; }
        }

        if (isset($street)) {
            if (isset($_POST['id'])) {
                $verification = new Messages\VerifyRequest($street, $_SESSION['USER'], $_POST['notes']);
                try {
                    $street->verify($verification);
                    header('Location: '.View::generateUrl('streets.view', ['id'=>$street->getId()]));
                    exit();
                }
                catch (\Exception $e) { $_SESSION['errorMessages'][] = $e; }
            }
            else {
                $verification = new Messages\VerifyRequest($street, $_SESSION['USER']);
            }
            return new Views\Actions\VerifyView(['request'=>$verification]);
        }
        return new \Application\Views\NotFoundView();
    }

    public function correct(array $params)
    {
        if (!empty($_REQUEST['id'])) {
            try { $street  = new Street($_REQUEST['id']); }
            catch (\Exception $e) { $_SESSION['errorMessages'][] = $e; }
        }

        if (isset($street)) {
            if (isset($_POST['id'])) {
                $correction = new Messages\CorrectRequest($street, $_SESSION['USER'], $_POST);
                try {
                    $street->correct($correction);
                    header('Location: '.View::generateUrl('streets.view', ['id'=>$street->getId()]));
                    exit();
                }
                catch (\Exception $e) { $_SESSION['errorMessages'][] = $e; }
            }
            else {
                $correction = new Messages\CorrectRequest($street, $_SESSION['USER']);
            }
            return new Views\Actions\CorrectView(['request'=>$correction]);
        }
        return new \Application\Views\NotFoundView();
    }

    public function changeStatus(array $params)
    {
        if (!empty($_REQUEST['id'])) {
            try { $street  = new Street($_REQUEST['id']); }
            catch (\Exception $e) { $_SESSION['errorMessages'][] = $e; }
        }

        if (isset($street)) {
            if (isset($_POST['id'])) {
                $change = new Messages\StatusChangeRequest($street, $_SESSION['USER'], $_POST);
                try {
                    $street->changeStatus($change);
                    header('Location: '.View::generateUrl('streets.view', ['id'=>$street->getId()]));
                    exit();
                }
                catch (\Exception $e) { $_SESSION['errorMessages'][] = $e; }
            }
            else {
                $change = new Messages\StatusChangeRequest($street, $_SESSION['USER']);
            }
            return new Views\Actions\StatusChangeView(['request'=>$change]);
        }
        return new \Application\Views\NotFoundView();
    }
}
