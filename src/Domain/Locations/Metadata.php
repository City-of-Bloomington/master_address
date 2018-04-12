<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Locations;

use Domain\Locations\DataStorage\LocationsRepository;

class Metadata
{
    const DEFAULT_TYPE_ID = 3;

    public static $trash_days = [
        'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'
    ];

    public static $recycle_weeks = ['A', 'B'];
}
