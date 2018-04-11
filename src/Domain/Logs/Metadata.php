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

    const ACTION_ACTIVATE  = 'activate';
    const ACTION_ADD       = 'add';
    const ACTION_ALIAS     = 'alias';
    const ACTION_ASSIGN    = 'assign';
    const ACTION_CHANGE    = 'change';
    const ACTION_CORRECT   = 'correct';
    const ACTION_CREATE    = 'create';
    const ACTION_MOVE      = 'move';
    const ACTION_PROPOSE   = 'propose';
    const ACTION_READDRESS = 'readdress';
    const ACTION_REASSIGN  = 'reassign';
    const ACTION_RETIRE    = 'retire';
    const ACTION_UPDATE    = 'update';
    const ACTION_UNRETIRE  = 'unretire';
    const ACTION_VERIFY    = 'verify';

    // Map the action name to the log message
	public static $actions = [
        self::ACTION_ACTIVATE  => 'activated',
        self::ACTION_ADD       => 'added',
        self::ACTION_ALIAS     => 'added alias',
        self::ACTION_ASSIGN    => 'assigned',
        self::ACTION_CHANGE    => 'changed street name',
        self::ACTION_CORRECT   => 'corrected',
        self::ACTION_CREATE    => 'created',
        self::ACTION_MOVE      => 'moved to location',
        self::ACTION_PROPOSE   => 'proposed',
        self::ACTION_READDRESS => 'readdressed',
        self::ACTION_REASSIGN  => 'reassigned',
        self::ACTION_RETIRE    => 'retired',
        self::ACTION_UPDATE    => 'updated',
        self::ACTION_UNRETIRE  => 'unretired',
        self::ACTION_VERIFY    => 'verified'
    ];

    public static $statuses = [
        self::STATUS_CURRENT,
        self::STATUS_RETIRED,
        self::STATUS_PROPOSED,
        self::STATUS_DUPLICATE,
        self::STATUS_TEMPORARY
    ];
}
