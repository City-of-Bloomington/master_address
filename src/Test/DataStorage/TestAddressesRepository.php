<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Test\DataStorage;

use Domain\Addresses\DataStorage\AddressesRepository;

use Domain\Addresses\Entities\Address;
use Domain\Addresses\UseCases\Correct\CorrectRequest;
use Domain\Addresses\UseCases\Renumber\RenumberRequest;
use Domain\Addresses\UseCases\Update\Request as UpdateRequest;
use Domain\Logs\Entities\ChangeLogEntry;

class TestAddressesRepository implements AddressesRepository
{
    public const LOG_TYPE = 'test';

    // Read functions
    public function load         (int $address_id): Address { return new Address(['id'=>$address_id]); }
    public function findLocations(int $address_id): array   { return []; }
    public function findPurposes (int $address_id): array   { return []; }
    public function findSubunits (array $fields, ?array $order=null, ?int $itemsPerPage=null, ?int $currentPage=null): array { return []; }
    public function loadStatusLog(int $address_id, string $logType): array { return []; }
    public function getStatus    (int $address_id, string $logType): string { return "test"; }

    public function find    (array $fields,          ?array $order=null, ?int $itemsPerPage=null, ?int $currentPage=null): array { return []; }
    public function search  (array $fields,          ?array $order=null, ?int $itemsPerPage=null, ?int $currentPage=null): array { return []; }
    public function changeLog(?int $address_id=null, ?array $order=null, ?int $itemsPerPage=null, ?int $currentPage=null): array { return []; }

    // Write functions
    public function activate(int $address_id, int $location_id) { }
    public function correct ( CorrectRequest $request) { }
    public function update  (  UpdateRequest $request) { }
    public function renumber(RenumberRequest $request) { }
    public function logChange(ChangeLogEntry $entry, string $logType): int { return 1; }
    public function saveStatus(int $address_id, string $status, string $logType) { }

    // Metadata functions
    public function cities         (): array { return []; }
    public function jurisdictions  (): array { return []; }
    public function quarterSections(): array { return []; }
    public function sections       (): array { return []; }
    public function streetTypes    (): array { return []; }
    public function subunitTypes   (): array { return []; }
    public function townships      (): array { return []; }
    public function types          (): array { return []; }
    public function zipCodes       (): array { return []; }
}
