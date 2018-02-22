<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Streets\UseCases\Load;

class LoadRequest
{
    public $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }
}
