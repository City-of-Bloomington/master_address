<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Application\Streets\Designations\Messages;

use Application\Streets\Designations\Designation;
use Application\Streets\Names\Name;
use Application\People\Person;

class UpdateRequest
{
    public $id;

    public $street_id;
    public $name_id;
    public $type_id;
    public $startDate;
    public $endDate;
    public $rank;

    public $user_id;
    public $contact_id;
    public $notes;

    public function __construct(array $post)
    {
        foreach ($this as $k=>$v) {
            if (!empty($post[$k])) {
                switch ($k) {
                    case 'startDate':
                    case 'endDate':
                        $this->$k = is_a($post[$k], 'DateTime')
                                  ? $post[$k]
                                  : \DateTime::createFromFormat(DATE_FORMAT, $post[$k]);
                    break;

                    default: $this->$k = $post[$k];
                }
            }
        }
    }
}
