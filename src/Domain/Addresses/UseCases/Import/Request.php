<?php
/**
 * @copyright 2020 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Domain\Addresses\UseCases\Import;

use Domain\Addresses\UseCases\Add\AddRequest;

class Request
{
    public $addRequests = [];

    /**
     * @param array $addRequests  An array of Address\AddRequest objects
     */
    public function __construct(array $addRequests)
    {
        foreach ($addRequests as $a) {
            if (!($a instanceof AddRequest)) {
                throw new \Exception('addresses/invalidAddRequest');
            }
        }
        $this->addRequests = $addRequests;
    }
}
