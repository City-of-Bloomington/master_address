<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Towns\DataStorage;

use Domain\Towns\Entities\Town;
use Domain\Towns\UseCases\Info\InfoRequest;
use Domain\Towns\UseCases\Search\SearchRequest;

interface TownsRepository
{
    public function   load(InfoRequest   $req): Town;
    public function search(SearchRequest $req): array;
}
