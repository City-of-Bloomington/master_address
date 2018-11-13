<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl-3.0.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Addresses\UseCases\ChangeLog;

class ChangeLogRequest
{
    // Pagination fields
    public $order;
    public $itemsPerPage;
    public $currentPage;
    public $hydrateEntities = false;

    public function __construct(?array $order           = null,
                                ?int   $itemsPerPage    = null,
                                ?int   $currentPage     = null,
                                ?bool  $hydrateEntities = false)
    {
        $this->order           = $order;
        $this->itemsPerPage    = $itemsPerPage;
        $this->currentPage     = $currentPage;
        $this->hydrateEntities = $hydrateEntities;
    }
}
