<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Application\Towns\Views;

use Application\Block;
use Application\Template;

use Domain\Towns\UseCases\Search\SearchResponse;

class ListView extends Template
{
    public function __construct(SearchResponse $response)
    {
        $format = !empty($_REQUEST['format']) ? $_REQUEST['format'] : 'html';
        $vars = [
            'title' => $this->_(['town', 'towns', count($response->towns)])
        ];
        parent::__construct('default', $format, $vars);

        if (count($response->errors)) {
            $_SESSION['errorMessages'][] = $response->errors;
        }

		$vars['towns'] = $response->towns;
		$this->blocks[] = new Block("towns/list.inc", $vars);
    }
}
