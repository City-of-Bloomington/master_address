<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Jurisdictions\DataStorage;

use Domain\PdoRepository;

use Domain\Jurisdictions\Entities\Jurisdiction;
use Domain\Jurisdictions\UseCases\Info\InfoRequest;
use Domain\Jurisdictions\UseCases\Search\SearchRequest;

class PdoJurisdictionsRepository extends PdoRepository implements JurisdictionsRepository
{
    const TABLE = 'jurisdictions';

    public static $DEFAULT_SORT = ['name'];
    public static $columns = ['id', 'name'];

    public function load(InfoRequest $req): Jurisdiction
    {
        $select = $this->queryFactory->newSelect();
        $select->cols(self::$columns)->from(self::TABLE);
        $select->where('id=?', $req->id);
        $result = $this->performSelect($select);
        if (count($result['rows'])) {
            return new Jurisdiction($result['rows'][0]);
        }
        throw new \Exception('jurisdictions/unknown');
    }

    public function search(SearchRequest $req): array
    {
        $select = $this->queryFactory->newSelect();
        $select->cols(self::$columns)->from(self::TABLE);

        foreach (self::$columns as $f) {
            if (!empty($req->$f)) {
                $select->where("lower($f) like ?", strtolower($req->$f));
            }
        }
        $select->orderBy(self::$DEFAULT_SORT);

        return $this->performSelect($select);
    }

    /**
     * Saves a jurisdiction and returns the ID for the jurisdiction
     */
    public function save(Jurisdiction $jurisdiction): int
    {
        return parent::saveToTable((array)$jurisdiction, self::TABLE);
    }
}
