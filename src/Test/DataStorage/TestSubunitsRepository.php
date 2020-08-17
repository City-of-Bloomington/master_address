<?php
/**
 * @copyright 2020 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Test\DataStorage;

use Domain\Logs\Entities\ChangeLogEntry;
use Domain\Subunits\DataStorage\SubunitsRepository;
use Domain\Subunits\Entities\Subunit;
use Domain\Subunits\UseCases\Add\AddRequest;
use Domain\Subunits\UseCases\Correct\CorrectRequest;
use Domain\Subunits\UseCases\Update\Request as UpdateRequest;

class TestSubunitsRepository implements SubunitsRepository
{
    public const LOG_TYPE = 'test';

    // Read functions
    public function load         (int $subunit_id): Subunit                 { return new Subunit(['id' => $subunit_id]); }
    public function loadStatusLog(int $subunit_id, string $logType): array  { return []; }
    public function getStatus    (int $subunit_id, string $logType): string { return 'test'; }

    public function find         (array $fields,     ?array $order=null, ?int $itemsPerPage=null, ?int $currentPage=null): array { return []; }
    public function findAddresses(array $fields,     ?array $order=null, ?int $itemsPerPage=null, ?int $currentPage=null): array { return []; }
    public function changeLog(?int $subunit_id=null, ?array $order=null, ?int $itemsPerPage=null, ?int $currentPage=null): array { return []; }
    public function findLocations(int $subunit_id): array                                                                        { return []; }

    public function validate(AddRequest $request): array { return []; }

    // Write functions
    public function add       (AddRequest     $request): int                  { return 1; }
    public function correct   (CorrectRequest $request)                           { }
    public function update    (UpdateRequest  $request)                           { }
    public function activate  (int $subunit_id, int $location_id)                 { }
    public function saveStatus(int $subunit_id,  string $status, string $logType) { }
    public function logChange (ChangeLogEntry   $entry, string $logType): int { return 1; }

    // Metadata functions
    public function types(): array         { return []; }
    public function locationTypes(): array { return []; }
}
