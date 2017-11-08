<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Application\Streets;

use Blossom\Classes\TableGateway;

class NameTypesTable extends TableGateway
{
    public function __construct() { parent::__construct('street_name_types', 'Application\Streets\NameType'); }

    public function find(array $fields=null, array $order=['name'], int $itemsPerPage=null, int $currentPage=null)
    {
        return parent::find($fields, $order, $itemsPerPage, $currentPage);
    }
}
