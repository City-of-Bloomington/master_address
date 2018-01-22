<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Towns\UseCases\Search;

use Domain\Users\Entities\User;

class SearchRequest
{
    public $id;
    public $name;

    public $requester;

    public function __construct(User $requester=null, array $data=null)
    {
        $this->requester = $requester;
        if ($data) {
            if (!empty($data['id'  ])) { $this->id   = $data['id'  ]; }
            if (!empty($data['name'])) { $this->name = $data['name']; }
        }
    }
}
