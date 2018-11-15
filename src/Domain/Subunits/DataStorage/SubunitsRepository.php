<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Subunits\DataStorage;

use Domain\Locations\Entities\Location;
use Domain\Logs\Entities\ChangeLogEntry;
use Domain\Subunits\Entities\Subunit;
use Domain\Subunits\UseCases\Add\AddRequest;
use Domain\Subunits\UseCases\Correct\CorrectRequest;
use Domain\Subunits\UseCases\Search\SearchRequest;

interface SubunitsRepository
{
    // Read functions
    public function load         (int $subunit_id): Subunit;
    public function locations    (int $subunit_id): array;
    public function loadStatusLog(int $subunit_id): array;
    public function getStatus    (int $subunit_id): string;

    public function find    (array $fields,          ?array $order=null, ?int $itemsPerPage=null, ?int $currentPage=null): array;
    public function changeLog(?int $subunit_id=null, ?array $order=null, ?int $itemsPerPage=null, ?int $currentPage=null): array;

    // Write functions
    public function add         (AddRequest     $request): int;
    public function correct     (CorrectRequest $request);
    public function logChange   (ChangeLogEntry   $entry): int;
    public function saveLocation(Location      $location): int;
    public function saveStatus        (int $subunit_id,  string $status);
    public function saveLocationStatus(int $location_id, string $status);

    // Metadata functions
    public function types(): array;
    public function locationTypes(): array;
}
