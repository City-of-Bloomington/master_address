<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\People\DataStorage;

use Domain\People\Entities\Person;
use Domain\People\UseCases\Info\InfoRequest;
use Domain\People\UseCases\Search\SearchRequest;
use Domain\People\UseCases\Update\UpdateRequest;

interface PeopleRepository
{
    public function   load(InfoRequest   $req): Person;
    public function search(SearchRequest $req): array;
    public function save(Person $person): int;
}
