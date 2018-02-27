<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Logs;

class Metadata
{
    const STATUS_CURRENT   = 'current';
    const STATUS_RETIRED   = 'retired';
    const STATUS_PROPOSED  = 'proposed';
    const STATUS_DUPLICATE = 'duplicate';
    const STATUS_TEMPORARY = 'temporary';

    // Map the action name to the log message
	public static $actions = [
        'activate'  => 'activated',
        'add'       => 'added',
        'alias'     => 'added alias',
        'assign'    => 'assigned',
        'change'    => 'changed street name',
        'correct'   => 'corrected',
        'create'    => 'created',
        'move'      => 'moved to location',
        'propose'   => 'proposed',
        'readdress' => 'readdressed',
        'reassign'  => 'reassigned',
        'retire'    => 'retired',
        'update'    => 'updated',
        'unretire'  => 'unretired',
        'verify'    => 'verified'
    ];

    public static $statuses = [
        self::STATUS_CURRENT,
        self::STATUS_RETIRED,
        self::STATUS_PROPOSED,
        self::STATUS_DUPLICATE,
        self::STATUS_TEMPORARY
    ];
}
