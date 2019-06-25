<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Application\Addresses\Views;

use Application\Block;
use Application\Template;

use Domain\Addresses\UseCases\Validate\Response;

class ValidateView extends Template
{
    public function __construct(string    $query=null,
                                ?Response $response=null)
    {
        $format = !empty($_REQUEST['format']) ? $_REQUEST['format'] : 'html';
        parent::__construct('default', $format);
        $this->vars['title'] = $this->_('validate');

        $vars = [
            'query'   => parent::escape($query),
            'address' => $response ? $response->address : null
        ];

        if ($this->outputFormat == 'html') {
            $this->blocks = [
                new Block('addresses/actions/validateForm.inc', $vars)
            ];
        }
        else {
            $this->blocks = [
                new Block('addresses/info.inc', ['info'=>$response->address])
            ];
        }
    }
}
