<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Streets\DataStorage;

use Domain\Logs\Entities\ChangeLogEntry;

use Domain\Streets\Entities\Street;
use Domain\Streets\UseCases\Info\InfoRequest;
use Domain\Streets\UseCases\Search\SearchRequest;
use Domain\Streets\UseCases\Correct\CorrectRequest;

interface StreetsRepository
{
    // Read functions
    public function load         (int $street_id): Street;
    public function loadChangeLog(int $street_id): array;
    public function designations (int $street_id): array;
    public function search   (SearchRequest $req): array;

    // Write functions
    public function correct  (CorrectRequest $request);
    public function logChange(ChangeLogEntry   $entry): int;


    // Metadata functions
    public function types(): array;
    public function towns(): array;


}
