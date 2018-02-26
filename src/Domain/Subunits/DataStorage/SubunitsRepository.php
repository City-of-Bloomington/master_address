<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Subunits\DataStorage;

use Domain\Subunits\Entities\Subunit;
use Domain\Subunits\UseCases\Search\SearchRequest;
use Domain\Logs\Entities\ChangeLogEntry;

interface SubunitsRepository
{
    // Read functions
    public function load         (int $subunit_id): Subunit;
    public function locations    (int $subunit_id): array;
    public function loadChangeLog(int $subunit_id): array;
    public function loadStatusLog(int $subunit_id): array;

    // Write functions
    public function logChange(ChangeLogEntry   $entry): int;
    public function saveStatus(int $subunit_id, string $status);
}
