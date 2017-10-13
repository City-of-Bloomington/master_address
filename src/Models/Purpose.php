<?php
/**
 * @copyright 2009-2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Application\Models;

class Purpose extends NameTable
{
    protected $tablename = 'location_purposes';

    // Maps this model's fieldnames to database column names
    // [field => column]
    public static $fieldmap = [
        'id'          => 'id',
        'name'        => 'name',
        'type'        => 'purpose_type',
    ];

    /**
     * Valid values for type
     *
     * These are sorted most commonly used first.
     */
    public static $types = [
        'HISTORIC DISTRICT',
        'NEIGHBORHOOD ASSOCIATION',
        'ECONOMIC DEVELOPMENT AREA',
        'RESIDENTIAL PARKING ZONE',
        'CITY COUNCIL DISTRICT',
        'REDEVELOPMENT ZONE',
        'OTHER'
    ];

    public function validate() {
        if (!$this->getName() || !$this->getType()) {
            throw new \Exception('missingRequiredFields');
        }
    }

    public function getType() { return parent::get('purpose_type'); }
    public function setType($s) { parent::set('type', $s); }


    public function handleUpdate(array $post)
    {
        parent::handleUpdate($post);
        $this->setType($post['type']);
    }
}
