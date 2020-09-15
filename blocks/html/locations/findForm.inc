<?php
/**
 * @copyright 2020 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE
 * @param array $this->locations
 * @param int   $this->total
 * @param int   $this->itemsPerPage
 * @param int   $this->currentPage
 */
use Application\Paginator;
$title = $this->_(['location', 'locations', 100]);
?>
<section>
    <header><h1><?= $title ?></h1></header>
    <?php
        if ($this->locations) { $this->_include('locations/results.inc'); }
        else {
            echo sprintf($this->_('no_results', 'messages'), $title);
        }

        if ($this->total > $this->itemsPerPage) {
            $this->paginator = new Paginator(
                $this->total,
                $this->itemsPerPage,
                $this->currentPage
            );
            $this->_include('pageNavigation.inc');
        }
    ?>
</section>
