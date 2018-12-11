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

use Domain\Streets\Metadata;
use Domain\Streets\UseCases\Search\SearchResponse;

class SearchView extends Template
{
    public function __construct(SearchResponse $response,
                                Metadata       $metadata,
                                int            $itemsPerPage,
                                int            $currentPage)
    {
        $template = !empty($_REQUEST['callback']) ? 'callback'          : 'default';
        $format   = !empty($_REQUEST['format'  ]) ? $_REQUEST['format'] : 'html';
        parent::__construct($template, $format);

        $this->vars['title'] = $this->_('streets_search');
        if ($response->errors) {
            $_SESSION['errorMessages'] = $response->errors;
        }

        $this->blocks[] = new Block('streets/searchForm.inc', [
            'town_id'        => !empty($_GET['town_id']) ?           (int)$_GET['town_id']  : null,
            'street'         => !empty($_GET['street' ]) ? parent::escape($_GET['street' ]) : '',
            'status'         => !empty($_GET['status' ]) ? parent::escape($_GET['status' ]) : '',
            'streets'        => $response->streets,
            'towns'          => $metadata->towns(),
            'statuses'       => $metadata->statuses(),
            'hidden'         => parent::filterActiveParams($_GET, ['street']),
            'callback_url'   => !empty($_GET['callback_url'  ]) ?        new Url($_GET['callback_url'  ]) : null,
            'callback_field' => !empty($_GET['callback_field']) ? parent::escape($_GET['callback_field']) : 'street_id',
            'callback_js'    => !empty($_GET['callback'      ]) ? parent::escape($_GET['callback'      ]) : null
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
