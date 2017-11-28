<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Application\Streets\Names\Views;

use Blossom\Classes\Block;
use Blossom\Classes\Template;

class SearchView extends Template
{
    public function __construct(array $vars)
    {
        $format = !empty($_REQUEST['format']) ? $_REQUEST['format'] : 'html';
        parent::__construct('default', $format, $vars);

        if ($this->outputFormat == 'html') {
            $this->blocks[] = new Block('streets/searchForm.inc');
        }

        if (count($this->names)) {
            $this->blocks[] = new Block('streets/names/list.inc', ['names' => $this->names]);

            if ($this->outputFormat == 'html'
                && is_a($this->names, '\Blossom\Classes\Paginator')) {
                $this->blocks[] = new Block('pageNavigation.inc', ['paginator'=> $this->names]);
            }
        }
    }
}
