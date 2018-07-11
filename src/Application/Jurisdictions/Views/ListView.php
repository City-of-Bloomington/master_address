<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Application\Jurisdictions\Views;

use Application\Block;
use Application\Template;

use Domain\Jurisdictions\UseCases\Search\SearchResponse;

class ListView extends Template
{
    public function __construct(SearchResponse $response)
    {
        $format = !empty($_REQUEST['format']) ? $_REQUEST['format'] : 'html';
        parent::__construct('default', $format);

        $vars = [
            'title' => $this->_('jurisdiction')
        ];
        $this->vars = $vars;

        if ($response->errors) {
            $_SESSION['errorMessages'][] = $response->errors;
        }

		$vars['jurisdictions'] = $response->jurisdictions;
		$this->blocks[] = new Block("jurisdictions/list.inc", $vars);
    }
}
