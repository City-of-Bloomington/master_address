<?php
/**
 * @copyright 2009-2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Application\Models;

class LocationType extends NameCodeTable
{
    protected $tablename = 'location_types';

    // Maps this model's fieldnames to database column names
    // [field => column]
    public static $fieldmap = [
        'id'          => 'id',
        'name'        => 'name',
        'code'        => 'code',
        'description' => 'description'
    ];

    // The character limit for Codes
    public $codeSize = 8;

    public function getDescription() { return parent::get('description'); }
    public function setDescription($s) { parent::set('description', $s); }

    public function handleUpdate(array $post)
    {
        parent::handleUpdate($post);
        $this->setDescription($post['description']);
    }
}
