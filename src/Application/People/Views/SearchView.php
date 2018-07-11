<?php
/**
 * @copyright 2017-2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Application\People\Views;

use Application\Block;
use Application\Template;
use Application\Paginator;
use Application\Url;

use Domain\People\UseCases\Search\SearchResponse;

class SearchView extends Template
{
    public function __construct(SearchResponse $response, int $itemsPerPage, int $currentPage)
    {
        $format = !empty($_REQUEST['format']) ? $_REQUEST['format'] : 'html';
        parent::__construct('default', $format);

        $this->vars['title'] = $this->_('people_search');
        if ($response->errors) {
            $_SESSION['errorMessages'] = $response->errors;
        }

        $vars = ['people' => $response->people];

        $fields = ['firstname', 'lastname', 'email'];
        foreach ($fields as $f) {
            $vars[$f] = !empty($_GET[$f]) ? parent::escape($_GET[$f]) : '';
        }
        $vars['hidden'    ] = parent::filterActiveParams($_GET, $fields);
        $vars['callback_url'  ] = !empty($_GET['callback_url'  ]) ?        new Url($_GET['callback_url'  ]) : null;
        $vars['callback_field'] = !empty($_GET['callback_field']) ? parent::escape($_GET['callback_field']) : 'person_id';

        $this->blocks[] = new Block('people/findForm.inc', $vars);

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
