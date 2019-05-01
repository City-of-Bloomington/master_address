<?php
/**
 * @copyright 2018-2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Application\Towns;
use Domain\Towns\DataStorage\TownsRepository;

use Application\PdoRepository;

use Domain\Towns\Entities\Town;
use Domain\Towns\UseCases\Info\InfoRequest;
use Domain\Towns\UseCases\Search\SearchRequest;

class PdoTownsRepository extends PdoRepository implements TownsRepository
{
    const TABLE = 'towns';

    public static $DEFAULT_SORT = ['name'];

    public function columns(): array
    {
        return array_keys(get_class_vars('Domain\Towns\Entities\Town'));
    }

    public function load(InfoRequest $req): Town
    {
        $select = $this->queryFactory->newSelect();
        $select->cols($this->columns())->from(self::TABLE);
        $select->where('id=?', $req->id);
        $result = $this->performSelect($select);
        if (count($result['rows'])) {
            return new Town($result['rows'][0]);
        }
        throw new \Exception('towns/unknown');
    }

    public function search(SearchRequest $req): array
    {
        $select = $this->queryFactory->newSelect();
        $select->cols($this->columns())->from(self::TABLE);

        foreach ($this->columns() as $f) {
            if (!empty($req->$f)) {
                $select->where("lower($f) like ?", strtolower("{$req->$f}%"));
            }
        }
        $select->orderBy(self::$DEFAULT_SORT);

        return $this->performSelect($select);
    }

    /**
     * Saves a town and returns the ID for the town
     */
    public function save(Town $town): int
    {
        return parent::saveToTable((array)$town, self::TABLE);
    }
}
