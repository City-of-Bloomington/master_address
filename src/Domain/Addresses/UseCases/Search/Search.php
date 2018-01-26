<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Addresses\UseCases\Search;

use Domain\UseCase;
use Domain\Addresses\Parser;

class Search extends UseCase
{
    private $parser;

    public function __construct(AuthorizationService $auth, Parser $parser)
    {
        parent::__construct($auth);
        $this->parser = $parser;
    }

    public function __invoke(SearchRequest $req): SearchResponse
    {
    }
}
