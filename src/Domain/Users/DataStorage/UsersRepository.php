<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Users\DataStorage;

use Domain\Users\Entities\User;
use Domain\Users\UseCases\Search\SearchRequest;

interface UsersRepository
{
    public function loadById(int $id): ?User;
    public function loadByUsername(string $username): ?User;
    public function search(SearchRequest $req) : array;
    public function delete(int $id);
}
