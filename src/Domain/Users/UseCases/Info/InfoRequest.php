<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Users\UseCases\Info;

class InfoRequest
{
    public $id;
    public $username;

    public $requester;

    public function __construct(User $requester, int $id, string $username)
}
