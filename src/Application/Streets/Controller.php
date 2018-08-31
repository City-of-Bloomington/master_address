<?php
/**
 * @copyright 2017-2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Application\Streets;

use Application\Controller as BaseController;
use Application\View;

use Domain\Addresses\UseCases\Parse\Parse;
use Domain\Addresses\UseCases\Parse\ParseResponse;
use Domain\Streets\Entities\Street;
use Domain\Streets\UseCases\Alias\AliasRequest;
use Domain\Streets\UseCases\Correct\CorrectRequest;
use Domain\Streets\UseCases\Retire\RetireRequest;
use Domain\Streets\UseCases\Search\SearchRequest;
use Domain\Streets\UseCases\Search\SearchResponse;
use Domain\Streets\UseCases\Verify\VerifyRequest;

class Controller extends BaseController
{
    const ITEMS_PER_PAGE = 20;

    /**
     * Converts Parser fieldnames to SearchRequest fieldnames
     */
    public static function extractStreetFields(ParseResponse $parse): array
    {
        $query = [];
        if (!empty($parse->{Parse::DIRECTION     })) { $query['direction'     ] = $parse->{Parse::DIRECTION     }; }
        if (!empty($parse->{Parse::STREET_NAME   })) { $query['name'          ] = $parse->{Parse::STREET_NAME   }; }
        if (!empty($parse->{Parse::POST_DIRECTION})) { $query['post_direction'] = $parse->{Parse::POST_DIRECTION}; }
        if (!empty($parse->{Parse::STREET_TYPE   })) { $query['suffix_code'   ] = $parse->{Parse::STREET_TYPE   }; }
        return $query;
    }

    /**
     * Search screen for streets
     */
    public function index(array $params)
    {
		$page   =  !empty($_GET['page']) ? (int)$_GET['page'] : 1;
        $search = $this->di->get('Domain\Streets\UseCases\Search\Search');
        $parser = $this->di->get('Domain\Addresses\UseCases\Parse\Parse');

        $query  = !empty($_GET['street'])
                ? self::extractStreetFields($parser($_GET['street']))
                : [];
        if (!empty($_GET['town_id'])) { $query['town_id'] = (int)$_GET['town_id']; }
        if (!empty($_GET['status' ])) { $query['status' ] =      $_GET['status' ]; }

        $res    = $query
                ? $search(new SearchRequest($query, null, self::ITEMS_PER_PAGE, $page))
                : new SearchResponse();

        return new Views\SearchView($res,
                                    $this->di->get('Domain\Streets\Metadata'),
                                    self::ITEMS_PER_PAGE,
                                    $page);
    }

    /**
     * View information about a single street
     */
    public function view(array $params)
    {
        if (!empty($_REQUEST['id'])) {
            $info = parent::streetInfo((int)$_REQUEST['id']);

            if ($info->street) {
                return new Views\InfoView($info, $this->addressSearch($info->street->id));
            }
            else {
                $_SESSION['errorMessages'] = $info->errors;
            }
        }
        return new \Application\Views\NotFoundView();
    }

    public function verify  (array $p) { return $this->doBasicChangeLogUseCase('Verify'  ); }
    public function retire  (array $p) { return $this->doBasicChangeLogUseCase('Retire'  ); }
    public function unretire(array $p) { return $this->doBasicChangeLogUseCase('Unretire'); }

    public function add(array $params)
    {
        return new Views\AddView();
    }

    /**
     * Correct an error in the primary attributes of a street
     */
    public function correct(array $params)
    {
        $street_id = (int)$_REQUEST['id'];
        if ($street_id) {
            if (isset($_POST['id'])) {
                $request  = new CorrectRequest($street_id, $_SESSION['USER']->id, $_POST);
                $correct  = $this->di->get('Domain\Streets\UseCases\Correct\Correct');
                $response = $correct($request);
                if (!count($response->errors)) {
                    header('Location: '.View::generateUrl('streets.view', ['id'=>$request->street_id]));
                    exit();
                }
                else { $_SESSION['errorMessages'] = $response->errors; }
            }
            $info    = parent::streetInfo($street_id);
            $contact = !empty($_GET['contact_id']) ? parent::person((int)$_GET['contact_id']) : null;
            if (!isset($request)) {
                $request = new CorrectRequest($street_id, $_SESSION['USER']->id, [
                    'town_id'      => $info->street->town_id,
                    'notes'        => $info->street->notes,
                    'contact_id'   => $contact ? $contact->id : null
                ]);
            }

            return new Views\CorrectView(
                $request,
                $info,
                $this->di->get('Domain\Streets\Metadata'),
                $this->addressSearch($street_id),
                $contact
            );
        }
        return new \Application\Views\NotFoundView();
    }

    public function alias(array $params)
    {
        $street_id = !empty($_REQUEST['id']) ? (int)$_REQUEST['id'] : null;
        if ($street_id) {
            if (isset($_POST['id'])) {
                $request  = new AliasRequest($street_id, $_SESSION['USER']->id, $_POST);
                $alias    = $this->di->get('Domain\Streets\UseCases\Alias\Alias');
                $response = $alias($request);
                if (!count($response->errors)) {
                    header('Location: '.View::generateUrl('streets.view', ['id'=>$street_id]));
                    exit();
                }
                else { $_SESSION['errorMessages'] = $response->errors; }
            }

            $info    = parent::streetInfo($street_id);
            $name    = !empty($_GET['name_id'   ]) ? $this->name  ((int)$_GET['name_id'   ]) : null;
            $contact = !empty($_GET['contact_id']) ? parent::person((int)$_GET['contact_id']) : null;
            if (!isset($request)) {
                $request = new AliasRequest($street_id, $_SESSION['USER']->id, [
                    'name_id'    => $name    ?    $name->id : null,
                    'contact_id' => $contact ? $contact->id : null
                ]);
            }

            return new Views\AliasView(
                $request,
                $info,
                $this->di->get('Domain\Streets\Metadata'),
                $name,
                $contact
            );
        }
        return new \Application\Views\NotFoundView();
    }

    /**
     * Standard use case handler involving a ChangeLogEntry
     *
     * The use case name should be the capitalized version, matching the
     * directory name in /src/Domain.
     *
     * @param string $name  The short (capitalized) use case name
     */
    private function doBasicChangeLogUseCase(string $name)
    {
        $useCase        = "Domain\\Streets\\UseCases\\$name\\$name";
        $useCaseRequest = "Domain\\Streets\\UseCases\\$name\\{$name}Request";
        $useCaseView    = "Application\\Streets\\Views\\{$name}View";

        $street_id = (int)$_REQUEST['id'];
        if ($street_id) {
            if (isset($_POST['id'])) {
                $request  = new $useCaseRequest($street_id, $_SESSION['USER']->id, $_POST);
                $handle   = $this->di->get($useCase);
                $response = $handle($request);

                if (!count($response->errors)) {
                    header('Location: '.View::generateUrl('streets.view', ['id'=>$street_id]));
                    exit();
                }
                else { $_SESSION['errorMessages'] = $response->errors; }
            }
            $contact = !empty($_GET['contact_id']) ? parent::person((int)$_GET['contact_id']) : null;
            if (!isset($request)) {
                $request = new $useCaseRequest($street_id, $_SESSION['USER']->id, [
                    'contact_id' => $contact ? $contact->id : null
                ]);
            }

            return new $useCaseView(
                $request,
                parent::streetInfo(   $street_id),
                $this->addressSearch($street_id),
                $contact
            );
        }

        return new \Application\Views\NotFoundView();
    }

    private function addressSearch(int $street_id): \Domain\Addresses\UseCases\Search\SearchResponse
    {
        $search = $this->di->get('Domain\Addresses\UseCases\Search\Search');
        return $search(new \Domain\Addresses\UseCases\Search\SearchRequest(['street_id' => $street_id]));
    }
}
