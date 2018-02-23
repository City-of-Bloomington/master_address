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
    public function load(int $location_id): Location;
    public function search(SearchRequest $req): array;
}
