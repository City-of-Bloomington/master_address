<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Application\Townships\Views;

use Application\Block;
use Application\Template;

use Domain\Townships\UseCases\Search\SearchResponse;

class ListView extends Template
{
    public function __construct(SearchResponse $response)
    {
        $format = !empty($_REQUEST['format']) ? $_REQUEST['format'] : 'html';
        parent::__construct('default', $format);

        $vars = [
            'title' => $this->_(['township', 'townships', count($response->townships)])
        ];
        $this->vars = $vars;

        if ($response->errors) {
            $_SESSION['errorMessages'][] = $response->errors;
        }

		$vars['townships'] = $response->townships;
		$this->blocks[] = new Block("townships/list.inc", $vars);
    }
}
