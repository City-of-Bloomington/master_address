<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Addresses\DataStorage;

use Domain\Addresses\Entities\Address;
use Domain\Addresses\UseCases\Correct\CorrectRequest;
use Domain\Addresses\UseCases\Search\SearchRequest;
use Domain\Logs\Entities\ChangeLogEntry;

interface AddressesRepository
{
    // Read functions
    public function load         (int $address_id): Address;
    public function locations    (int $address_id): array;
    public function subunits     (int $address_id): array;
    public function loadChangeLog(int $address_id): array;
    public function loadStatusLog(int $address_id): array;
    public function getStatus    (int $address_id): string;
    public function search   (SearchRequest  $req): array;

    // Write functions
    public function correct(CorrectRequest $request);
    public function logChange(ChangeLogEntry $entry): int;
    public function saveStatus(int $address_id, string $status);

    // Metadata functions
    public function cities      (): array;
    public function townships   (): array;
    public function streetTypes (): array;
    public function subunitTypes(): array;
}
