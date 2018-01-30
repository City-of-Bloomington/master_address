<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Subdivisions\DataStorage;

use Domain\Subdivisions\Entities\Subdivision;
use Domain\Subdivisions\UseCases\Info\InfoRequest;
use Domain\Subdivisions\UseCases\Search\SearchRequest;
use Domain\Subdivisions\UseCases\Update\UpdateRequest;

interface SubdivisionsRepository
{
    public function load    (InfoRequest   $req): Subdivision;
    public function search  (SearchRequest $req): array;
    public function save    (Subdivision  $subd): int;
    public function distinct(string      $field): array;
    public function townships(): array;
}
