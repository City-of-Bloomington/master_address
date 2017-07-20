<?php
/**
 * @copyright 2009-2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Application\Models;

class StreetType extends NameCodeTable
{
    protected $tablename = 'street_types';

    // The character limit for Codes
    public $codeSize = 8;
}
