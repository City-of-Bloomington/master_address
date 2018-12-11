<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Jurisdictions\DataStorage;

use Domain\Jurisdictions\Entities\Jurisdiction;
use Domain\Jurisdictions\UseCases\Info\InfoRequest;
use Domain\Jurisdictions\UseCases\Search\SearchRequest;

interface JurisdictionsRepository
{
    public function   load(InfoRequest   $req): Jurisdiction;
    public function search(SearchRequest $req): array;
    public function save(Jurisdiction $jurisdiction): int;
}
