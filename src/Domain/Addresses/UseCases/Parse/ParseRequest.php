<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Addresses\UseCases\Parse;

class ParseRequest
{
    public $address;
    
    public function __construct(string $address)
    {
        $this->address = $address;
    }
}
