<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Plats\DataStorage;

use Domain\Plats\Entities\Plat;
use Domain\Plats\UseCases\Info\InfoRequest;
use Domain\Plats\UseCases\Search\SearchRequest;

interface PlatsRepository
{
    public function load    (InfoRequest   $req): Plat;
    public function search  (SearchRequest $req): array;
    public function save    (Plat         $plat): int;

    // Metadata functions
    public function townships(): array;
    public function distinct(string $field): array;
}
