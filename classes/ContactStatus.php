<?php
/**
 * @copyright 2014 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
class ContactStatus
{
    private $id;
    private $status;

    /**
     * Populates the object with data
     *
     * Passing in an associative array of data will populate this object without
     * hitting the database.
     *
     * Passing in a scalar will load the data from the database.
     * This will load all fields in the table as properties of this class.
     * You may want to replace this with, or add your own extra, custom loading
     *
     * @param int|array $status_code
     */
    public function __construct($id=null)
    {
        if ($id) {
            if (is_array($id)) {
                $result = $id;
            }
            else {
                $zend_db = Database::getConnection();
                $sql = is_numeric($id)
                    ? 'select * from eng.contactstatus where id=?'
                    : 'select * from eng.contactstatus where status=?';
                $result = $zend_db->fetchRow($sql, [$id]);
            }
			if ($result) {
				foreach ($result as $field=>$value) {
					if ($value) {
						$this->$field = $value;
					}
				}
			}
			else {
				throw new Exception('contacts/unknownStatus');
			}
        }
        else {
            // This is where the code goes to generate a new, empty instance.
            // Set any default values for properties that need it here
        }
    }

    public function validate()
    {
        if (!$this->status) { throw new Exception('missingRequiredFields'); }
    }

    public function save()
    {
        $this->validate();
        $data = ['status'=>$this->status];

        $this->id ? $this->update($data) : $this->insert($data);
    }

    private function update($data)
    {
        $zend_db = Database::getConnection();
        $zend_db->update('eng.contactstatus', $data, "id='{$this->id}'");
    }

    private function insert($data)
    {
        $zend_db = Database::getConnection();
        $zend_db->insert('eng.contactstatus',$data);
        if (Database::getType()=='oracle') {
            $this->id = $zend_db->lastSequenceId('eng.contactStatus_id_s');
        }
        else {
            $this->id = $zend_db->lastInsertId();
        }
    }

    //----------------------------------------------------------------
    // Generic Getters & Setters
    //----------------------------------------------------------------
    public function getId()     { return $this->id;     }
    public function getStatus() { return $this->status; }

    public function setStatus($s) { $this->status = trim($s); }

    public function __toString() { return "{$this->status}"; }
}
