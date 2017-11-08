<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Application\Subdivisions;

use Blossom\Classes\ActiveRecord;
use Blossom\Classes\Database;

class Subdivision extends ActiveRecord
{
    protected $tablename = 'subdivisions';
    protected $township;

    public static $statuses = ['CURRENT', 'RENAMED'];

    public function validate()
    {
        if (!$this->getName() || !$this->getStatus()) {
            throw new \Exception('missingRequiredFields');
        }

        if (!in_array($this->getStatus(), self::$statuses)) {
            throw new \Exception('invalidStatus');
        }
    }

    public function save() { parent::save(); }

	//----------------------------------------------------------------
	// Generic Getters and Setters
	//----------------------------------------------------------------
	public function getId()     { return (int)parent::get('id'    ); }
	public function getName()   { return      parent::get('name'  ); }
	public function getStatus() { return      parent::get('status'); }
	public function getPhase()       { $p = (int)parent::get('phase'      ); return $p ? $p : null; }
	public function getTownship_id() { $i = (int)parent::get('township_id'); return $i ? $i : null; }
	public function getTownship()    { return parent::getForeignKeyObject('Application\Townships\Township', 'township_id'); }

	public function setName     (string $s) { parent::set('name',   $s); }
	public function setStatus   (string $s) { parent::set('status', $s); }
	public function setPhase      (int $i=null) { parent::set('phase', $i ? $i : null); }
	public function setTownship_id(int $i=null) { parent::setForeignKeyField ('Application\Townships\Township', 'township_id', $i ? $i : null); }
	public function setTownship   (Township $o) { parent::setForeignKeyObject('Application\Townships\Township', 'township_id', $o ); }

	public function handleUpdate(array $post)
	{
        $this->setName  ($post['name'  ]);
        $this->setStatus($post['status']);

        $this->setPhase      ((int)$post['phase'      ]);
        $this->setTownship_id((int)$post['township_id']);
	}

	//----------------------------------------------------------------
	// Custom Functions
	//----------------------------------------------------------------
	public static function getPhases() : array
	{
        $pdo   = Database::getConnection();
        $query = $pdo->prepare('select distinct phase from subdivisions where phase is not null order by phase');
        $query->execute();
        return $query->fetchAll(\PDO::FETCH_COLUMN, 0);
	}
}
