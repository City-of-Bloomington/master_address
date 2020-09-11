<?php
/**
 * @copyright 2020 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Application\Locations\Views;

use Application\Block;
use Application\Template;

use Domain\Locations\UseCases\Find\FindResponse;

class ListView extends Template
{
    public function __construct(FindResponse $res)
    {
        $format = !empty($_REQUEST['format']) ? $_REQUEST['format'] : 'html';
        parent::__construct('default', $format);

        $this->blocks = [
            new Block('locations/locations.inc', ['locations' => $res->locations])
        ];
    }
}
