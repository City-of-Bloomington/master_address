<?php
/**
 * @copyright 2009-2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Application\Models;

class SubunitType extends NameCodeTable
{
    protected $tablename = 'subunit_types';

    // The character limit for Codes
    public $codeSize = 8;
}
