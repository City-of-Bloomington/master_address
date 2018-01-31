<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Addresses\DataStorage;

use Domain\Addresses\Entities\Address;
use Domain\Addresses\UseCases\Info\InfoRequest;
use Domain\Addresses\UseCases\Search\SearchRequest;
use Domain\Addresses\UseCases\Update\UpdateRequest;

interface AddressesRepository
{
    public function load    (InfoRequest   $req): Address;
    public function search  (SearchRequest $req): array;
    public function save    (Address   $address): int;
    public function distinct(string      $field): array;

    public function cities(): array;
    public function townships(): array;
    public function streetTypes(): array;
    public function subunitTypes(): array;
}
