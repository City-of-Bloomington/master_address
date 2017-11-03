<?php
/**
 * @copyright 2009-2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param Address $this->address
 */
use Blossom\Classes\Block;

$locations = $this->address->getLocations();
?>
<section>
    <header>
        <h2><?= $this->_(['location', 'locations', count($locations)]); ?></h2>
        <div class="tools">
        </div>
    </header>
    <div>
    <?php
        $block = new Block('locations/info.inc');
        foreach ($locations as $l) {
            $block->location = $l;
            echo $block->render('html', $this->template);
        }
    ?>
    </div>
</section>
