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
?>
<section>
    <header><h1><?= $this->_('location_search'); ?></h1></header>
    <form method="get">
        <fieldset>
        <?php
            $h = $this->template->getHelper('field');
            echo $h->field([
                'name'  => 'location',
                'id'    => 'location',
                'label' => $this->_('location'),
                'value' => isset($_GET['location']) ? parent::escape($_GET['location']) : ''
            ]);
        ?>
        </fieldset>
    </form>
</section>
<?php
    if ($this->locations) { $this->_include('locations/locations.inc'); }

    if ($this->total > $this->itemsPerPage) {
        $this->paginator = new Paginator(
            $this->total,
            $this->itemsPerPage,
            $this->currentPage
        );
        $this->_include('pageNavigation.inc');
    }
?>
