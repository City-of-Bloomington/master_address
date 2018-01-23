<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Townships\DataStorage;

use Domain\PdoRepository;

use Domain\Townships\Entities\Township;
use Domain\Townships\UseCases\Info\InfoRequest;
use Domain\Townships\UseCases\Search\SearchRequest;
use Domain\Townships\UseCases\Update\UpdateRequest;

class PdoTownshipsRepository extends PdoRepository implements TownshipsRepository
{
    protected $tablename   = 'townships';
    protected $entityClass = '\Domain\Townships\Entities\Township';

    public static $DEFAULT_SORT = ['name'];

    public function load(InfoRequest $req): Township
    {
        $select = $this->queryFactory->newSelect();
        $select->cols($this->columns())->from($this->tablename);
        $select->where('id=?', $req->id);
        $result = $this->performSelect($select);
        if (count($result['rows'])) {
            return new Township($result['rows'][0]);
        }
        throw new \Exception('townships/unknown');
    }

    public function search(SearchRequest $req): array
    {
        $select = $this->queryFactory->newSelect();
        $select->cols($this->columns())->from($this->tablename);

        foreach ($this->columns() as $f) {
            if (!empty($req->$f)) {
                $select->where("$f like ?", $req->$f);
            }
        }
        $select->orderBy(self::$DEFAULT_SORT);

        return $this->performSelect($select);
    }

    /**
     * Saves a township and returns the ID for the township
     */
    public function save(Township $township): int
    {
        return parent::saveEntity($township);
    }
}
