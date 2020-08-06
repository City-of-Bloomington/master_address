<?php
/**
 * @copyright 2020 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Subunits\UseCases\Import;

use Domain\Subunits\UseCases\Add\AddRequest;

class Request
{
    public $addRequests = [];

    /**
     * @param array $addRequests  An array of Subunit\AddRequest objects
     */
    public function __construct(array $addRequests)
    {
        foreach ($addRequests as $a) {
            if (!($a instanceof AddRequest)) {
                throw new \Exception('subunits/invalidAddRequest');
            }
        }
        $this->addRequests = $addRequests;

    }
}
