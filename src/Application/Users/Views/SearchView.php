<?php
/**
 * @copyright 2018-2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Application\Users\Views;

use Application\Block;
use Application\Template;
use Application\Paginator;

use Domain\Users\UseCases\Search\SearchResponse;

class SearchView extends Template
{
    public function __construct(SearchResponse $response,
                                int            $itemsPerPage,
                                int            $currentPage,
                                array          $roles,
                                array          $authentication_methods)
    {
        $format = !empty($_REQUEST['format']) ? $_REQUEST['format'] : 'html';
        parent::__construct('default', $format);

        $this->vars['title'] = $this->_('users_search');
        if ($response->errors) {
            $_SESSION['errorMessages'] = $response->errors;
        }

        $this->blocks = [
            new Block('users/findForm.inc', [
                'users'                  => $response->users,
                'roles'                  => $roles,
                'authentication_methods' => $authentication_methods
            ])
        ] ;

        if ($response->total > $itemsPerPage) {
            $this->blocks[] = new Block('pageNavigation.inc', [
                'paginator' => new Paginator(
                    $response->total,
                    $itemsPerPage,
                    $currentPage
            )]);
        }
    }
}
