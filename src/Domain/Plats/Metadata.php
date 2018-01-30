<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Plats;

use Domain\Plats\DataStorage\PlatsRepository;

class Metadata
{
    private $repo;

    public function __construct(PlatsRepository $repository)
    {
        $this->repo = $repository;
    }

    public function types(): array
    {
        return ['A', 'C', 'S'];
    }

    public function cabinets(): array
    {
        static $cabinets;
        if (!$cabinets) { $cabinets = $this->repo->distinct('cabinet'); }
        return $cabinets;
    }

    public function townships(): array
    {
        static $townships;
        if (!$townships) { $townships = $this->repo->townships(); }
        return $townships;
    }
}
