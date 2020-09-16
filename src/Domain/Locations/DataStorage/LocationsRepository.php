<?php
/**
 * @copyright 2018-2020 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Locations\DataStorage;

use Domain\Locations\UseCases\Search\SearchRequest;

interface LocationsRepository
{
    // Read functions
    public function find  (array         $fields): array;
    public function search(SearchRequest $search): array;

    // Metadata functions
    public function types       (): array;
    public function trashDays   (): array;
    public function recycleWeeks(): array;
}
