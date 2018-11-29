<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Locations\DataStorage;

use Aura\SqlQuery\Common\SelectInterface;
use Domain\PdoRepository;

use Domain\Locations\Entities\Location;
use Domain\Locations\UseCases\Search\SearchRequest;

class PdoLocationsRepository extends PdoRepository implements LocationsRepository
{
    //---------------------------------------------------------------
    // Metadata functions
    //---------------------------------------------------------------
    public function types(): array
    {
        return parent::doQuery('select * from location_types');
    }

    public function trashDays(): array
    {
        return parent::distinctFromTable('trash_day', self::TABLE);
    }

    public function recycleWeeks(): array
    {
        return parent::distinctFromTable('recycle_week', self::TABLE);
    }
}
