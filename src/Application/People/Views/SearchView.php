<?php
/**
 * @copyright 2017-2018 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
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
        $template = !empty($_REQUEST['callback']) ? 'callback'          : 'default';
        $format   = !empty($_REQUEST['format'  ]) ? $_REQUEST['format'] : 'html';
        parent::__construct($template, $format);

        $this->vars['title'] = $this->_('people_search');
        if ($response->errors) {
            $_SESSION['errorMessages'] = $response->errors;
        }

        $this->blocks[] = new Block('people/findForm.inc', [
            'people'        => $response->people,
            'callback_url'  => !empty($_GET['callback_url'  ]) ?        new Url($_GET['callback_url'  ]) : null,
            'callback_field'=> !empty($_GET['callback_field']) ? parent::escape($_GET['callback_field']) : 'person_id',
            'callback_js'   => !empty($_GET['callback'      ]) ? parent::escape($_GET['callback'      ]) : null,

            'firstname'     => !empty($_GET['firstname'     ]) ? parent::escape($_GET['firstname'     ]) : '',
            'lastname'      => !empty($_GET['lastname'      ]) ? parent::escape($_GET['lastname'      ]) : '',
            'email'         => !empty($_GET['email'         ]) ? parent::escape($_GET['email'         ]) : '',
            'hidden'        => parent::filterActiveParams($_GET, ['firstname', 'lastname', 'email']),
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
