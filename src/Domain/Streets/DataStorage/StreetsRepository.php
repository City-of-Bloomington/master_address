<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Streets\DataStorage;

use Domain\Logs\Entities\ChangeLogEntry;

use Domain\Streets\Entities\Designation;
use Domain\Streets\Entities\Street;
use Domain\Streets\UseCases\Add\AddRequest;
use Domain\Streets\UseCases\Alias\AliasRequest;
use Domain\Streets\UseCases\Info\InfoRequest;
use Domain\Streets\UseCases\Search\SearchRequest;
use Domain\Streets\UseCases\Update\UpdateRequest;

interface StreetsRepository
{
    // Read functions
    public function findDesignations(array       $fields): array;
    public function load            (int      $street_id): Street;
    public function loadDesignation (int $designation_id): Designation;
    public function search   (SearchRequest $req): array;
    public function changeLog(?int $street_id=null, ?array $order=null, ?int $itemsPerPage=null, ?int $currentPage=null): array;

    // Write functions
    public function add      (AddRequest     $request): int;
    public function update   (UpdateRequest  $request);
    public function addDesignation(AliasRequest  $req): int;
    public function logChange(ChangeLogEntry   $entry, string $logType): int;
    public function saveStatus(int $street_id, string $status);
    public function updateDesignation(\Domain\Streets\Designations\UseCases\Update\UpdateRequest $req);

    // Metadata functions
    public function types(): array;
    public function towns(): array;
    public function designationTypes(): array;
}
