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
use Domain\Plats\UseCases\Update\UpdateRequest;

interface PlatsRepository
{
    public function load    (InfoRequest   $req): Plat;
    public function search  (SearchRequest $req): array;
    public function save    (Plat         $plat): int;
    public function distinct(string      $field): array;
    public function townships(): array;
}
