<?php
/**
 * @copyright 2017-2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Application\Addresses;

use Application\Controller as BaseController;
use Application\View;

use Domain\Addresses\Parser;
use Domain\Addresses\Entities\Address;
use Domain\Addresses\UseCases\Info\InfoRequest;
use Domain\Addresses\UseCases\Search\SearchRequest;
use Domain\Addresses\UseCases\Search\SearchResponse;

use Domain\ChangeLogs\ChangeLogRequest;

class Controller extends BaseController
{
    const ITEMS_PER_PAGE = 20;

    /**
     * Converts Parser fieldnames to SearchRequest fieldnames
     */
    private static function translateFields(array $parse): array
    {
        $query = [];
        foreach ($parse as $k=>$v) {
            if (!empty($v)) {
                switch ($k) {
                    case Parser::DIRECTION:      $query['street_direction'     ] = $v; break;
                    case Parser::STREET_NAME:    $query['street_name'          ] = $v; break;
                    case Parser::POST_DIRECTION: $query['street_post_direction'] = $v; break;
                    case Parser::STREET_TYPE:    $query['street_suffix_code'   ] = $v; break;
                    default:
                        $query[$k] = $v;
                }
            }
        }
        return $query;
    }

    public function index(array $params)
    {
		$page   =  !empty($_GET['page']) ? (int)$_GET['page'] : 1;
        $search = $this->di->get('Domain\Addresses\UseCases\Search\Search');
        $parser = $this->di->get('Domain\Addresses\Parser');

        $query  = !empty($_GET['address'])
                ? self::translateFields($parser($_GET['address']))
                : null;
        $res    = $query
                ? $search(new SearchRequest($query, null, self::ITEMS_PER_PAGE, $page))
                : new SearchResponse();

        return new Views\SearchView($res, self::ITEMS_PER_PAGE, $page);
    }

    public function parse(array $params)
    {
    }

    public function view(array $params)
    {
        if (!empty($_GET['id'])) {
            $addressInfo = $this->di->get('Domain\Addresses\UseCases\Info\Info');

            $info = $addressInfo(new InfoRequest((int)$_GET['id']));
            if ($info->address) {
                return new Views\InfoView($info);
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
            try { $address = new Address($_REQUEST['id']); }
            catch (\Exception $e) { $_SESSION['errorMessages'][] = $e; }
        }

        if (isset($address)) {
            if (isset($_POST['id'])) {
                $verification = new Messages\VerifyRequest($address, $_SESSION['USER'], $_POST['notes']);
                try {
                    $address->verify($verification);
                    header('Location: '.View::generateUrl('addresses.view', ['id'=>$address->getId()]));
                    exit();
                }
                catch (\Exception $e) { $_SESSION['errorMessages'][] = $e; }
            }
            else {
                $verification = new Messages\VerifyRequest($address, $_SESSION['USER']);
            }
            return new Views\VerifyView(['request'=>$verification]);
        }
        return new \Application\Views\NotFoundView();
    }
}
