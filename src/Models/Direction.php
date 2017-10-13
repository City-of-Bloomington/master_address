<?php
/**
 * @copyright 2009-2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Application\Models;

class Direction extends NameCodeTable
{
    protected $tablename = 'directions';

    // The character limit for Codes
    public $codeSize = 2;
}
