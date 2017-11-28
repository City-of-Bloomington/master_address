<?php
/**
 * @copyright 2009-2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Application\Streets\Types;

use Application\Models\NameCodeTable;

class Type extends NameCodeTable
{
    protected $tablename = 'street_types';

    // Max length for the code field
    public $codeSize = 8;
}
