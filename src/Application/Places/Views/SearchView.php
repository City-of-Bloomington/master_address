<?php
/**
 * @copyright 2021 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Application\Places\Views;

use Application\Block;
use Application\Template;

use Domain\Places\Actions\Search\Request;
use Domain\Places\Actions\Search\Response;

class SearchView extends Template
{
    public function __construct(Request  $request,
                                Response $response)
    {
        $format = !empty($_REQUEST['format']) ? $_REQUEST['format'] : 'html';
        parent::__construct('default', $format);

        $this->vars['title'] = $this->_('place_search');
        if ($response->errors) {
            $_SESSION['errorMessages'] = $response->errors;
        }

        $vars = [
            'places'       => $response->places,
            'total'        => $response->total,
            'itemsPerPage' => $request ->itemsPerPage,
            'currentPage'  => $request ->currentPage,
        ];

        if ($format != 'html') {
            $this->blocks = [
                new Block('places/list.inc', $vars)
            ];
        }
        else {
            foreach ($request as $k=>$v) {
                $vars[$k] = is_string($v) ? parent::escape($v) : $v;
            }

            $this->blocks[] = new Block('places/findForm.inc', $vars);
        }
    }
}
