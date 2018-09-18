<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Locations\DataStorage;

use Domain\Locations\Entities\Location;
use Domain\Locations\UseCases\Search\SearchRequest;

interface LocationsRepository
{
    // Read functions
    public function find(SearchRequest $req): array;
    public function load         (int $location_id): Location;
    public function loadStatusLog(int $location_id): array;

    // Write functions
    public function saveStatus(int $location_id, string $status);

    // Metadata functions
    public function types       (): array;
    public function trashDays   (): array;
    public function recycleWeeks(): array;
}
