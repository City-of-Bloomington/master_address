<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Locations\DataStorage;

interface LocationsRepository
{
    // Metadata functions
    public function types       (): array;
    public function trashDays   (): array;
    public function recycleWeeks(): array;
}
