<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Streets\Names\DataStorage;

use Domain\Streets\Names\Entities\Name;
use Domain\Streets\Names\UseCases\Search\SearchRequest;
use Domain\Streets\Names\UseCases\Update\UpdateRequest;

interface NamesRepository
{
    public function   load(int            $id): Name;
    public function search(SearchRequest $req): array;
    public function save  (Name         $name): int;
}
