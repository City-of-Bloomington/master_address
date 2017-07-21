<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Application\Views\Plats;

use Blossom\Classes\Block;
use Blossom\Classes\Template;

class SearchView extends Template
{
    public function __construct(array $vars)
    {
        $format = !empty($_REQUEST['format']) ? $_REQUEST['format'] : 'html';
        parent::__construct('default', $format, $vars);

        if ($this->outputFormat == 'html') {
            $this->blocks[] = new Block('plats/searchForm.inc');
        }
        $this->blocks[] = new Block('plats/list.inc', ['plats' => $this->plats]);

        if ($this->outputFormat == 'html'
            && is_a($this->plats, '\Blossom\Classes\Paginator')) {
            $this->blocks[] = new Block('pageNavigation.inc', ['paginator'=> $this->plats]);
        }
    }
}
