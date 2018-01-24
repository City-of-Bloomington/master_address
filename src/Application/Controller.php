<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Application;

class Controller
{
    protected $di;

    public function __construct()
    {
        global $DI;
        $this->di = $DI;
    }
}
