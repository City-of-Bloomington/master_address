<?php
/**
 * @copyright 2021 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param array $this->places
 */
declare (strict_types=1);
use Application\Paginator;
?>
<table>
<?php
    foreach ($this->places as $p) {
        $name = parent::escape($p->name);
        echo "<tr><td>$name</td></tr>";
    }
?>
</table>
<?php
    if ($this->total > $this->itemsPerPage) {
        $this->paginator = new Paginator(
            $this->total,
            $this->itemsPerPage,
            $this->currentPage
        );
        $this->_include('pageNavigation.inc');
    }
?>
