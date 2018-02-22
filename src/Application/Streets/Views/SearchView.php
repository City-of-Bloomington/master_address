<?php
/**
 * @copyright 2017-2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Application\Streets\Views;

use Application\Block;
use Application\Template;
use Application\Paginator;
use Application\Url;

use Domain\Streets\UseCases\Search\SearchResponse;

class SearchView extends Template
{
    public function __construct(SearchResponse $response, int $itemsPerPage, int $currentPage)
    {
        $format = !empty($_REQUEST['format']) ? $_REQUEST['format'] : 'html';
        parent::__construct('default', $format);

        $this->vars['title'] = $this->_('streets_search');
        if (count($response->errors)) {
            $_SESSION['errorMessages'] = $response->errors;
        }
        
        $this->blocks[] = new Block('streets/searchForm.inc', [
            'street'     => !empty($_GET['street']) ? parent::escape($_GET['street']) : '',
            'streets'    => $response->streets,
            'hidden'     => parent::filterActiveParams($_GET, ['street']),
            'return_url' => !empty($_GET['return_url']) ? new Url($_GET['return_url']) : null
        ]);

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
