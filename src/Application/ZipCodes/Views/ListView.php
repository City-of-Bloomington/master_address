<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Application\ZipCodes\Views;

use Application\Block;
use Application\Template;

use Domain\ZipCodes\UseCases\Index\Response;

class ListView extends Template
{
    public function __construct(Response $response)
    {
        $format = !empty($_REQUEST['format']) ? $_REQUEST['format'] : 'html';
        parent::__construct('default', $format);

        $this->vars['title'] = $this->_(['zip', 'zips', count($response->zipCodes)]);
        if ($response->errors) {
            $_SESSION['errorMessages'] = $response->errors;
        }

        $this->blocks = [
            new Block('zipCodes/list.inc', ['zipCodes' => $response->zipCodes])
        ];
    }
}
