<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Townships\DataStorage;

use Domain\Townships\Entities\Township;
use Domain\Townships\UseCases\Info\InfoRequest;
use Domain\Townships\UseCases\Search\SearchRequest;

interface TownshipsRepository
{
    public function   load(InfoRequest   $req): Township;
    public function search(SearchRequest $req): array;
    public function   save(Township $township): int;
}
