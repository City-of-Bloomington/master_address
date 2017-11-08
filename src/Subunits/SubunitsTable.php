<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Application\Subunits;

use Blossom\Classes\TableGateway;

class SubunitsTable extends TableGateway
{
    public function __construct()
    {
        $this->columns = array_keys(Subunit::$fields);

        parent::__construct('subunits', 'Application\Subunits\Subunit');
    }
}
