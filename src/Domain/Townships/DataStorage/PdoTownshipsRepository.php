<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Townships\DataStorage;

use Application\PdoRepository;

use Domain\Townships\Entities\Township;
use Domain\Townships\UseCases\Info\InfoRequest;
use Domain\Townships\UseCases\Search\SearchRequest;

class PdoTownshipsRepository extends PdoRepository implements TownshipsRepository
{
    const TABLE = 'townships';

    public static $DEFAULT_SORT = ['name'];
    public function columns(): array
    {
        return array_keys(get_class_vars('Domain\Townships\Entities\Township'));
    }

    public function load(InfoRequest $req): Township
    {
        $select = $this->queryFactory->newSelect();
        $select->cols($this->columns())->from(self::TABLE);
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
        $select->cols($this->columns())->from(self::TABLE);

        foreach ($this->columns() as $f) {
            if (!empty($req->$f)) {
                $select->where("lower($f) like ?", strtolower($req->$f).'%');
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
        return parent::saveToTable((array)$township, self::TABLE);
    }
}
