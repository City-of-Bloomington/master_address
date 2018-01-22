<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Towns\DataStorage;

use Domain\PdoRepository;

use Domain\Towns\Entities\Town;
use Domain\Towns\UseCases\Info\InfoRequest;
use Domain\Towns\UseCases\Search\SearchRequest;

class PdoTownsRepository extends PdoRepository implements TownsRepository
{
    public static $FIELDS       = ['id', 'name'];
    public static $DEFAULT_SORT = ['name'];

    public function load(InfoRequest $req): Town
    {
        $select = $this->queryFactory->newSelect();
        $select->cols(self::$FIELDS)->from('towns');
        $select->where('id=?', $req->id);
        $result = $this->performSelect($select);
        if (count($result['rows'])) {
            return new Person($result['rows'][0]);
        }
        throw new \Exception('towns/unknown');
    }

    public function search(SearchRequest $req): array
    {
        $select = $this->queryFactory->newSelect();
        $select->cols(self::$FIELDS)->from('towns');

        foreach (self::$FIELDS as $f) {
            if (!empty($req->$f)) {
                $select->where("$f like ?", $req->$f);
            }
        }
        $select->orderBy(self::$DEFAULT_SORT);

        return $this->performSelect($select);
    }
}
