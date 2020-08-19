<?php
/**
 * @copyright 2019-2020 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\ZipCodes\UseCases\Index;

class Response
{
    public $zipCodes;
    public $errors;

    public function __construct(?array $zipCodes=null, ?array $errors=null)
    {
        $this->zipCodes = $zipCodes;
        $this->errors   = $errors;
    }
}
