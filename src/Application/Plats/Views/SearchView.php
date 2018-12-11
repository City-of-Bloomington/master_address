<?php
/**
 * @copyright 2017-2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Application\Plats\Views;

use Application\Block;
use Application\Template;
use Application\Paginator;

use Domain\Plats\UseCases\Search\SearchResponse;

class SearchView extends Template
{
    public function __construct(SearchResponse $response, int $itemsPerPage, int $currentPage)
    {
        $template = !empty($_REQUEST['callback']) ? 'callback'          : 'default';
        $format   = !empty($_REQUEST['format'  ]) ? $_REQUEST['format'] : 'html';
        parent::__construct($template, $format);

        $this->vars['title'] = $this->_('plats_search');
        if ($response->errors) {
            $_SESSION['errorMessages'] = $response->errors;
        }

        $this->blocks[] = new Block('plats/searchForm.inc', [
            'plats'   => $response->plats,
            'options' => $response->options,
            'callback_url'   => !empty($_GET['callback_url'  ]) ?        new Url($_GET['callback_url'  ]) : null,
            'callback_field' => !empty($_GET['callback_field']) ? parent::escape($_GET['callback_field']) : 'plat_id',
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
