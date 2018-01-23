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
use Domain\Jurisdictions\UseCases\Update\UpdateRequest;

class PdoJurisdictionsRepository extends PdoRepository implements JurisdictionsRepository
{
    protected $tablename   = 'jurisdictions';
    protected $entityClass = '\Domain\Jurisdictions\Entities\Jurisdiction';

    public static $DEFAULT_SORT = ['name'];

    public function load(InfoRequest $req): Jurisdiction
    {
        $select = $this->queryFactory->newSelect();
        $select->cols($this->columns())->from($this->tablename);
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
     * Saves a jurisdiction and returns the ID for the jurisdiction
     */
    public function save(Jurisdiction $jurisdiction): int
    {
        return parent::saveEntity($jurisdiction);
    }
}
