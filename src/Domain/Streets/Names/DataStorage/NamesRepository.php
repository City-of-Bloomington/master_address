<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Streets\Names\DataStorage;

use Domain\Streets\Entities\Name;
use Domain\Streets\Names\UseCases\Search\SearchRequest;
use Domain\Streets\Names\UseCases\Update\UpdateRequest;

interface NamesRepository
{
    // Read functions
    public function    load     (int $name_id): Name;
    public function designations(int $name_id): array;
    public function search(SearchRequest $req): array;

    // Write functions
    public function save  (Name         $name): int;

    // Metadata
}
